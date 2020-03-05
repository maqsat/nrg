<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Storage;
use PayPost;
use App\User;
use App\Models\Package;
use App\Models\Order;
use App\Models\Basket;
use App\Models\UserClients;
use Illuminate\Http\Request;
use App\Events\Activation;
use App\Events\ShopTurnover;

class PayController extends Controller
{

    public function payTypes(Request $request)
    {
        if(isset($request->package)) $package = Package::find($request->package);
        else $package = null;

        return view('processing.types',compact('package'));
    }

        public function payPrepare(Request $request)
        {
            $package_id = 0;
            if(!is_null($request->package)){
                $package = Package::find($request->package);
                $cost = $package->cost + env('REGISTRATION_FEE');
                $package_id  = $package->id;
            }
            else $cost = env('REGISTRATION_FEE');

            $order =  Order::updateOrCreate(
                [
                    'type' => 'register',
                    'status' => 0,
                    'payment' => $request->type,
                    'uuid' => 0,
                    'user_id' => Auth::user()->id,
                ],
                ['amount' => $cost, 'package_id' => $package_id]
            );


            if($request->type == "manual"){
                return view('processing.manual', compact('order', 'cost'));
            }
            if($request->type == "paypost"){}
            if($request->type == "robokassa"){}
            if($request->type == "payeer"){}
            if($request->type == "paybox"){}
            if($request->type == "indigo"){}
        }

    public function payProcessing(Request $request, $id)
    {
        $order = Order::find($id);

        if($order->payment == 'manual'){
            if ($request->hasFile('scan')) {
                $dir = 'public/scan/'.date('Y-m-d');
                $name = $id.'.jpg';
                $request->scan->storeAs($dir, $name);
                $path = "storage/scan/".date('Y-m-d').'/'.$name;

                Order::where('id', $id)
                    ->update(
                        [
                            'scan' => $path,
                            'status' => 11,
                        ]
                    );

                return redirect('/home')->with('status', 'Квитанция успечно отправлено');
            }

            return redirect()->back()->with('status', 'Вышла ошибка при оплате квитанции');
        }


    }

    public function paypostSend(Request $request)
    {
        if(isset($request->shop)){
            $basket = Basket::where('user_id', Auth::user()->id)->whereStatus(0)->first();

            $goods_sum = DB::table('basket_good')->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->sum(DB::raw('basket_good.quantity * products.partner_cost'));

            $cost = ($goods_sum+8)*385;

            $order = Order::create([
                'type' => 'shop',
                'amount' => $cost,
                'uuid' => 0,
                'basket_id' => $basket->id,
                'user_id' => Auth::user()->id,
            ]);

            foreach($basket->basket_goods as $good){
                if($good->product["is_client"]){
                    UserClients::create([
                        'user_id'=>Auth::user()->id,
                        'client_id'=>$good->product["id"] ,
                        'order_id'=>$order->id]);
                }
            }

            $payment_webhook = "http://en-rise.com/webhook/$order->id?shop=1";

        }else{

            $package = Package::find(Auth::user()->package_id);

            $cost = $package->cost;

            $order = Order::create([
                'type' => 'register',
                'amount' => $cost,
                'uuid' => 0,
                'user_id' => Auth::user()->id,
            ]);

            $payment_webhook = "http://en-rise.com/webhook/$order->id/";

        }


        $payPost = PayPost::generateUrl([
            'amount' => $cost,
            //'amount' => 10,
            'email' => Auth::user()->email,
            'language' => 'ru',
            'currency' => 'KZT',
            'type' => 'card',
            'payment_webhook' => $payment_webhook
        ]);
        if ($payPost->success) {
            // todo white success instructions

            $paymentId = $payPost->result->payment;
            $paymentUrl = $payPost->result->url;

            Order::where('id',$order->id)->update([
               'uuid' => $paymentId,
            ]);

            return redirect($paymentUrl);
        }
        else{
            dd("Что то пошло не так, уведовимте администратора сайта");
        }

    }

    public function webhook(Request $request,$id)
    {
        $order = Order::where('id',$id)->first();

        $check = PayPost::checkStatusPay("$order->uuid");
       //$check = PayPost::checkStatusPay('a05fb2e4-9f55-480e-99ea-d795da12a763');
       //dd($check);


        Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),$check->success);
        Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),$id);

        if($check->success){

            UserClients::where('order_id',$order->id)->update(['is_complete',1]);

            Order::where('uuid',$check->result->id)
                ->update([
                    'status' => $check->result->status,
                ]);

            $uuid_order = Order::where('uuid',$check->result->id)->first();

            $user = User::find($uuid_order->user_id);

            if($check->result->status == 4 or $check->result->status == 6){

                if(isset($request->shop)){
                    Basket::whereId($uuid_order->basket_id)->update(['status' => 1]);
                    $basket = Basket::find($uuid_order->basket_id);
                    //event(new ShopTurnover($basket = $basket));

                }
                else{

                    if($user->status == 1) {
                        Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Пользователь уже активирован: $user->id");
                    }
                    else{
                        User::whereId($user->id)->update(['status' => 1]);
                        event(new Activation($user = $user));
                        Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Пользователь успешно активирован: $user->id");
                    }
                }

            }
            else{
                $success = $check->result->status;
                Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),"Ошибка оплаты с кодом: $success у пользователя: $user->id");
            }

            return redirect('/home');
        }
        else{
            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d'),'Pay Error 2');
        }

    }
}

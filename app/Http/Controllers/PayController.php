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

        if (isset($request->upgrade)){
            $current_package = Package::find($request->upgrade);
            return view('processing.types-for-upgrade',compact('package','current_package'));
        }
        else return view('processing.types',compact('package'));
    }

    public function payPrepare(Request $request)
    {
        $package_id = 0;
        if (isset($request->upgrade)){
            $package = Package::find($request->package);
            $package_id  = $package->id;
            $current_package = Package::find($request->upgrade);
            $cost = $package->cost - $current_package->cost;

            $order =  Order::updateOrCreate(
                [
                    'type' => 'upgrade',
                    'status' => 0,
                    'payment' => $request->type,
                    'uuid' => 0,
                    'user_id' => Auth::user()->id,
                ],
                ['amount' => $cost, 'package_id' => $package_id]
            );
        }
        else{
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

            $user = User::find(Auth::user()->id);
            $user->package_id = $package_id;
            $user->save();
        }


        //User::find(Auth::user()->id)->update(['package_id' => $package_id]);

        $order_id = $order->id;
        $message = "Вы собираетесь оплатить $cost$";

        if($request->type == "manual"){
            return view('processing.manual', compact('order', 'cost'));
        }
        if($request->type == "paypost"){}
        if($request->type == "robokassa"){}
        if($request->type == "payeer"){}
        if($request->type == "paybox"){}
        if($request->type == "indigo"){

            $body = json_encode([
                'operator_id' => config('pay.indigo_operator_id'),
                'order_id' => $order_id,
                'amount' => intval($cost),
                'expiration_date' => date("Y-m-d H:i:s", time() + 3600 * 24),
                'description' => $message,
                'success_url' => 'https://nrg-max.kz/home?success=1',
                'fail_url' => 'https://nrg-max.kz/home?fail=1',
                'result_url' => "https://nrg-max.kz/pay-processing/$order_id",
            ]);

            $signature = md5($body . config('pay.indigo_key'));

            $data = [
                'body' => $body,
                'signature' => $signature
            ];

            $url = 'https://billing.indigo24.com/api/v1/payment';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded","Accept: application/json"));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            if (!$response) dd("Error 1. Свяжитесь с Администратором, номер заказа  $order_id");

            $response = json_decode($response);

            if (isset($response->errors)) dd("Error 1. Свяжитесь с Администратором, номер заказа  $order_id");

            return redirect($response->redirect_url);
        }
    }

    public function payProcessing(Request $request, $id)
    {
        $order = Order::find($id);
        $user_id = $order->user_id;

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
        if($order->payment == 'indigo'){

            $body = $request->body;
            $signature = $request->signature;

            $mysignature = md5($body . config('pay.indigo_key'));

            if ($signature != $mysignature) return;

            $data = json_decode($body);
            if (!$data) return;

            if ($data->status != 'successful') return;

            $order->status = 4;
            $order->save();

            $user  = User::find($user_id);
            event(new Activation($user = $user));
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

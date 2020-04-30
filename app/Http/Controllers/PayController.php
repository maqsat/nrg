<?php

namespace App\Http\Controllers;

use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Models\UserProgram;
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
        elseif (isset($request->basket)){
            $balance = Balance::getBalance(Auth::user()->id);
            $revitalization = Balance::revitalizationBalance(Auth::user()->id);

            $basket = Basket::find($request->basket);
            $all_cost = DB::table('basket_good')
                ->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->sum(DB::raw('basket_good.quantity*products.partner_cost'));//['products.*','basket_good.quantity']

            return view('processing.types-for-shop',compact('basket','all_cost','revitalization','balance'));
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
            if (is_null($current_package))  $cost = $package->cost - 0;
            else $cost = $package->cost - $current_package->cost;

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
        elseif(isset($request->basket)){

            $cost = DB::table('basket_good')
                ->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $request->basket])
                ->sum(DB::raw('basket_good.quantity*products.partner_cost'));//['products.*','basket_good.quantity']

            if($request->type == 'revitalization'){
                $order =  Order::updateOrCreate(
                    [
                        'type' => 'shop',
                        'status' => 0,
                        'payment' => $request->type,
                        'uuid' => 0,
                        'user_id' => Auth::user()->id,
                        'basket_id' => $request->basket,
                        'not_original' => 1
                    ],
                    ['amount' => $cost, 'package_id' => 0]
                );

            }
            else{
                $order =  Order::updateOrCreate(
                    [
                        'type' => 'shop',
                        'status' => 0,
                        'payment' => $request->type,
                        'uuid' => 0,
                        'user_id' => Auth::user()->id,
                        'basket_id' => $request->basket
                    ],
                    ['amount' => $cost, 'package_id' => 0]
                );
            }

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
        if($request->type == "paypost"){
            $payment_webhook = "http://nrg-max.kz/pay-processing/$order_id/";

            $payPost = PayPost::generateUrl([
                'amount' => $cost*env('DOLLAR_COURSE'),
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
        if($request->type == "robokassa"){}
        if($request->type == "payeer"){

            $m_shop = '1014438338';
            $m_curr = 'USD';
            $m_key = 'G1UvTbE6370Q0Vj3';
            $m_orderid = $order_id;
            $m_amount = number_format($cost, 2, '.', '');
            $m_desc = base64_encode($message);

            $arParams = array(
                'success_url' => 'http://nrg-max.kz/home',
                'fail_url' => 'http://nrg-max.kz/home',
                'status_url' => "http://nrg-max.kz/pay-processing/$order_id/",
            );

            $key = md5(''.$m_orderid);
            $m_params = @urlencode(base64_encode(openssl_encrypt(json_encode($arParams), 'AES-256-CBC', $key, OPENSSL_RAW_DATA)));

            $arHash = array($m_shop, $m_orderid, $m_amount, $m_curr, $m_desc,json_encode($arParams));

            $arHash[] = $m_key;
            $sign = strtoupper(hash('sha256', implode(':', $arHash)));







            return view('processing.payeer', compact('m_shop','m_orderid','m_amount','m_curr','m_desc','m_key','sign','message','cost'));
        }
        if($request->type == "paybox"){}
        if($request->type == "indigo"){

            $body = json_encode([
                'operator_id' => config('pay.indigo_operator_id'),
                'order_id' => $order_id,
                'amount' => intval($cost),
                'expiration_date' => date("Y-m-d H:i:s", time() + 3600 * 240000),
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
        if($request->type == "balance"){

            if(Balance::getBalance(Auth::user()->id) >= $order->amount)
                return redirect()->back()->with('status', 'У вас недостаточно средств!');

            Order::where( 'id',$order_id)
                ->update(
                    [
                        'status' => 1,
                    ]
                );
            Basket::whereId($order->basket_id)->update(['status' => 1]);

            $order_pv = Hierarchy::orderPv($order_id, Auth::user()->id);

            $data = [];
            $data['pv'] = $order_pv;
            $data['user_id'] = Auth::user()->id;
            Balance::changeBalance(Auth::user()->id,$order->amount,'shop',Auth::user()->id,1,0,0);

            event(new ShopTurnover($data = $data));

            return redirect('/story-store');
        }
        if($request->type == "revitalization"){

            if(Balance::revitalizationBalance(Auth::user()->id) >= $order->amount)
                return redirect()->back()->with('status', 'У вас недостаточно средств!');

            Order::where( 'id',$order_id)
                ->update(
                    [
                        'status' => 1,
                    ]
                );
            Basket::whereId($order->basket_id)->update(['status' => 1]);

            $order_pv = Hierarchy::orderPv($order_id, Auth::user()->id);

            $data = [];
            $data['pv'] = $order_pv;
            $data['user_id'] = Auth::user()->id;
            Balance::changeBalance(Auth::user()->id,$order->amount,'revitalization-shop',Auth::user()->id,1,0,0);

            event(new ShopTurnover($data = $data));

            return redirect('/story-store');
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
        if($order->payment == 'paypost'){
            $check = PayPost::checkStatusPay("$order->uuid");

            if($check->success){

                Order::where('uuid',$check->result->id)
                    ->update([
                        'status' => $check->result->status,
                    ]);

                $uuid_order = Order::where('uuid',$check->result->id)->first();

                $user = User::find($uuid_order->user_id);

                if($check->result->status == 4 or $check->result->status == 6){

                    if($uuid_order->type == 'shop'){
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
        if($order->payment == 'payeer'){
            if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return 0;
            if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
            {
                $m_key = '0xIW0GjW70tVnqPW';

                $arHash = array(
                    $_POST['m_operation_id'],
                    $_POST['m_operation_ps'],
                    $_POST['m_operation_date'],
                    $_POST['m_operation_pay_date'],
                    $_POST['m_shop'],
                    $_POST['m_orderid'],
                    $_POST['m_amount'],
                    $_POST['m_curr'],
                    $_POST['m_desc'],
                    $_POST['m_status']
                );

                if (isset($_POST['m_params']))
                {
                    $arHash[] = $_POST['m_params'];
                }

                $arHash[] = $m_key;
                $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

                if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
                {
                    $order = Order::find($_POST['m_orderid']);
                    Order::where('id',$_POST['m_orderid'])->update([
                        'status' => 1,
                    ]);
                    $data = [];
                    //$data['inviter_id'] = $order->inviter_id;
                    $data['program_id'] = $order->program_id;
                    $data['user_id'] = $order->user_id;

                    event(new Activation($user = $data));
                    ob_end_clean(); exit($_POST['m_orderid'].'|success');
                }
                ob_end_clean(); exit($_POST['m_orderid'].'|error');
            }
            else return 0;
        }
    }
}

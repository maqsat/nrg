<?php

namespace App\Http\Middleware;

use App\Events\Activation;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

use DB;
use Storage;
use App\Models\Package;
use App\Models\Basket;
use App\Events\ShopTurnover;
use PayPost;

class Order
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $orders = \App\Models\Order::where('user_id',Auth::user()->id)->where('status',0)->where('uuid','!=',null)->where('uuid','!=',0)->get();

        foreach ($orders as $item){
            $check = PayPost::checkStatusPay("$item->uuid");

            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt','-----own webhook');
            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt',"order_id:".$item->id);
            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt',"order_status:".$check->success);

            if($check->success){

                \App\Models\Order::where('uuid',$check->result->id)
                    ->update([
                        'status' => $check->result->status,
                    ]);

                $uuid_order = \App\Models\Order::where('uuid',$check->result->id)->first();

                $user = User::find($uuid_order->user_id);

                if($check->result->status == 4 or $check->result->status == 6){

                    if(isset($request->shop)){
                        Basket::whereId($uuid_order->basket_id)->update(['status' => 1]);
                        $basket = Basket::find($uuid_order->basket_id);
                        //event(new ShopTurnover($basket = $basket));

                    }
                    else{

                        if($user->status == 1) {
                            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt',"Пользователь уже активирован: $user->id");
                        }
                        else{
                            User::whereId($user->id)->update(['status' => 1]);
                            event(new Activation($user = $user));
                            Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt',"Пользователь успешно активирован: $user->id");
                        }
                    }

                }
                else{
                    $success = $check->result->status;
                    Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt',"Ошибка оплаты с кодом: $success у пользователя: $user->id");
                }

                return redirect('/home');
            }
            else{
                Storage::disk('local')->prepend('/paypost_logs/'.date('Y-m-d').'.txt','Pay Error 2');
            }
        }


        return $next($request);
    }
}

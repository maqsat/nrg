<?php

namespace App\Http\Controllers;

use App\Models\Order;
use DB;
use Auth;
use App\Models\Basket;
use App\Models\BasketGood;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class BasketController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', Auth::user()->id)->where('type','shop')->whereNotIn('status',[1,6,4])->where('payment','manual')->orderBy('id','desc')->first();

        if(is_null($orders) or $orders->status == 12) {
            if(isset($request['id']))
                $basket = Basket::where('id', $request['id'])->whereStatus(1)->first();
            else
                $basket = Basket::where('user_id', Auth::user()->id)->whereStatus(0)->first();

            if(is_null($basket))
                return redirect('/main-store')->with('status', 'Ваша корзина пуста, сначала добавьте товары в корзину');
            $user_program = UserProgram::where('user_id',Auth::user()->id)->first();


            /*$goods=[];
            foreach($basket->basket_goods as $item){
                array_push($goods,$item->product);
            }*/
            // $goods = $basket->basket_goods->product;
            //dd($goods);

            $goods = DB::table('basket_good')
                ->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->get(['products.*','basket_good.quantity']);

            return view('basket.show',compact('basket','user_program','goods'));
        }
        else return redirect('main-store');



    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'good_id' => 'required',
        ]);
        $basket = Basket::firstOrCreate([
            'user_id' => $request->user_id,
            'status' => 0
        ]);
        $basket_good=BasketGood::firstOrCreate(['basket_id'=>$basket->id ,'good_id'=>$request->good_id]);

        /*Удалить товар из корзины*/
        if($request->is_delete=="true"){
            $quantity=$basket_good->quantity;
            $cost=$basket_good->product->partner_cost;
            /*$cv=$basket_good->product->cv;*/
            $pv=$basket_good->product->pv;
            $basket_good->delete();

            $result['product_total_sum']=$quantity*$cost;
            /*$result['product_total_cv']=$quantity*$cv;*/
            $result['product_total_pv']=$quantity*$pv;
            $result['messages'] = "Товар удален!";
        }
        /*Увеличить товар на одну еденицу*/
        elseif($request->is_increase=="true")  {
            if (!is_null($basket_good)) {
                $basket_good->increment('quantity');
            } else {
                $basket_good=BasketGood::create([
                    'basket_id' => $basket->id,
                    'good_id' => $request->good_id,
                    'quantity' => 1
                ]);
            }
            $result['status'] = true;
            $result['messages'] = "Добавлено количество!";

            /*Все товары в баскете*/
           $goods = DB::table('basket_good')->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->get(['products.*','basket_good.quantity']);

            $result['quantity']=$basket_good->quantity;
           /* $result['cv']=$basket_good->product->cv;*/
            $result['pv']=$basket_good->product->pv;
            $result['cost']=$basket_good->product->partner_cost;
            $result['product_total_sum']=$result['quantity']*$result['cost'];
          /*  $result['product_total_cv']=$result['quantity']*$result['cv'];*/
            $result['product_total_pv']=$result['quantity']*$result['pv'];
            $result['goods'] =$goods;
        }
        elseif($request->is_decrease=="true"){
            if (!is_null($basket_good)) {
                DB::table('basket_good')->where(['basket_id' => $basket->id])
                    ->where(['good_id' => $request->good_id])
                    ->decrement('quantity');
            } else {
                /**/
            }
            $result['status'] = true;
            $result['messages'] = "Убавлено количество!";
            /*Все товары в баскете*/
            $goods = DB::table('basket_good')->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->get(['products.*','basket_good.quantity']);

            $quantity=$goods->pluck ('quantity')[$request->botton];
            $cost=$goods->pluck ('partner_cost')[$request->botton];
            /*$cv=$goods->pluck ('cv')[$request->botton];*/
            $pv=$goods->pluck ('pv')[$request->botton];
            $result['quantity']=$quantity;
           /* $result['cv']=$cv;*/
            $result['pv']=$pv;
            $result['cost']=$cost;
            $result['product_total_sum']=$quantity*$cost;
            /*$result['product_total_cv']=$quantity*$cv;*/
            $result['product_total_pv']=$quantity*$pv;
            $result['goods'] =$goods;
            if($quantity==0){
                DB::table('basket_good')->where(['basket_id' => $basket->id])
                    ->where(['good_id' => $request->good_id])
                    ->delete();
            }
        }
        return $result;
    }
    public function show(Basket $basket)
    {
        //
    }
    public function edit(Basket $basket)
    {
        //
    }
    public function update(Request $request, Basket $basket)
    {
        //
    }
    public function destroy(Basket $basket)
    {
        //
    }
    public function buycontact(Request $request){
        $request->validate([
            'user_id' => 'required',
            'good_id' => 'required',
        ]);
        $basket = Basket::firstOrCreate([
            'user_id' => $request->user_id,
            'status' => 0
        ]);
        $basket_good=BasketGood::where(['basket_id'=>$basket->id ,'good_id'=>$request->good_id])->first();

        /*Удалить товар из корзины*/
        if($request->is_delete=="true"){
            $quantity=$basket_good->quantity;
            $cost=$basket_good->product->partner_cost;
            $cv=$basket_good->product->cv;
            $basket_good->delete();

            $result['product_total_sum']=$quantity*$cost;
            $result['product_total_cv']=$quantity*$cv;
            $result['messages'] = "Товар удален!";
        }
        /*Увеличить товар на одну еденицу*/
        elseif($request->add=="true")  {
            $basket_good=BasketGood::create([
                    'basket_id' => $basket->id,
                    'good_id' => $request->good_id,
                    'quantity' => 1
                ]);

            $result['status'] = true;
            $result['messages'] = "Добавлено количество!";

            /*Все товары в баскете*/
            $goods = DB::table('basket_good')->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->get(['products.*','basket_good.quantity']);

            $result['quantity']=$basket_good->quantity;
            $result['cv']=$basket_good->product->cv;
            $result['cost']=$basket_good->product->partner_cost;
            $result['product_total_sum']=$result['quantity']*$result['cost'];
            $result['product_total_cv']=$result['quantity']*$result['cv'];
            $result['goods'] =$goods;
        }
        elseif($request->is_decrease=="true"){
            if (count($basket_good) > 0) {
                DB::table('basket_good')->where(['basket_id' => $basket->id])
                    ->where(['good_id' => $request->good_id])
                    ->decrement('quantity');
            } else {
                /**/
            }
            $result['status'] = true;
            $result['messages'] = "Убавлено количество!";
            /*Все товары в баскете*/
            $goods = DB::table('basket_good')->join('products','basket_good.good_id','=','products.id')
                ->where(['basket_id' => $basket->id])
                ->get(['products.*','basket_good.quantity']);

            $quantity=$goods->pluck ('quantity')[$request->botton];
            $cost=$goods->pluck ('partner_cost')[$request->botton];
            $cv=$goods->pluck ('cv')[$request->botton];
            $result['quantity']=$quantity;
            $result['cv']=$cv;
            $result['cost']=$cost;
            $result['product_total_sum']=$quantity*$cost;
            $result['product_total_cv']=$quantity*$cv;
            $result['goods'] =$goods;
            if($quantity==0){
                DB::table('basket_good')->where(['basket_id' => $basket->id])
                    ->where(['good_id' => $request->good_id])
                    ->delete();
            }
        }
        return $result;
    }
}

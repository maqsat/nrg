<?php

namespace App\Http\Controllers;

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
        if(isset($request->id))
            $basket = Basket::where('id', $request->id)->whereStatus(1)->first();
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
        $basket_good=BasketGood::where(['basket_id'=>$basket->id ,'good_id'=>$request->good_id])->first();

        /*Удалить товар из корзины*/
        if($request->is_delete=="true"){
            $quantity=$basket_good->quantity;
            $cost=$basket_good->product->partner_cost;
            $cv=$basket_good->product->cv;
            $qv=$basket_good->product->qv;
            $basket_good->delete();

            $result['product_total_sum']=$quantity*$cost;
            $result['product_total_cv']=$quantity*$cv;
            $result['product_total_qv']=$quantity*$qv;
            $result['messages'] = "Товар удален!";
        }
        /*Увеличить товар на одну еденицу*/
        elseif($request->is_increase=="true")  {
            if (count($basket_good) > 0) {
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
            $result['cv']=$basket_good->product->cv;
            $result['qv']=$basket_good->product->qv;
            $result['cost']=$basket_good->product->partner_cost;
            $result['product_total_sum']=$result['quantity']*$result['cost'];
            $result['product_total_cv']=$result['quantity']*$result['cv'];
            $result['product_total_qv']=$result['quantity']*$result['qv'];
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
            $qv=$goods->pluck ('qv')[$request->botton];
            $result['quantity']=$quantity;
            $result['cv']=$cv;
            $result['qv']=$qv;
            $result['cost']=$cost;
            $result['product_total_sum']=$quantity*$cost;
            $result['product_total_cv']=$quantity*$cv;
            $result['product_total_qv']=$quantity*$qv;
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

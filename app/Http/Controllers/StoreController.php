<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserProgram;
use DB;
use Auth;
use Carbon\Carbon;
use App\Facades\Balance;
use App\Models\Basket;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function store(Request $request)
    {
        if($user = Auth::user())
        {
            $orders = Order::where('user_id', Auth::user()->id)->where('type','shop')->where('payment','manual')->orderBy('id','desc')->first();

            if($user->type==1){
                $list = Product::whereNull('is_client')->orderBy('created_at','desc')->paginate();
                $tag = Tag::all();
                if($request->has('tag')){
                    $list = Tag::find($request->tag)->products;
                }
                return view('product.user-main', compact('list','tag','orders'));
            }
            else{
                $list = Product::whereNull('is_client')->orderBy('created_at','desc')->paginate();
                $tag = Tag::all();
                if($request->has('tag')){
                    $list = Tag::find($request->tag)->products;
                }
                return view('product.main', compact('list','tag','orders'));
            }
        }
        else{
            $list = Product::whereNull('is_client')->orderBy('created_at','desc')->paginate();
            $tag = Tag::all();
            if($request->has('tag')){
                $list = Tag::find($request->tag)->products;
            }
            return view('product.user-main', compact('list','tag'));
        }

    }

    public function story()
    {
        $list = Basket::join('basket_good','basket_good.basket_id','=','baskets.id')
            ->join('products','basket_good.good_id','=','products.id')
            ->where('baskets.user_id', Auth::user()->id)
            ->where('baskets.status',1)
            ->select('baskets.*',DB::raw('sum(basket_good.quantity * products.partner_cost) as cost'),DB::raw('sum(basket_good.quantity * products.cv) as cv'),DB::raw('sum(basket_good.quantity) as quantity'))
            //->groupBy('baskets.id')
            ->paginate();
        //dd($list);


        return view('basket.story',compact('list'));
    }

    public function activationStore()
    {
        $status = UserProgram::where('user_id',Auth::user()->id)->first();

        if($status->status_id < 5) $data = [];
        else $data = $this->activationCore();

        return view('basket.activation',compact('data'));
    }

    public function activationCore()
    {
        $sp_date = Notification::where('status_id','=',5)->where('user_id','=',Auth::user()->id)->first();
        $start_activation = Carbon::createFromDate(2020, 01, 01);

        if($sp_date->created_at < $start_activation) $startDate = date("Y-m-d",strtotime($start_activation));
        else $startDate = date("Y-m-d",strtotime($sp_date->created_at));

        $endDate = date("Y-m-d");

        $months = Balance::getMonthByRange($startDate,$endDate);


        $data = [];

        foreach ($months as $key => $item){
            $data[$key]['month'] = $item;

            $startDate = date("Y-m-d",strtotime($item));
            $endDate = date('Y-m-d',strtotime(date("Y-m-t") . "+1 days"));

            $sum = Order::whereBetween('updated_at',[$startDate,$endDate])
                ->where('type','shop')
                ->where('user_id', Auth::user()->id)
                ->where(function($query){
                    $query->where('status',4)
                        ->orWhere('status',6);
                })
                ->sum('amount');


            $data[$key]['cost'] =   $sum/env('DOLLAR_COURSE');;
            $data[$key]['activation'] = env('ACTIVATION_COST');
        }

        return $data;
    }

    public function show($id){
        if(Auth::check()){
            $user = Auth::user();
            if($user->type==1){
                $product = Product::find($id);
                return view('product.user-single',compact('product'));
            }
            else{
                $orders = Order::where('user_id', Auth::user()->id)->where('type','shop')->where('payment','manual')->orderBy('id','desc')->first();
                if(is_null($orders) or $orders->status == 12) {
                    $product = Product::find($id);
                    return view('product.single',compact('product'));
                }
               else return redirect('main-store');
            }
        }
        else{
            $product = Product::find($id);
            return view('product.user-single',compact('product'));
        }


    }


}

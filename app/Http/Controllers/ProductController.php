<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Basket;
use App\Models\Order;
use App\Models\Tag;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('admin_product_view')) {
            abort('401');
        }

        $list = Product::orderBy('created_at','desc')->paginate();
        return view('product.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('admin_product_create')) {
            abort('401');
        }

        $tags=Tag::all();
        return view('product.create' , compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_product_create')) {
            abort('401');
        }

        if($request->has('tags')){
            $vowels = array("{", "}", "\"", ":", "[", "]");
            $onlyconsonants = str_replace($vowels, "", $request->tags);

           // $str2 = substr($str, 4);

            $tags =explode(",", $onlyconsonants);
            $tags2=[];
            foreach ($tags as $item){
                array_push($tags2,substr($item, 5));
            }
        }


        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cost' => 'required',
            'pv' => 'required',
            'partner_cost' => 'required',
            'category_id' => 'required',
            'image1' => 'required','file',
        ]);

        if ($request->hasFile('image1')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image1->getFilename().'.'.$request->image1->getClientOriginalExtension();
            $path = $request->image1->storeAs('public/images', $tmp_path);
            $request->image1 = str_replace("public", "storage", $path);
        }

        if ($request->hasFile('image2')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image2->getFilename().'.'.$request->image2->getClientOriginalExtension();
            $path = $request->image2->storeAs('public/images', $tmp_path);
            $request->image2 = str_replace("public", "storage", $path);
        }

        if ($request->hasFile('image3')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image3->getFilename().'.'.$request->image3->getClientOriginalExtension();
            $path = $request->image3->storeAs('public/mages', $tmp_path);
            $request->image3 = str_replace("public", "storage", $path);
        }
        if($request->has('tags')){
            $tags =explode(",", $request->tags);
        }


        Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'cost' => $request->cost,
            'partner_cost' => $request->partner_cost,
            'category_id' => $request->category_id,
            'sale' => $request->sale,
            'pv' => $request->qv,
            'image1' => $request->image1,
            'image2' => $request->image2,
            'image3' => $request->image3,
        ]);


        return redirect()->back()->with('status', 'Успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if(!Gate::allows('admin_product_view')) {
            abort('401');
        }

        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product, $id)
    {
        if(!Gate::allows('admin_product_edit')) {
            abort('401');
        }

        $product = $product->find($id);
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product,$id)
    {
        if(!Gate::allows('admin_product_edit')) {
            abort('401');
        }

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'cost' => 'required',
            'pv' => 'required',
            'partner_cost' => 'required',
            'category_id' => 'required',
            'image1' => 'required','file',
        ]);

        if ($request->hasFile('image1')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image1->getFilename().'.'.$request->image1->getClientOriginalExtension();
            $path = $request->image1->storeAs('public/images', $tmp_path);
            $request->image1 = str_replace("public", "storage", $path);
        }

        if ($request->hasFile('image2')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image2->getFilename().'.'.$request->image2->getClientOriginalExtension();
            $path = $request->image2->storeAs('public/images', $tmp_path);
            $request->image2 = str_replace("public", "storage", $path);
        }

        if ($request->hasFile('image3')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->image3->getFilename().'.'.$request->image3->getClientOriginalExtension();
            $path = $request->image3->storeAs('public/mages', $tmp_path);
            $request->image3 = str_replace("public", "storage", $path);
        }

        Product::where('id',$id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'cost' => $request->cost,
            'partner_cost' => $request->partner_cost,
            'category_id' => $request->category_id,
            'sale' => $request->sale,
            'cv' => $request->cv,
            'pv' => $request->pv,
            'image1' => $request->image1,
            'image2' => $request->image2,
            'image3' => $request->image3,
        ]);


        return redirect()->back()->with('status', 'Успешно добавлено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(!Gate::allows('admin_product_destroy')) {
            abort('401');
        }

        //
    }
    public function orders(Request $request){
        if(!Gate::allows('admin_orders_access')) {
            abort('401');
        }

        $orders_query = Order::orderBy('updated_at' , 'desc')->where('type','shop')->where('status',11);

        if(isset($request->shop)) $orders_query = $orders_query->where('status',11);
        else $orders_query = $orders_query->where('status',4)->orWhere('status',6);

        $orders = $orders_query->paginate(10);

        return view('order', compact('orders'));
    }

    public function basket_items($basket_id){

        $items = Basket::find($basket_id)->products;
        return view('items', compact('items'));
    }

    public function changedeliverystatus(Request $request){
        $order = Order::where('id',$request->order_id)->first();
        $order->delivery_status=$request->delivery_value;
        if($order->save()) {
            return ['status' => true];
        }
        else{
            return ['status' => false];
        }
    }
    public function userorders(Request $request){
        $orders = Order::where('user_id',Auth::user()->id)
            ->where(function ($q) {
                $q->where('status',4)->orWhere('status', 6);
            })
            ->orderBy('updated_at' , 'desc')
            ->paginate();
        return view('userorders', compact('orders'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserClients;
use App\Events\Activation;
use App\Models\Product;
use Validator;
use Auth;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('s')&& !empty($request->s)){
            $list = Product::where('title','like','%'.$request->s.'%')->paginate(30);
        }
        else {
            $list = Product::where('is_client', 1)->paginate(30);
        }
        return view('client.index', compact('list'));
    }
    public function getclientswithoutphone(){
        $list = Product::whereNotNull('is_client')->orderBy('id','desc')->paginate(30);
        $userclients=UserClients::where('user_id',Auth::user()->id)->where('is_complete',1)->pluck('id')->toArray();
        return view('client.withoutphone', compact('list','userclients'));
    }
    public function create()
    {
        return view('client.create');
    }
    public function store(Request $request)
    {
        Product::create([
            'title'=>$request->name,
            'description'=>'номер клиента',
            'cost'=>200,
            'partner_cost'=>200,
            'category_id'=>1,
            'cv'=>10,
            'qv'=>10,
            'image1'=>'123',
            'is_client'=>1,
            'client_phone'=>$request->number,
        ]);
        return redirect('/client');
    }

    public function show($id)
    {

    }
    public function edit($id)
    {

    }
    public function update(Request $request, $id)
    {

    }
    public function destroy($id)
    {

    }
}

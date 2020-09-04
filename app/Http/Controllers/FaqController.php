<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use Session;

class FaqController extends Controller
{
    public function index()
    {
        $faq=Faq::where('is_admin','1')->get();
        return view('faq.adminfaq',compact('faq'));
    }
    public function allguestfaq(){
        $faq=Faq::where('is_admin',0)->get();
        return view('faq.faq',compact('faq'));
    }
    public function alladminfaq(){
        $faq=Faq::where('is_admin',1)->get();
        return view('faq.faq',compact('faq'));
    }
    public function create()
    {
        return view ('faq.faq-create');
    }
    public function store(Faq $faq)
    {
        $validatedData =request()->validate([
            'question' => 'required',
            'answer' => 'required',
        ],[
            'question.required' => 'Заполните поле Вопрос',
            'answer.required' => 'Заполните поле Ответ',
        ]);
        $faq::create(request(['answer','question','is_admin']));
        Session::flash('message', "Успешно");
        return redirect()->back();

    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $faq=Faq::find($id);
        return view('faq.faq-edit',compact('faq'));
    }
    public function update(Request $request, $id)
    {
        $validatedData =request()->validate([
            'question' => 'required',
            'answer' => 'required',
        ],[
            'question.required' => 'Заполните поле Вопрос',
            'answer.required' => 'Заполните поле Ответ',
        ]);
        Faq::find($id)->update(['answer'=>$request->answer,
        'question'=>$request->question,'is_admin'=>$request->is_admin]);
        Session::flash('message', "Успешно изменен");
        return redirect()->back();
    }
    public function destroy($id)
    {
        Faq::find($id)->delete();
        return redirect()->back();
    }
}

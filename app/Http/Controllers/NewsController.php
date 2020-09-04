<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\News;
use Session;

class NewsController extends Controller
{
    public function index()
    {
        if(!Gate::allows('admin_news_view')) {
            abort('401');
        }

        $news=News::all();
        return view('news.news',compact('news'));
    }
    public function create()
    {
        if(!Gate::allows('admin_news_create')) {
            abort('401');
        }

        return view('news.news-create');
    }
    public function store(Request $request ,News $news)
    {
        if(!Gate::allows('admin_news_create')) {
            abort('401');
        }

        $validatedData = $request->validate([
            'news_name' => 'required|max:150',
            'news_text' => 'required|max:2000',
            'news_date'=> 'required | date',
            'news_image'=> 'file',
            'news_desc'=>  'required|max:1000',
        ],[
            'news_name.required' => 'Заполните поле Заголовок',
            'news_text.required' => 'Заполните поле Текст',
            'news_date.required' => 'Заполните поле Дата',
            'news_image.required' => 'Заполните поле Фото',
            'news_desc.required' => 'Заполните поле Краткое описание',
            'image' => 'Загрузите фото',

        ]);
        if ($request->hasFile('news_image')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->news_image->getFilename().'.'.$request->news_image->getClientOriginalExtension();
            $path = $request->news_image->storeAs('public/images', $tmp_path);
            $request->news_image = str_replace("public", "storage", $path);

        }
        $news::create([
            'news_name' => $request->news_name,
            'news_text' => $request->news_text,
            'news_date' => $request->news_date,
            'news_image' => $request->news_image,
            'news_desc' => $request->news_desc,

        ]);
        return redirect('/news');
    }
    public function show($id)
    {
        if(!Gate::allows('admin_news_view')) {
            abort('401');
        }

        //
    }
    public function edit(News $news)
    {
        if(!Gate::allows('admin_news_edit')) {
            abort('401');
        }

        return view('news.news-edit',compact('news'));
    }
    public function update(Request $request, $id)
    {
        if(!Gate::allows('admin_news_edit')) {
            abort('401');
        }

        $validatedData = $request->validate([
            'news_name' => 'required|max:150',
            'news_text' => 'required|max:2000',
            'news_date'=> 'required | date',
            'news_desc'=>  'required|max:1000',
        ],[
            'news_name.required' => 'Заполните поле Заголовок',
            'news_text.required' => 'Заполните поле Текст',
            'news_date.required' => 'Заполните поле Дата',
            'news_image.required' => 'Заполните поле Фото',
            'news_desc.required' => 'Заполните поле Краткое описание',
            'image' => 'Загрузите фото',

        ]);
        if ($request->hasFile('news_image')) {
            News::find($id)->update([
                'news_name'=>$request->news_name,
                'news_text'=>$request->news_text,
                'news_date'=>$request->news_date,
                'news_image'=>$request->news_image,
                'news_desc'=>$request->news_desc
            ]);
        }
        else{
            News::find($id)->update([
                'news_name'=>$request->news_name,
                'news_text'=>$request->news_text,
                'news_date'=>$request->news_date,
                'news_desc'=>$request->news_desc
            ]);
        }


        Session::flash('message', "Успешно изменен");
        return redirect()->back();
    }
    public function destroy(News $news)
    {
        if(!Gate::allows('admin_news_destroy')) {
            abort('401');
        }

        $news->delete();
        return redirect('/news');
    }
}

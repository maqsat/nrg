<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Faq;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Gate::allows('admin_page_view')) {
            abort('401');
        }

        $list = Page::orderBy('created_at','desc')->paginate(30);

        return view('page.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!Gate::allows('admin_page_create')) {
            abort('401');
        }

        return view('page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Gate::allows('admin_page_create')) {
            abort('401');
        }

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        if(!Gate::allows('admin_page_view')) {
            abort('401');
        }

        return view('page.show',compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        if(!Gate::allows('admin_page_edit')) {
            abort('401');
        }

        return view('page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        if(!Gate::allows('admin_page_edit')) {
            abort('401');
        }

        Page::where('id',$page->id)->update([
            'title' => $request->title,
            //'content' => $request->content,
        ]);

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        if(!Gate::allows('admin_page_destroy')) {
            abort('401');
        }

        //
    }
}

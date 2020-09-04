<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileApp\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::paginate(10);
        return view("course.all",compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Course $course)
    {
        if ($request->hasFile('preview')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->preview->getFilename().'.'.$request->preview->getClientOriginalExtension();
            $path = $request->preview->storeAs('public/images', $tmp_path);
            $request->preview = str_replace("public", "storage", $path);

        }
        $course::create([
            'title' => $request->title,
            'description' => $request->description,
            'preview' => $request->preview,
            'total_time' => $request->total_time1.":".$request->total_time2.":00"

        ]);
        return redirect('/course/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course=Course::where('id',$id)->first();
        return view('course.edit',compact('course'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $array=[
            'title' => $request->title,
            'description' => $request->description
        ];


        if ($request->hasFile('preview')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->preview->getFilename().'.'.$request->preview->getClientOriginalExtension();
            $path = $request->preview->storeAs('public/images', $tmp_path);
            $request->preview = str_replace("public", "storage", $path);
            $array['preview'] =  $request->preview;

        }
        Course::where('id',$id)->first()->update($array);
        return redirect('/course');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Course::where('id',$id)->first()->delete();
        return redirect()->back();
    }
}

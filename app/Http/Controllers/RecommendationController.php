<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileApp\Recommendation;
use App\Models\MobileApp\Course;

class RecommendationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recommendations =Recommendation::paginate(10);
        return view("recommendation.index",compact('recommendations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::all();
        return view('recommendation.create_recommendation' , compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required',
            'description' => 'required',
            'title'=> 'required',
            'file'=> 'required',
            'photo'=>'required'
        ],[
            'course_id.required' => 'Выберите курс',
            'description.required' => 'Заполните поле Описание',
            'file.required' => 'Заполните поле фото',
            'title.required' => 'Заполните поле Название'

        ]);

        if ($request->hasFile('file')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->file->getFilename().'.'.$request->file->getClientOriginalExtension();
            $path = $request->file->storeAs('public/images', $tmp_path);
            $request->file = str_replace("public", "storage", $path);
            $image_mime = $request->file('file')->getClientOriginalExtension();

            if($image_mime=='jpeg' || $image_mime=='jpg'|| $image_mime=='png'){
                $type="photo";
            }
            else{
                $type="video";
            }

        }
        if ($request->hasFile('photo')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->photo->getFilename().'.'.$request->photo->getClientOriginalExtension();
            $path = $request->photo->storeAs('public/images', $tmp_path);
            $request->photo = str_replace("public", "storage", $path);

        }
        Recommendation::create([
            'course_id' => $request->course_id,
            'description' => $request->description,
            'title' => $request->title,
            'file' => $request->file,
            'photo' => $request->photo,
            'type'=>$type
        ]);
        return redirect('/recommendations');
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
    public function edit(Recommendation $recommendation)
    {
        $courses = Course::all();
        return view('recommendation.edit_recommendation' , compact('courses','recommendation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $array=[
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->description,
        ];
        if ($request->hasFile('file')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->file->getFilename().'.'.$request->file->getClientOriginalExtension();
            $path = $request->file->storeAs('public/images', $tmp_path);
            $request->file = str_replace("public", "storage", $path);
            $array['file'] = $request->file;
        }
        if ($request->hasFile('photo')) {
            $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->photo->getFilename().'.'.$request->photo->getClientOriginalExtension();
            $path = $request->photo->storeAs('public/images', $tmp_path);
            $request->photo = str_replace("public", "storage", $path);
            $array['photo'] = $request->photo;
        }
        $recommendation = Recommendation::where('id',$id)->first();
        $recommendation->update($array);
        return redirect('/recommendations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Recommendation::where('id',$id)->delete();
        return redirect()->back();
    }
}

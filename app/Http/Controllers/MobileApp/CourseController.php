<?php

namespace App\Http\Controllers\MobileApp;

use App\Models\MobileApp\Course;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MobileApp\UserCourses;
use JWTAuth;
class CourseController extends Controller
{
    /**
     * Вывести все курсы
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        JWTAuth::parseToken()->authenticate();

        $courses = Course::all();

        if ($courses){
            $response = ['success'=>true, 'data'=>$courses];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }
    public function create()
    {
        //
    }

    /**
     * Добавить курс к юзеру
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->course_id;

        $savecourse=UserCourses::create(['user_id'=>$user->id,'course_id'=>$request->course_id]);

        if ($savecourse)
        {
            $response = ['success'=>true];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }

    /**
     * Открыть один курс
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user=JWTAuth::parseToken()->authenticate();
        $course = Course::where('id',$request->course_id)->first();
        
        foreach ($course->lessons as $item) {

            $item->likescount=count($item->likesCount);
            if($item->likesCount->contains($user->id)){
                $item['is_liked']='true';
            }
            else{
                $item['is_liked']='false';
            }
            unset($item->likesCount);
        }

        if ($course) {
            $response = ['success'=>true, 'data'=>$course];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }

    public function edit(Course $course)
    {
        //
    }
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $request->course_id;

        $savecourse=UserCourses::where(['user_id'=>$user->id,'course_id'=>$request->course_id])->delete();

        if ($savecourse)
        {
            $response = ['success'=>true];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }
    public function takenByUserCourses(){
        $user = JWTAuth::parseToken()->authenticate();

        $user=User::find($user->id)->courses()->get();

        if ($user)
        {
            $response = ['success'=>true,'data'=>$user];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }
}

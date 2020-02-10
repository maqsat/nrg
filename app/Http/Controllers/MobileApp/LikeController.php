<?php

namespace App\Http\Controllers\MobileApp;

use Egulias\EmailValidator\Exception\CommaInDomain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MobileApp\Like;
use App\Models\MobileApp\Lessons;
use Illuminate\Notifications\Notification;
use JWTAuth;
use App\Models\MobileApp\Appnotification;
use App\Models\MobileApp\NotificationObject;
use App\Models\MobileApp\NotificationChange;
use App\Models\MobileApp\Comment;

class LikeController extends Controller
{
    /**
     * Количество лайков коментрия и рекоммендаций
     */
    public function index(Request $request)
    {
        if($request->has('recommendation_id')){
            $likeobject_id=$request->recommendation_id;
        }
        else if($request->has('comment_id')){
            $likeobject_id=$request->comment_id;
        }

        $likes = Like::where('likeobject_id',$likeobject_id)->get();
        $count=$likes->count();

        if ($likes)
        {
            $response = ['success'=>true,'data'=>$count];
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
     * Ставит лайк юзера в рекоммендацию или коммент
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if($request->has('recommendation_id')){
            $likeobject='1';
            $likeobject_id=$request->recommendation_id;

            $is_there_like=Like::where('likeobject','=' ,$likeobject)
                ->where('likeobject_id','=',$likeobject_id)
                ->where('user_id','=',$user->id)->first();
        }
        else if($request->has('comment_id')){
            $likeobject='2';
            $likeobject_id=$request->comment_id;
            
            $is_there_like=Like::where('likeobject','=' ,$likeobject)
                ->where('likeobject_id','=',$likeobject_id)
                ->where('user_id','=',$user->id)->first();
        }
        else if($request->has('lesson_id')){
            $likeobject='3';
            $likeobject_id=$request->lesson_id;


            $is_there_like=Like::where('likeobject','=' ,$likeobject)
                ->where('likeobject_id','=',$likeobject_id)
                ->where('user_id','=',$user->id)->first();

        }
        if($is_there_like){
            Like::where([
                'likeobject'=>$likeobject,
                'likeobject_id'=>$likeobject_id,
                'user_id'=>$user->id
            ])->delete();
            if($request->has('comment_id')) {
                /*Удаляем уведомленеие если такой комментарий уже существует*/
                $likeobject = '2';
                $likeobject_id = $request->comment_id;
                $notifier_id = Comment::find($likeobject_id)->user->id;

                $notificationoject = NotificationObject::where([
                    'entity_type_id' => '1',
                    'entity_id' => $likeobject_id,
                    'actor_id' => $user->id,
                ])->first();
                Appnotification::where([
                    'notification_object_id' => $notificationoject->id,
                    'notifier_id' => $notifier_id
                ])->delete();
                $notificationoject->delete();
                /**/
            }

            $response = ['success'=>true, 'data'=>'Like deleted'];
        }
        else{
            $like = Like::create([
                'likeobject'=>$likeobject,
                'likeobject_id'=>$likeobject_id,
                'user_id'=>$user->id
            ]);

            if ($like)
            {
                //Если  это комментарий то создаем уведомление
                if($request->has('comment_id')) {
                    /*Создаем уведомление*/
                    $likeobject = '2';
                    $likeobject_id = $request->comment_id;
                    $notifier_id = Comment::find($likeobject_id)->user->id;

                    $notificationoject = NotificationObject::create([
                        'entity_type_id' => '1',
                        'entity_id' => $likeobject_id,
                        'actor_id' => $user->id,
                    ]);
                    Appnotification::create([
                            'notification_object_id' => $notificationoject->id,
                            'notifier_id' => $notifier_id]
                    );
                }
                $response = ['success'=>true];
            }
            else
                $response = ['success'=>false, 'data'=>'Record doesnt exists'];
        }

        return response()->json($response, 201);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
    public function allvideolikes(){
        $user = JWTAuth::parseToken()->authenticate();
        $likes = Like::where('likeobject','=','3')->where('user_id','=',$user->id)->get();

        $allikescount = Lessons::all()->count();

        foreach ($likes as $like){
            $like->videos;
            $like["course_photo"]=$like->videos->course["preview"];
            unset($like->videos->course);
        }

        if ($likes) {
            $response = ['success'=>true, 'all_video_count'=>$allikescount,'data'=>$likes];
        }
        else
            $response = ['success'=>false, 'data'=>'Record doesnt exists'];

        return response()->json($response, 201);
    }
}

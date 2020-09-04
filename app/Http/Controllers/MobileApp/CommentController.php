<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MobileApp\NotificationObject;
use App\Models\MobileApp\NotificationChange;
use App\Models\MobileApp\Appnotification;
use App\Models\MobileApp\Recommendation;
use App\Models\MobileApp\Comment;
use App\Models\MobileApp\Lessons;
use JWTAuth;


class CommentController extends Controller
{
    public function index()
    {
        //
    }
    public function create()
    {
        //
    }

    /**
     * Сохранить комментарий юзера
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $comment=Comment::create([
            "text"=>$request->text,
            "user_id"=>$user->id ,
            'recommendation_id'=>$request->recommendation_id,
            'type'=>$request->type
        ]);
        if ($comment)
        {
            $response = ['success'=>true];
        }
        else
            $response = ['success'=>false];

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
    //Оставит ответ на комментарий
    public function reply(Request $request){
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }
        $comment=Comment::create([
            "text"=>$request->text,
            "user_id"=>$user->id ,
            'parent_id'=>$request->comment_id,
            'recommendation_id'=>$request->recommendation_id,
            'type'=>$request->type
        ]);

        $notifier_id=Comment::find($request->comment_id)->user->id;
        $notificationoject=NotificationObject::create([
            'entity_type_id'=>'2',
            'entity_id'=>$request->comment_id,
            'actor_id'=>$user->id,
            'actor_entity_id'=>$comment->id
        ]);
        Appnotification::create([
            'notification_object_id'=>$notificationoject->id,
            'notifier_id'=>$notifier_id]
        );
        if ($comment)
        {
            $response = ['success'=>true];
        }
        else {
            $response = ['success' => false];
        }

        return response()->json($response, 201);
    }

    public function getallComments(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        //Получить комментарий рекоммендаций
        if($request->type==1) {
            $recommendation = Recommendation::with(['comments.user','comments.replies.user'])
                ->select('id')
                ->where('id', $request->recommendation_id)
                ->first();

            foreach ($recommendation->comments as $comment) {

                $comment->loadRecursiveReplies();
                $comment['comment_likes'] = $comment->CommentLikesCount->count();


                if ($comment->CommentLikesCount->contains($user->id)) {
                    $comment['is_liked'] = 'true';
                } else {
                    $comment['is_liked'] = 'false';
                }

            }

            if ($recommendation) {
                $response = ['success' => true, 'data' => $recommendation];
            } else {
                $response = ['success' => false, 'data' => 'Record doesnt exists'];
            }

        }
        //Получить комментарий видео
        if($request->type==2) {

            $lessons = Lessons::with([
                'comments.user',
                'comments.replies.user'
            ])->select('id')->where('id', $request->recommendation_id)->first();

            foreach ($lessons->comments as $comment) {

                $comment->loadRecursiveReplies();
                $comment['comment_likes'] = $comment->CommentLikesCount->count();
                $comment['test'] = "klj";

                if ($comment->CommentLikesCount->contains($user->id)) {
                    $comment['is_liked'] = 'true';
                } else {
                    $comment['is_liked'] = 'false';
                }

            }

            if ($lessons) {
                $response = ['success' => true, 'data' => $lessons];
            } else {
                $response = ['success' => false, 'data' => 'Record doesnt exists'];
            }


        }
        return response()->json($response);
    }
}

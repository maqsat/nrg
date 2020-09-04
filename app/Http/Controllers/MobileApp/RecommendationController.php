<?php

namespace App\Http\Controllers\MobileApp;

use App\Models\MobileApp\Recommendation;
use App\Models\MobileApp\Course;
use App\Models\MobileApp\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Http\Resources\RecommendationResource;

class RecommendationController extends Controller
{
    /**
     * Все рекоммендации
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=JWTAuth::parseToken()->authenticate();
        $recommendations = Recommendation::orderBy('created_at', 'desc')->get();

        foreach ($recommendations as $item) {
           // return new RecommendationResource($item,$user);
            $item['recommendation_likes']=$item->likesCount->count();
            //файл курса
            $item['course_file']=$item->course["preview"];
            $item['course_title']=$item->course["title"];
            unset($item["course"]);
            //Осы юзерда осы реккомендация заметкада барма
            if($item->user_bookmarks->contains($user->id)){
                $item['is_bookmarked']='true';
            }
            else{
                $item['is_bookmarked']='false';
            }


            //Осы юзерда осы реккомендация заметкада барма
            if($item->likesCount->contains($user->id)){
                $item['is_liked']='true';
            }
            else{
                $item['is_liked']='false';
            }
            unset($item->likesCount);

            //лишнийды алып тастадым
            unset($item['user_bookmarks']);
            
            /*foreach ($item->comments as $comment) {
                $comment->loadRecursiveReplies();
            }*/
        }

        if (!$recommendations->isEmpty()) {
            $response = ['success' => true, 'data' => $recommendations->toArray()];
        } else {
            $response = ['success' => false, 'data' => 'Record doesnt exists'];
        }

        return response()->json($response, 201);
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show(Recommendation $recommendation)
    {
        //
    }
    public function edit(Recommendation $recommendation)
    {
        //
    }
    public function update(Request $request, Recommendation $recommendation)
    {
        //
    }
    public function destroy(Recommendation $recommendation)
    {
        //
    }
}

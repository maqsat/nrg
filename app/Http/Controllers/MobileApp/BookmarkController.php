<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Support\Facades\Validator;
use App\Models\MobileApp\Recommendation;
use App\Http\Controllers\Controller;
use App\Models\MobileApp\Bookmark;
use App\Models\MobileApp\Course;
use Illuminate\Http\Request;
use JWTAuth;
use App\Http\Resources\BookmarkResource;
use App\User;

class BookmarkController extends Controller
{
    //Все закладки юзера
    public function index(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $user_bookmarked = $user->recommendation_bookmarks;

        if ($user_bookmarked->count()>0) {
            return BookmarkResource::collection($user_bookmarked);
        }
        else {
            $response = ['success' => false, 'data' => 'Record doesnt exists'];
        }
        return response()->json($response, 201);
    }
    public function create()
    {
        //
    }
    /**
     * Сохранить закладку юзеру
     */
    public function store(Request $request)
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $validator = Validator::make($request->all(), [
            'recommendation_id' => 'required',
        ],[
            'recommendation_id.required'=>'Отправьте айди рекомендаций'
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['error'] = $error[0];
            $result['error_code'] = 500;
            $result['status'] = false;
            return response()->json($result);
        }

        $is_there_bookmark=Bookmark::where('user_id', '=', $user->id)
            ->where('recommendation_id','=', $request->recommendation_id)->first();

        if($is_there_bookmark){
            $response = [
                'success'=>true ,
                'message'=>'bookmark_deleted'
            ];
            Bookmark::where('user_id', '=', $user->id)
                ->where('recommendation_id','=', $request->recommendation_id)->delete();
        }
        else{
            $bookmark=Bookmark::create([
                "user_id"=>$user->id ,
                'recommendation_id'=>$request->recommendation_id
            ]);
            if ($bookmark) {
                $response = ['success'=>true];
            }
            else
                $response = ['success'=>false];
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

    /**
     * Удалить закладку юзера
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'bookmark_id' => 'required',
        ]);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['error'] = $error[0];
            $result['error_code'] = 500;
            $result['status'] = false;
            return response()->json($result);
        }

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }


        $bookmark=Bookmark::where(["user_id"=>$user->id , 'id'=>$request->bookmark_id])->delete();
        if ($bookmark)
        {
            $response = ['success'=>true];
        }
        else
            $response = ['success'=>false];

        return response()->json($response, 201);
    }
}

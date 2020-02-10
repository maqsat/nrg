<?php

namespace App\Http\Controllers\MobileApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MobileApp\Appnotification;
use Illuminate\Support\Facades\App;
use JWTAuth;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index(){

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

        $notifications=Appnotification::with('user')->where('notifier_id',$user->id)->get();



            return NotificationResource::collection($notifications);
        /*

        foreach ($notifications as $item) {
            $item->notificationObject->object;
            $item->notificationObject->actorobject;
            $item->notificationObject->user;
            if($item->notificationObject->object["type"]==1){
                $item["recommendation_file"]=$item->notificationObject->object->recommendation["photo"];
            }
            else{
                $item["recommendation_file"]=$item->notificationObject->object->lessons["photo"];
            }

            unset($item->notificationObject->object->recommendation);
        }

        if ($notifications)
        {
            $response = ['success'=>true,'data'=>$notifications];
        }
        else
            $response = ['success'=>false];

        return response()->json($response, 201);*/
    }
}

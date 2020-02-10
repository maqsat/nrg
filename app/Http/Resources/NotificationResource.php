<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "notification_object_id"=> $this->notification_object_id,
            "notifier_id"=> $this->notifier_id,
            //"is_read"=> $this->is_read,
            "recommendation_file"=> ($this->notificationObject->object["type"]==1)?$this->notificationObject->object->recommendation["photo"]:$this->notificationObject->object->lessons["photo"],
            "user" =>[
                "id"=> $this->user["id"],
                "nickname"=> $this->user["nickname"],
                "name"=> $this->user["name"],
            ],
            "notification_object"=> [
                "id"=> $this->notificationObject['id'],
                "entity_type_id"=> $this->notificationObject['entity_type_id'],
                "entity_id"=> $this->notificationObject['entity_id'],
                "actor_id"=> $this->notificationObject['actor_id'],
                "actor_entity_id"=> $this->notificationObject['actor_entity_id'],
                "type"=> [
                    "id"=> $this->notificationObject->type["id"],
                    "action"=> $this->notificationObject->type["action"],
                ],
                "object"=> [
                    "id"=> $this->notificationObject->object["id"],
                    "text"=> $this->notificationObject->object["text"],
                    "user_id"=> $this->notificationObject->object["user_id"],
                    "recommendation_id"=> $this->notificationObject->object["recommendation_id"],
                    "type"=> $this->notificationObject->object["type"],
                    "parent_id"=> $this->notificationObject->object["parent_id"],
                    "user"=> [
                        "id"=> $this->notificationObject->object->user["id"],
                        "name"=> $this->notificationObject->object->user["name"]
                    ],
                    "lessons"=> $this->notificationObject->object["lessons"],
                ],
                "actorobject"=> $this->notificationObject['actorobject'],
                "user"=>[
                    "id"=> $this->notificationObject->user["id"],
                    "nickname"=> $this->notificationObject->user["nickname"],
                    "name"=> $this->notificationObject->user["name"],
                    "photo"=> $this->notificationObject->user["photo"]
                ]
            ]
        ];
    }
}

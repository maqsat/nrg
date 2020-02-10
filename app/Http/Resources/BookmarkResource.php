<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class BookmarkResource extends JsonResource
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
            'recommendation_id' => $this->id,
            'recommendation_title' => $this->title,
            'course_id' => $this->course_id,
            'recommendation_description' => $this->description,
            'recommendation_file' => $this->file,
            'likes_count' => $this->likesCount->count(),
            'course_file' => $this->course2["preview"],
            'course_title' => $this->course2["title"],
            'is_bookmarked' => "true",
            'type' => $this->type,
            'is_liked' => ($this->likesCount->contains(Auth::user()->id))?'true':'false',
            'recommendation_photo' => $this->photo,
        ];
    }
}

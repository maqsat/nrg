<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Lessons extends Model
{
    protected $table = 'lessons';
    protected $fillable = ['course_id','path','title','photo'];

    public function course()
    {
        return $this->belongsTo('App\Models\MobileApp\Course',"course_id");
    }

    public function likesCount()
    {
        return $this->belongsToMany('App\User','likes' ,'likeobject_id','user_id')->withPivot('likeobject')->where('likeobject', '=', 3);
    }
    public function comments()
    {
        return $this->hasMany('App\Models\MobileApp\Comment' , 'recommendation_id')->where('parent_id', '=', Null);
    }

    public function getPathAttribute($value){
        return 'https://en-rise.com/' .$value;
    }
    public function getPhotoAttribute($value){
        return 'https://en-rise.com/' .$value;
    }
}

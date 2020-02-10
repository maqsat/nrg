<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $table = 'recommendations';
    protected $fillable = ['course_id','title','description','file','type','photo'];

    public function course()
    {
        return $this->hasOne('App\Models\MobileApp\Course' ,'id');
    }

    //Fixed
    public function course2()
    {
        return $this->belongsTo('App\Models\MobileApp\Course' ,'course_id');
    }
    //End Fixed

    public function getFileAttribute($value) {
        return 'https://en-rise.com/' . $value;
    }
    public function getPhotoAttribute($value) {
        return 'https://en-rise.com/' . $value;
    }

    public function comments()
    {
        return $this->hasMany('App\Models\MobileApp\Comment')->where('parent_id', '=', Null);
    }

    public function user_bookmarks()
    {
        return $this->belongsToMany('App\User','bookmark');
    }
    public function likesCount()
    {
        return $this->belongsToMany('App\User','likes' ,'likeobject_id','user_id')->withPivot('likeobject')->where('likeobject', '=', 1);
    }
    //Fixed
    public function recommendation_bookmarks(){
        return $this->belongsToMany('App\User', 'bookmark', 'recommendation_id', 'user_id');
    }
}

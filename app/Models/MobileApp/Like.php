<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'likes';
    protected $fillable = ['user_id','likeobject_id','likeobject'];

    public  function videos(){
        return $this->belongsTo('App\Models\MobileApp\Lessons','likeobject_id');
    }
}

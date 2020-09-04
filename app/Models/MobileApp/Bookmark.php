<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmark';
    protected $fillable = ['user_id','recommendation_id'];

    public  function recommendations(){
        return $this->hasMany('App\Models\MobileApp\Recommendation','recommendation_id');
    }



}

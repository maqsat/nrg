<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class NotificationObject extends Model
{
    protected $with = ['type:id,action'];
    protected $table = 'notification_object';
    protected $hidden=['created_at','updated_at'];
    protected $fillable = ['entity_type_id','entity_id','actor_id','actor_entity_id'];

    /*public function notification()
    {
        return $this->hasMany('App\Models\MobileApp\Appnotification');
    }*/
    public function type()
    {
        return $this->belongsTo('App\Models\MobileApp\EntityType','entity_type_id')->select('id','action');
    }
    public function object()
    {
        return $this->belongsTo('App\Models\MobileApp\Comment','entity_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','actor_id')->select('id','nickname','name','photo');
    }
    public function actorobject()
    {
        return $this->belongsTo('App\Models\MobileApp\Comment','actor_entity_id');
    }

}

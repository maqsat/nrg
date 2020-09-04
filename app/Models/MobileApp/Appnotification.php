<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Appnotification extends Model
{
    protected $table = 'appnotification';
    protected $fillable = ['notification_object_id','notifier_id','is_read'];
    protected $hidden=['created_at','updated_at'];

    public function notificationObject()
    {
        return $this->belongsTo('App\Models\MobileApp\NotificationObject' , 'notification_object_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','notifier_id')->select('id','nickname','name');
    }
    
}

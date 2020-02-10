<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class UserView extends Model
{
    protected $table = 'userview';
    protected $fillable = ['user_id','lesson_id','course_id'];
}

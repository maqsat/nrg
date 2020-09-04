<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class UserCourses extends Model
{
    protected $table = 'usercourse';
    protected $fillable = ['user_id',	'course_id'];
}

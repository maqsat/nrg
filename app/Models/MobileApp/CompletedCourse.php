<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class CompletedCourse extends Model
{

    protected $table = 'completed_courses';
    protected $fillable = ['user_id','course_id'];




}

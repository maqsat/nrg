<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';
    protected $fillable = ['title','description','preview','lessons_quantity','total_time'];

    public  function lessons(){
        return $this->hasMany('App\Models\MobileApp\Lessons','course_id');
    }

    public function recommendation()
    {
        return $this->belongsTo('App\Models\MobileApp\Course\Recommendation');
    }
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function usercompletedcourses()
    {
        return $this->belongsToMany('App\Models\MobileApp\Course','completed_courses' ,'course_id','user_id');
    }
    public function getPreviewAttribute($value){
        return 'https://en-rise.com/' .$value;
    }
}

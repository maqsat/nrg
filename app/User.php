<?php

namespace App;

use App\Models\Review;
use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    /**
     *
     * 1 - Администратор
     * 2 - Бухгалтер
     * 3 - Менеджер
     * 4 - Руководитель
     *
     */

    /**
     *
     * 6 - позиция изменена
     * 5 - наставник изменен
     * 4 - спонсор изменен
     *
     */


    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function courses()
    {
        return $this->belongsToMany('App\Models\MobileApp\Course');
    }
    public function notifications()
    {
        return $this->hasMany('App\Models\MobileApp\Appnotification');
    }
    public function notificationchanges()
    {
        return $this->hasMany('App\Models\MobileApp\NotificationChange');
    }

    public function recommendations()
    {
        return $this->belongsToMany('App\Models\MobileApp\Recommendation','bookmark');
    }
    //Получить количество лайков
    public function likes()
    {
        return $this->belongsToMany('App\Models\MobileApp\Recommendation','likes' ,'user_id','likeobject_id')->withPivot('likeobject')->where('likeobject', '=', 1);
    }
    public function CommentLikes()
    {
        return $this->belongsToMany('App\Models\MobileApp\Comment','likes' ,'user_id','likeobject_id')->withPivot('likeobject')->where('likeobject', '=', 2);
    }
    public function LessonLikes()
    {
        return $this->belongsToMany('App\Models\MobileApp\Lesson','likes' ,'user_id','likeobject_id')->withPivot('likeobject')->where('likeobject', '=', 3);
    }
    public function completedcourses()
    {
        return $this->belongsToMany('App\Models\MobileApp\Course','completed_courses' ,'user_id','course_id');
    }

    public function recommendation_bookmarks(){
        return $this->belongsToMany('App\Models\MobileApp\Recommendation', 'bookmark', 'user_id', 'recommendation_id');
    }

    public function package()
    {
        return $this->belongsTo('App\Models\Package','package_id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Status','status_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'user_id');
    }

    public function review_likes()
    {
        return $this->belongsToMany(Review::class, 'user_like_review', 'user_id', 'review_id');
    }

    public function comment_likes()
    {
        return $this->belongsToMany(Comment::class, 'user_like_comment', 'user_id', 'comment_id');
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value;
    }

}

<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;
use App\Models\MobileApp\Like;

class Comment extends Model
{
    protected $with = ['user:id,name'];
    protected $table = 'comments';
    protected $fillable = ['text','user_id','recommendation_id','parent_id','type'];
    protected $hidden = ['created_at','updated_at'];

    public function isRepliesLoaded() {
        return isset($this->relations['replies']);
    }
    public function loadReplies() {
        $this->load('replies')["comment_likes"]=$this->CommentLikesCount->count();

        if ($this->CommentLikesCount->contains($this->user->id)) {
            $this->load('replies')["is_liked"]='true';
        } else {
            $this->load('replies')["is_liked"]='false';
        }

        return $this->load('replies.user');
    }
    public function loadRecursiveReplies()
    {
        if (!$this->isRepliesLoaded()) {
            $this->loadReplies();
        }
        if ($this->replies->isEmpty()) {
            return;
        }
        foreach ($this->replies as $reply) {
            $reply->loadRecursiveReplies();
        }
    }
    public function recommendation()
    {
        return $this->belongsTo('App\Models\MobileApp\Recommendation' , 'recommendation_id');
    }
    public function lessons()
    {
        return $this->belongsTo('App\Models\MobileApp\Lessons' , 'recommendation_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User')->select('id','name','nickname','photo');
    }
    public function replies()
    {
        return $this->hasMany('App\Models\MobileApp\Comment' ,'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\MobileApp\Comment' ,'id');
    }
    public function CommentLikesCount()
    {
        return $this->belongsToMany('App\User','likes' ,'likeobject_id','user_id')->withPivot('likeobject')->where('likeobject', '=', 2);
    }
}

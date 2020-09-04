<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class,'review_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class,'comment_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'comment_id');
    }

    public function user_likes()
    {
        return $this->belongsToMany(User::class, 'user_like_comment', 'comment_id', 'user_id');
    }
}

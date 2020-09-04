<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'review_id');
    }

    public function user_likes()
    {
        return $this->belongsToMany(User::class, 'user_like_review', 'review_id', 'user_id');
    }
}

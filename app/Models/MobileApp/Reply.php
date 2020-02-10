<?php

namespace App\Models\MobileApp;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'replies';
    protected $fillable = ['comment_id','user_id','text'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table='news';
    protected $fillable = [
        'news_name','news_text','news_date','news_image','news_desc'
    ];
}

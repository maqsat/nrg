<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'id','title','description','cost','partner_cost','category_id','sale','image1','image2','image3', 'cv','qv',
        'is_client','client_phone'
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag' , 'product_tag' ,'tag_id' ,'product_id');
    }
}

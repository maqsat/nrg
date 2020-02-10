<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    protected $fillable = [
        'id','user_id','status'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product' ,'basket_good','basket_id','good_id');
    }
    public function basket_goods()
    {
        return $this->hasMany('App\Models\BasketGood' , 'basket_id');
    }

}

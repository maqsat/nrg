<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasketGood extends Model
{
    protected $table='basket_good';
    protected $fillable = [
        'basket_id','good_id','quantity','client_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','good_id');
    }
    public function clients()
    {
        return $this->belongsTo('App\Models\Client','client_id');
    }
}

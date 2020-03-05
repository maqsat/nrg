<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';


    public function statusName()
    {
        return $this->hasOne('App\Models\Status','id','rank');
    }
}

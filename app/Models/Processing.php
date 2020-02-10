<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Processing extends Model
{
    protected $table = 'processing';

    protected $fillable = [
        'status','sum','in_user','user_id','program_id','created_at','status_id','package_id','card_number'
    ];
}

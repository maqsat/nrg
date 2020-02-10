<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserClients extends Model
{
    protected $table = 'user_clients';
    protected $fillable = ['user_id' , 'client_id' , 'order_id','is_complete'];
}

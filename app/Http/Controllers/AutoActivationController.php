<?php

namespace App\Http\Controllers;

use App\Models\Processing;
use DB;
use App\User;
use App\Events\Activation;
use Illuminate\Http\Request;

class AutoActivationController extends Controller
{
    public function bot_activation()
    {
        DB::table('counters')->truncate();
        DB::table('notifications')->truncate();
        DB::table('not_cash_bonuses')->truncate();
        DB::table('user_programs')->truncate();
        Processing::where('status','!=','out')
            ->where('status','!=','quickstart_bonus')
            ->where('status','!=','cashback')
            ->where('status','!=','revitalization')
            ->where('status','!=','shop')
            ->delete();

        DB::insert('INSERT INTO `user_programs` (`id`, `user_id`, `list`, `inviter_list`, `status_id`, `program_id`, `package_id`, `created_at`, `updated_at`) VALUES (NULL, \'1\', \',\', \'\', \'4\', \'1\', \'4\', \'2020-03-11 00:00:00\', \'2020-03-11 00:00:00\');');
        DB::update('update users set status = 0 where id != 1');

        $users = User::where('id','!=',1)->get();

        $counter = 0;
        foreach ($users as $key => $item){
            $counter = $key;
            $user = User::find($item->id);
            event(new Activation($user = $user));
        }

        echo $counter;
    }

    public function checkMentor()
    {
        $program_id = 10;

        $users = User::where('program_id',$program_id)->get();

        foreach ($users as $key => $item){
            $counter = $key;
            $user = User::find($item->id);

            if(is_null(User::find($user->sponsor)))   echo "$user->id"."<br>";
        }
    }
}

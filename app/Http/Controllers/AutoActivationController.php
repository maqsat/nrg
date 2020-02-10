<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Events\Activation;
use Illuminate\Http\Request;

class AutoActivationController extends Controller
{
    public function bot_activation()
    {
        dd(0);
        $program_id = 11;

        $users = User::where('program_id',$program_id)->get();
        $counter = 0;

        DB::delete('DELETE FROM processing WHERE program_id = ?',[$program_id]);
        DB::delete('DELETE FROM user_programs WHERE program_id = ? and user_id != ?;',[$program_id,$program_id]);
        DB::delete('DELETE FROM user_subscribers WHERE program_id = ?',[$program_id]);
        DB::update('UPDATE users SET status = 0 WHERE program_id = ? and id != ?', [$program_id,$program_id]);
        DB::update('UPDATE user_programs SET step = 1 WHERE user_id = ?', [$program_id]);
        DB::update('UPDATE user_programs SET is_done = 0 WHERE user_id = ?', [$program_id]);

        foreach ($users as $key => $item){
            $counter = $key;
            $user = User::find($item->id);

            if($user->status != 1)  event(new Activation($user = $user));
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

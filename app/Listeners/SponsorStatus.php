<?php

namespace App\Listeners;

use App\User;
use App\Models\Status;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Events\Activation;
use App\Models\UserProgram;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SponsorStatus
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Activation  $event
     * @return void
     */
    public function handle(Activation $event)
    {
        /*$user_program = UserProgram::whereUserId($event->user->id)->first();
        $user_program_list = explode(',', $user_program->list);

        foreach ($user_program_list as $item){
            if($item != ''){

                $sponsor_user_program = UserProgram::whereUserId($item)->first();

                if($sponsor_user_program->status_id == 1){
                    $subscribers_count = User::whereSponsorId($item)->whereStatus(1)->count();

                    if($subscribers_count == 2){
                        Hierarchy::moveNextStatus($item,1);
                    }
                }
                else{
                    if($sponsor_user_program->status_id > 11 && Balance::getIncomeBalance($item) >= Status::find($sponsor_user_program->status_id)->start_sum){

                        Hierarchy::moveNextStatus($item,$sponsor_user_program->status_id+1);
                        $program_sum = Program::find($event->user->program_id)->in_sum*0.2;
                        Balance::changeBalance($item,$program_sum,'percentage',$event->user->id,$event->user->program_id);
                    }

                }


            }
        }*/
        

    }
}

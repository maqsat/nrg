<?php

namespace App\Listeners;

use App\Models\Processing;
use DB;
use Auth;
use App\User;
use App\Models\Order;
use App\Models\Counter;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use App\Models\Notification;
use App\Models\Program;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Events\Activation;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivated
{
    /**
     * Create the event listener.
     *
     */

    public $dollar = 385;

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

        /*start init and activate*/
        $id = $event->user->id;
        $inviter = User::find($event->user->inviter_id);
        $sponsor = User::find($event->user->sponsor_id);
        $program = Program::find($event->user->program_id);

        if ($event->user->package_id == 0){
            $package_id = 0;
            $status_id = 1;
            $package_cost = env('REGISTRATION_FEE');
        }
        else{
            $package = Package::find($event->user->package_id);
            $package_id = $package->id;
            $status_id = $package->rank;
            $package_cost = $package->cost + env('REGISTRATION_FEE');
        }

        if(!is_null($event->user->status_id) && $event->user->status_id != 0){
            $status_id = $event->user->status_id;
        }

        $list = Hierarchy::getSponsorsList($event->user->id,'').',';
        $inviter_list = Hierarchy::getInviterList($event->user->id,'').',';

        User::whereId($event->user->id)->update(['status' => 1]);



        /*set register sum */
        Balance::changeBalance(0,$package_cost,'register',$event->user->id,$event->user->program_id,$package_id,0);

        UserProgram::insert(
            [
                'user_id' => $event->user->id,
                'list' => $list,
                'status_id' => $status_id,
                'inviter_list' => $inviter_list,
                'program_id' => $event->user->program_id,
                'package_id' => $package_id,
            ]
        );

        Notification::create([
            'user_id' => $event->user->id,
            'type' => 'user_activated',
            'author_id' => Auth::user()->id
        ]);

        /*end init and activate*/


        if($package_id != 0){
            $sponsors_list = explode(',',trim($list,','));

            foreach ($sponsors_list as $key => $item){

                $item_user_program = UserProgram::where('user_id',$item)->first();

                if($item_user_program->package_id != 0){
                    $item_status = Status::find($item_user_program->status_id);

                    /*set own pv and change status*/
                    $position = 0;

                    if((count($sponsors_list) == 1 && $item == 1) || $key == 0){
                        $position = $event->user->position;
                    }
                    elseif(count($sponsors_list) == 2 && $item == 1){
                        $position = User::where('id',$sponsors_list[0])->first()->position;
                    }else{

                        $current_user_first = User::where('id',$sponsors_list[$key-1])->where('position',1)->first();
                        $current_user_second =  User::where('id',$sponsors_list[$key-1])->where('position',2)->first();

                        if(!is_null($current_user_first) && strpos($list, ','.$current_user_first->id.',') !== false) $position = 1;
                        if(!is_null($current_user_second) && strpos($list, ','.$current_user_second->id.',') !== false) $position = 2;
                    }

                    Balance::setQV($item,$package->pv,$id,$package->id,$position);
                    //start check small branch definition
                    $left_pv = Hierarchy::pvCounter($item,1);
                    $right_pv = Hierarchy::pvCounter($item,2);
                    if($left_pv > $right_pv) $small_branch_position = 2;
                    elseif($left_pv == $right_pv) $small_branch_position = 0;
                    else $small_branch_position = 1;
                    //end check small branch definition


                    //start check next status conditions and move
                    $left_user = User::whereSponsorId($item)->wherePosition(1)->whereStatus(1)->first();
                    $right_user = User::whereSponsorId($item)->wherePosition(2)->whereStatus(1)->first();
                    $pv = Hierarchy::pvCounterAll($item);
                    $next_status = Status::find($item_status->order+1);

                    if(!is_null($left_user) && !is_null($right_user)){

                        if(!is_null($next_status)){
                            if($next_status->pv <= $pv){

                                if(!$next_status->personal){
                                    $left_user_count = UserProgram::where('list','like','%,'.$left_user->id.','.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    $left_user_status = UserProgram::where('user_id',$left_user->id)->first();
                                    if($left_user_status->status_id >= $item_status->id){
                                        $left_user_count++;
                                    }
                                    $right_user_count = UserProgram::where('list','like','%,'.$right_user->id.','.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    $right_user_status = UserProgram::where('user_id',$left_user->id)->first();
                                    if($right_user_status->status_id >= $item_status->id){
                                        $right_user_count++;
                                    }
                                }
                                else{
                                    $left_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                                            ->where('list','like','%,'.$left_user->id.','.$item.',%')
                                            ->where('users.inviter_id',$item)
                                            ->count() + 1;

                                    $right_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                                            ->where('list','like','%,'.$right_user->id.','.$item.',%')
                                            ->where('users.inviter_id',$item)
                                            ->count() + 1;
                                }

                                $all_count = $left_user_count+$right_user_count;

                                if($all_count  >= $next_status->condition){

                                    Hierarchy::moveNextStatus($item,$next_status->id,$item_user_program->program_id);
                                    $item_user_program = UserProgram::where('user_id',$item)->first();
                                    $item_status = Status::find($item_user_program->status_id);

                                    Notification::create([
                                        'user_id'   => $item,
                                        'type'      => 'move_status',
                                        'status_id' => $item_user_program->status_id
                                    ]);

                                    Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$id,$program->id,$package->id,$item_status->id);

                                    if ($next_status->travel_bonus){
                                        DB::table('not_cash_bonuses')->insert([
                                            'user_id' => $item,
                                            'type' => 'travel_bonus',
                                            'status_id' => $next_status->id,
                                            'status' => 0,
                                        ]);
                                    }

                                    if ($next_status->status_no_cash_bonus){
                                        DB::table('not_cash_bonuses')->insert([
                                            'user_id' => $item,
                                            'type' => 'status_no_cash_bonus',
                                            'status_id' => $next_status->id,
                                            'status' => 0,
                                        ]);
                                    }

                                }
                            }
                        }
                    }
                    //end check next status conditions and move


                    /*start set  turnover_bonus  */
                    $credited_pv = Processing::where('status','turnover_bonus')->where('user_id',$item)->sum('pv');

                    if($small_branch_position != 0){

                        if($small_branch_position == 1){
                            $to_enrollment_pv = $left_pv - $credited_pv;
                        }
                        else
                            $to_enrollment_pv = $right_pv - $credited_pv;

                        $sum = $to_enrollment_pv*$item_status->turnover_bonus/100*env('COURSE');
                        Balance::changeBalance($item,$sum,'turnover_bonus',$id,$program->id,$package->id,$item_status->id,$to_enrollment_pv);

                        /*start set  matching_bonus  */
                        $inviter_list = Hierarchy::getInviterList($item,'').',';
                        $inviter_list = explode(',',trim($inviter_list,','));
                        $inviter_list = array_slice($inviter_list, 0, 3);

                        foreach ($inviter_list as $inviter_key => $inviter_item){
                            if($inviter_item != ''){
                                $inviter_user_program = UserProgram::where('user_id',$inviter_item)->first();
                                $list_inviter_status = Status::find($inviter_user_program->status_id);
                                if($list_inviter_status->depth_line <= $inviter_key+1){
                                    Balance::changeBalance($inviter_item,$sum*$list_inviter_status->matching_bonus/100,'matching_bonus',$id,$program->id,$package->id,$list_inviter_status->id);
                                }
                            }
                        }
                        /*end  set  matching_bonus  */
                    }
                    /*end set  turnover_bonus  */
                }
            }

            /*start set  invite_bonus  */
            $inviter_program = UserProgram::where('user_id',$event->user->inviter_id)->first();
            $inviter_status = Status::find($inviter_program->status_id);
            Balance::changeBalance($inviter->id,$package->pv*$inviter_status->invite_bonus/100*env('COURSE'),'invite_bonus',$id,$program->id,$package->id,$inviter_status->id);
            /*end set  invite_bonus  */
        }

    }
}

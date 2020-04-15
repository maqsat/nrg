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
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Events\Upgrade;

class UserUpgraded
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Upgrade  $event
     * @return void
     */
    public function handle(Upgrade $event)
    {
        $id = $event->order->user_id;
        $this_user = UserProgram::whereUserId($id)->first();
        $current_user = User::find($id);
        $program = Program::find($this_user->program_id);
        $inviter = User::find($current_user->inviter_id);
        $new_package = Package::find($event->order->package_id);
        $old_package = Package::find($this_user->package_id);
        $package_cost = $event->order->amount;
        $upgrade_pv = $new_package->pv - $old_package->pv;

        Balance::changeBalance($id,$package_cost,'upgrade',$id,$program->id,$new_package->id,0);

        User::whereId($id)->update(['package_id' => $new_package->id]);
        UserProgram::whereUserId($id)->update(['package_id' => $new_package->id]);

        Notification::create([
            'user_id' => $id,
            'type' => 'user_upgraded',
            'author_id' => Auth::user()->id
        ]);

        $list = $this_user->list;
        /*end init and activate*/
        $sponsors_list = explode(',',trim($list,','));

        foreach ($sponsors_list as $key => $item){

            $item_user_program = UserProgram::where('user_id',$item)->first();

            if(!is_null($item_user_program) && $item_user_program->package_id != 0){

                $item_status = Status::find($item_user_program->status_id);

                /*set own pv and change status*/

                $counter = Counter::where('user_id',$item)->where('inner_user_id',$id)->first();
                if(!is_null($counter)){
                    $position = $counter->position;

                    Balance::setQV($item,$upgrade_pv,$id,$new_package->id,$position,$item_status->id);
                    //start check small branch definition
                    $left_user = User::whereSponsorId($item)->wherePosition(1)->whereStatus(1)->first();
                    $right_user = User::whereSponsorId($item)->wherePosition(2)->whereStatus(1)->first();
                    $left_pv = Hierarchy::pvCounter($item,1);
                    $right_pv = Hierarchy::pvCounter($item,2);
                    if($left_pv > $right_pv) $small_branch_position = 2;
                    else $small_branch_position = 1;
                    //end check small branch definition

                    //start check next status conditions and move
                    $pv = Hierarchy::pvCounterAll($item);
                    if($item_user_program->package_id == 3) $pv =  $pv + 500;
                    if($item_user_program->package_id == 4) $pv =  $pv + 2500 + 500;

                    $next_status = Status::find($item_status->order+1);
                    $prev_statuses_pv = Status::where('order','<=',$next_status->order)->sum('pv');

                    if(!is_null($left_user) && !is_null($right_user)){

                        if(!is_null($next_status)){
                            if($prev_statuses_pv <= $pv){
                                $all_count = 0;
                                $left_user_count  = 0;
                                $right_user_count  = 0;
                                if(!$next_status->personal){
                                    $left_user_list = UserProgram::where('list','like','%,'.$left_user->id.','.$item.',%')->where('status_id','>=',$item_status->id)->get();

                                    $left_user_count = 0;
                                    foreach ($left_user_list as $left_user_item){
                                        $pos = strpos($left_user_item->inviter_list, ",$item,");
                                        if ($pos !== false)  {
                                            $left_user_count++;
                                        }
                                    }
                                    $left_user_status = UserProgram::where('user_id',$left_user->id)->where('inviter_list','like','%,'.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    if($left_user_status > 0){
                                        $left_user_count++;
                                    }


                                    $right_user_list = UserProgram::where('list','like','%,'.$right_user->id.','.$item.',%')->where('status_id','>=',$item_status->id)->get();

                                    $right_user_count = 0;
                                    foreach ($right_user_list as  $right_user_item){
                                        $pos = strpos($right_user_item->inviter_list, ",$item,");
                                        if ($pos !== false)  {
                                            $right_user_count++;
                                        }
                                    }
                                    $right_user_status = UserProgram::where('user_id',$right_user->id)->where('inviter_list','like','%,'.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    if($right_user_status > 0){
                                        $right_user_count++;
                                    }
                                }
                                else{
                                    $left_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                                        ->where('list','like','%,'.$left_user->id.','.$item.',%')
                                        ->where('users.inviter_id',$item)
                                        ->count();
                                    $left_user_status = UserProgram::where('user_id',$left_user->id)->where('inviter_list','like','%,'.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    if($left_user_status > 0){
                                        $left_user_count++;
                                    }

                                    $right_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                                        ->where('list','like','%,'.$right_user->id.','.$item.',%')
                                        ->where('users.inviter_id',$item)
                                        ->count();

                                    $right_user_status = UserProgram::where('user_id',$right_user->id)->where('inviter_list','like','%,'.$item.',%')->where('status_id','>=',$item_status->id)->count();
                                    if($right_user_status > 0){
                                        $right_user_count++;
                                    }
                                }

                                if($left_user_count == 0 or $right_user_count ==0){
                                    $all_count = 0;
                                }
                                else{
                                    $all_count = $left_user_count+$right_user_count;
                                }


                                if($all_count  >= $next_status->condition){

                                    Hierarchy::moveNextStatus($item,$next_status->id,$item_user_program->program_id);
                                    $item_user_program = UserProgram::where('user_id',$item)->first();
                                    $item_status = Status::find($item_user_program->status_id);

                                    Notification::create([
                                        'user_id'   => $item,
                                        'type'      => 'move_status',
                                        'status_id' => $item_user_program->status_id
                                    ]);

                                    if($item_user_program->package_id != 1){
                                        Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$id,$program->id,$item_user_program->package_id,$item_status->id);

                                        if ($next_status->status_no_cash_bonus){
                                            DB::table('not_cash_bonuses')->insert([
                                                'user_id' => $item,
                                                'type' => 'status_no_cash_bonus',
                                                'status_id' => $next_status->id,
                                                'status' => 0,
                                            ]);
                                        }

                                        if ($next_status->travel_bonus){
                                            DB::table('not_cash_bonuses')->insert([
                                                'user_id' => $item,
                                                'type' => 'travel_bonus',
                                                'status_id' => $next_status->id,
                                                'status' => 0,
                                            ]);
                                        }
                                    }


                                }
                            }
                        }
                    }
                    //end check next status conditions and move

                    $all_count = 0;
                    $left_user_count  = 0;
                    $right_user_count  = 0;
                    if(!is_null($left_user) && !is_null($right_user)){
                        $left_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                            ->where('list','like','%,'.$left_user->id.','.$item.',%')
                            ->where('users.inviter_id',$item)
                            ->count();
                        $left_user_status = UserProgram::where('user_id',$left_user->id)->where('inviter_list','like','%,'.$item.',%')->count();
                        if($left_user_status > 0){
                            $left_user_count++;
                        }

                        $right_user_count = UserProgram::join('users','user_programs.user_id','=','users.id')
                            ->where('list','like','%,'.$right_user->id.','.$item.',%')
                            ->where('users.inviter_id',$item)
                            ->count();
                        $right_user_status = UserProgram::where('user_id',$right_user->id)->where('inviter_list','like','%,'.$item.',%')->count();
                        if($right_user_status > 0){
                            $right_user_count++;
                        }

                        if($left_user_count == 0 or $right_user_count == 0){
                            $all_count = 0;
                        }
                        else{
                            $all_count = $left_user_count+$right_user_count;
                        }
                    }
                    else{
                        $all_count = 0;
                    }

                    if($all_count >= 2 && !is_null($next_status)){

                        /*start set  turnover_bonus  */
                        $credited_pv = Processing::where('status','turnover_bonus')->where('user_id',$item)->sum('pv');
                        $credited_sum = Processing::where('status','turnover_bonus')->where('user_id',$item)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');

                        if($small_branch_position == 1){
                            $to_enrollment_pv = $left_pv - $credited_pv;
                        }
                        else
                            $to_enrollment_pv = $right_pv - $credited_pv;

                        $sum = $to_enrollment_pv*$item_status->turnover_bonus/100*env('COURSE');

                        if(true){//$credited_sum < $item_status->week_sum_limit
                            $temp_sum = 0;
                            /*if($credited_sum + $sum >  $item_status->week_sum_limit){
                                $temp_sum = $item_status->week_sum_limit-$credited_sum;
                                $temp_sum = $sum - $temp_sum;
                                $sum = $sum - $temp_sum;
                            }*/
                            $sum = $to_enrollment_pv*$item_status->turnover_bonus/100*env('COURSE');//удалить
                            Balance::changeBalance($item,$sum,'turnover_bonus',$id,$program->id,$new_package->id,$item_status->id,$to_enrollment_pv,$temp_sum);


                            /*start set  matching_bonus  */
                            $inviter_list = $item_user_program->inviter_list;
                            $inviter_list = explode(',',trim($inviter_list,','));
                            $inviter_list = array_slice($inviter_list, 0, 3);

                            foreach ($inviter_list as $inviter_key => $inviter_item){
                                if($inviter_item != ''){
                                    $inviter_user_program = UserProgram::where('user_id',$inviter_item)->first();
                                    if(!is_null($inviter_user_program) && $inviter_user_program->package_id != 1){
                                        $list_inviter_status = Status::find($inviter_user_program->status_id);
                                        if($list_inviter_status->depth_line >= $inviter_key+1){
                                            Balance::changeBalance($inviter_item,$sum*$list_inviter_status->matching_bonus/100,'matching_bonus',$item_user_program->user_id,$program->id,$new_package->id,$list_inviter_status->id,$to_enrollment_pv,0,$inviter_key+1);
                                        }
                                    }
                                }
                            }
                            /*end  set  matching_bonus  */
                        }
                        else {
                            Balance::changeBalance($item,0,'turnover_bonus',$id,$program->id,$new_package->id,$item_status->id,$to_enrollment_pv,$sum);
                        }

                        /*end set  turnover_bonus  */
                    }
                }

            }
        }

        /*start set  invite_bonus  */
        $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
        if(!is_null($inviter_program) && $inviter_program->package_id != 0){
            $inviter_status = Status::find($inviter_program->status_id);
            Balance::changeBalance($inviter->id,$upgrade_pv*$inviter_status->invite_bonus/100*env('COURSE'),'invite_bonus',$id,$program->id,$new_package->id,$inviter_status->id,$new_package->pv);
        }
        /*end set  invite_bonus  */
    }
}

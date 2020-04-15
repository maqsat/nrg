<?php

namespace App\Listeners;

use DB;
use Auth;
use App\User;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use App\Models\Notification;
use App\Models\Program;
use App\Facades\Balance;
use App\Facades\Hierarchy;
use App\Events\Activation;
use Carbon\Carbon;
use App\Models\Processing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActivated
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

        $id = $event->user->id;
        $tag = 'program_id'.$event->user->program_id;
        $program =  Cache::remember($tag, 30, function() use ($event) {
            return  Program::find($event->user->program_id);
        });

        $tag = 'user_id'.$id;
        $this_user = Cache::remember($tag, 30, function() use ($id) {
            return  User::find($id);
        });

        /*start check*/
        if(is_null($this_user)) dd("Пользователь не найден");
        $check_user_program = UserProgram::where('program_id', $program->id)
            ->where('user_id',$id)
            ->count();
        if($check_user_program != 0) dd("Пользователь уже активирован -> $id");
        /*end check*/

        /*start init and activate*/
        $tag = 'user_id'.$event->user->inviter_id;
        $inviter = Cache::remember($tag, 30, function() use ($event) {
            return  User::find($event->user->inviter_id);
        });


        if ($event->user->package_id == 0){
            $package_id = 0;
            $status_id = 1;
            $package_cost = env('REGISTRATION_FEE');
        }
        else{
            $tag = 'package_id'.$event->user->package_id;
            $package = Cache::remember($tag, 30, function() use ($event) {
                return Package::find($event->user->package_id);
            });
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
        Balance::changeBalance($id,$package_cost,'register',$event->user->id,$event->user->program_id,$package_id,0);

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

                if(!is_null($item_user_program) && $item_user_program->package_id != 0){
                    $tag = 'status_id'.$item_user_program->status_id;
                    $item_status = Cache::remember($tag, 30, function() use ($item_user_program) {
                        return Status::find($item_user_program->status_id);
                    });

                    /*set own pv and change status*/
                    $position = 0;

                    if((count($sponsors_list) == 1 && $item == 1) || $key == 0){
                        $position = $event->user->position;
                    }
                    elseif(count($sponsors_list) == 2 && $item == 1){
                        $position = User::where('id',$sponsors_list[0])->where('status',1)->first()->position;
                    }else{

                        $current_user_first = User::where('id',$sponsors_list[$key-1])->where('position',1)->first();
                        $current_user_second =  User::where('id',$sponsors_list[$key-1])->where('position',2)->first();

                        if(!is_null($current_user_first) && strpos($list, ','.$current_user_first->id.',') !== false) $position = 1;
                        if(!is_null($current_user_second) && strpos($list, ','.$current_user_second->id.',') !== false) $position = 2;
                    }

                    Balance::setQV($item,$package->pv,$id,$package->id,$position,$item_status->id);
                    //start check small branch definition
                    $left_pv = Hierarchy::pvCounter($item,1);
                    $right_pv = Hierarchy::pvCounter($item,2);
                    if($left_pv > $right_pv) $small_branch_position = 2;
                    else $small_branch_position = 1;
                    //end check small branch definition


                    //start check next status conditions and move
                    $left_user = User::whereSponsorId($item)->wherePosition(1)->whereStatus(1)->first();
                    $right_user = User::whereSponsorId($item)->wherePosition(2)->whereStatus(1)->first();

                    $pv = Hierarchy::pvCounterAll($item);
                    $tag = 'status_id'.$item_status->order+1;
                    $next_status = Cache::remember($tag, 30, function() use ($item_status) {
                        return Status::find($item_status->order+1);
                    });

                    $prev_statuses_pv = Status::where('order','<=',$next_status->order)->sum('pv');

                    if(!is_null($left_user) && !is_null($right_user)){

                        if(!is_null($next_status)){
                            if($prev_statuses_pv <= $pv){

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

                                    $tag = 'status_id'.$item_user_program->status_id;
                                    $item_status = Cache::remember($tag, 30, function() use ($item_user_program) {
                                        return Status::find($item_user_program->status_id);
                                    });

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
                        Balance::changeBalance($item,$sum,'turnover_bonus',$id,$program->id,$package->id,$item_status->id,$to_enrollment_pv,$temp_sum);


                        /*start set  matching_bonus  */
                        $inviter_list = $item_user_program->inviter_list;
                        $inviter_list = explode(',',trim($inviter_list,','));
                        $inviter_list = array_slice($inviter_list, 0, 3);

                        foreach ($inviter_list as $inviter_key => $inviter_item){
                            if($inviter_item != ''){
                                $inviter_user_program = UserProgram::where('user_id',$inviter_item)->first();
                                if(!is_null($inviter_user_program) && $inviter_user_program->package_id != 1){

                                    $tag = 'status_id'.$inviter_user_program->status_id;
                                    $list_inviter_status = Cache::remember($tag, 30, function() use ($inviter_user_program) {
                                        return Status::find($inviter_user_program->status_id);
                                    });

                                    if($list_inviter_status->depth_line >= $inviter_key+1){
                                        Balance::changeBalance($inviter_item,$sum*$list_inviter_status->matching_bonus/100,'matching_bonus',$item_user_program->user_id,$program->id,$package->id,$list_inviter_status->id,$to_enrollment_pv,0,$inviter_key+1);
                                    }
                                }
                            }
                        }
                        /*end  set  matching_bonus  */
                    }
                    else {
                        Balance::changeBalance($item,0,'turnover_bonus',$id,$program->id,$package->id,$item_status->id,$to_enrollment_pv,$sum);
                    }

                    /*end set  turnover_bonus  */
                }
            }

            /*start set  invite_bonus  */
            $inviter_program = UserProgram::where('user_id',$inviter->id)->first();
            if(!is_null($inviter_program) && $inviter_program->package_id != 0){
                $tag = 'status_id'.$inviter_program->status_id;
                $inviter_status = Cache::remember($tag, 30, function() use ($inviter_program) {
                    return Status::find($inviter_program->status_id);
                });
                Balance::changeBalance($inviter->id,$package->pv*$inviter_status->invite_bonus/100*env('COURSE'),'invite_bonus',$id,$program->id,$package->id,$inviter_status->id,$package->pv);
            }
            /*end set  invite_bonus  */
        }

    }
}
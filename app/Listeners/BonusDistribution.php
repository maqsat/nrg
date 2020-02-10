<?php

namespace App\Listeners;

use App\Facades\Hierarchy;
use App\User;
use Carbon\Carbon;
use DB;
use App\Models\Status;
use App\Facades\Balance;
use App\Models\UserProgram;
use App\Models\BasketGood;
use App\Events\ShopTurnover;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BonusDistribution
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
     * @param  ShopTurnover  $event
     * @return void
     */
    public function handle(ShopTurnover $event)
    {

        /*init and activate*/
        $id = $event->basket->user_id;
        $user =User::find($id);

        $package = Package::find($user->package_id);
        $inviter = User::find($user->inviter_id);
        $sponsor = User::find($user->sponsor_id);

        $list = Hierarchy::getSponsorsList($user->id,'').',';

        $inviter_program = UserProgram::where('user_id',$user->inviter_id)->first();
        $inviter_status = Status::find($inviter_program->status_id);

        $qv = Basket::join('basket_good','basket_good.basket_id','=','baskets.id')
            ->join('products','basket_good.good_id','=','products.id')
            ->where('baskets.id', $event->basket->id)
            ->where('baskets.status',1)
            ->sum(DB::raw('sum(basket_good.quantity * products.qv) as qv'));

        $cv = Basket::join('basket_good','basket_good.basket_id','=','baskets.id')
            ->join('products','basket_good.good_id','=','products.id')
            ->where('baskets.id', $event->basket->id)
            ->where('baskets.status',1)
            ->sum(DB::raw('sum(basket_good.quantity * products.cv) as cv'));


        /*set own qv*/
        Balance::setQV($id,$qv,$id,$package->id,2);

        $sponsors_list = explode(',',trim($list,','));

        /*set own qv and change status*/

        $dynamic_status_for_qv = 1;
        foreach ($sponsors_list as $key => $item){

            $result = 0;
            $item_user_program = UserProgram::where('user_id',$item)->first();
            $item_status = Status::find($item_user_program->status_id);

            $position = 0;

            if((count($sponsors_list) == 1 && $item == 1) || $key == 0){
                $position = $user->position;}
            elseif(count($sponsors_list) == 2 && $item == 1){
                $position = User::where('id',$sponsors_list[0])->first()->position;
            }else{//,4866,4864,4862,1, ---------- 4867

                $current_user_first = User::where('id',$sponsors_list[$key-1])->where('position',1)->first();
                $current_user_second =  User::where('id',$sponsors_list[$key-1])->where('position',2)->first();
                $current_user_third =  User::where('id',$sponsors_list[$key-1])->where('position',3)->first();

                if(!is_null($current_user_first) && strpos($list, ','.$current_user_first->id.',') !== false) $position = 1;
                if(!is_null($current_user_second) && strpos($list, ','.$current_user_second->id.',') !== false) $position = 2;
                if(!is_null($current_user_third) && strpos($list, ','.$current_user_third->id.',') !== false) $position = 3;
            }

            Balance::setQV($item,$qv,$id,$package->id,$position);


            $position1 = Hierarchy::qvCounter($item,1);
            $next_status = Status::find($item_user_program->status_id+1);
            if($next_status->qv1 <= $position1) $result++;

            $position2 = Hierarchy::qvCounter($item,2);
            $next_status = Status::find($item_user_program->status_id+1);
            if($next_status->qv2 <= $position2) $result++;

            $position3 = Hierarchy::qvCounter($item,3);
            $next_status = Status::find($item_user_program->status_id+1);
            if($next_status->qv3 <= $position3) $result++;


            if($result == 3) {
                Hierarchy::moveNextStatus($item,$item_user_program->status_id,$user->program_id);

                $item_user_program = UserProgram::where('user_id',$item)->first();
                $item_status = Status::find($item_user_program->status_id);

                $name = User::find($item)->name;

                Notification::create([
                    'user_id'   => $item,
                    'type'      => 'move_status',
                    'message'   => "Дистрибьютор $name достиг на ранг  $item_status->title",
                    'status_id' => $item_user_program->status_id
                ]);

                if($item_user_program->status_id == 2) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 3) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);

                    $date = Carbon::parse($item_user_program->created_at);
                    $now  = Carbon::now();
                    $diff = $date->floatDiffInMonths($now);

                    if($diff === 0){
                        Balance::changeBalance($item,$item_status->quickstart_bonus/2,'quickstart_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                    }

                }
                if($item_user_program->status_id == 4) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                    $date = Carbon::parse($item_user_program->created_at);
                    $now  = Carbon::now();
                    $diff = $date->floatDiffInMonths($now);

                    if($diff === 0){
                        Balance::changeBalance($item,$item_status->quickstart_bonus/2,'quickstart_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                    }
                }if($item_user_program->status_id == 5) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 6) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 7) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 8) {
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 9){
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 10){
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 11){
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
                if($item_user_program->status_id == 12){
                    //if($item_user_program->package_id == 2 or $item_user_program->package_id == 3 or $item_user_program->package_id == 4)
                    //Balance::changeBalance($item,$item_status->status_bonus,'status_bonus',$event->user->id,$item_user_program->program_id,$item_user_program->package_id,$item_user_program->status_id);
                }
            }

        }

        $inviter_program = UserProgram::where('user_id',$user->inviter_id)->first();
        $inviter_status = Status::find($inviter_program->status_id);

        /*set turnover_bonus*/
        //echo "list turnover_bonus => $list <br>";
        $inviter_program_list = [];
        $dynamic_status_for_turnover_bonus = 1;
        if($inviter_program->list != '')   $inviter_program_list = explode(',',trim($inviter_program->list,','));

        foreach ($sponsors_list as $key => $item){
            //echo "item => $item <br>";
            $item_user_program = UserProgram::where('user_id',$item)->first();
            $item_status = Status::find($item_user_program->status_id);


            //echo "dynamic_status_for_turnover_bonus => $dynamic_status_for_turnover_bonus  : user_status  => $item_status->id<br>";
            if($dynamic_status_for_turnover_bonus >= $item_status->id){
                continue;
            }
            else{
                $dynamic_status_for_turnover_bonus = $item_status->id;
                Balance::changeBalance($item,$cv*$item_status->turnover_bonus/100,'turnover_bonus',$event->user->id,$event->user->program_id,$event->user->package_id,$item_status->id);
            }

        }

    }
}

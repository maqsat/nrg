<?php

namespace App\Helpers;

use App\Events\ShopTurnover;
use App\Models\Order;
use App\Models\Package;
use App\Models\Revitalization;
use DB;
use Auth;
use App\User;
use Carbon\Carbon;
use App\Models\Basket;
use App\Models\Status;
use App\Facades\Balance;
use App\Models\Program;
use App\Models\Counter;
use App\Models\Processing;
use App\Models\Notification;
use App\Models\UserProgram;
use App\Models\UserSubscriber;
use Illuminate\Support\Facades\Storage;

class Hierarchy {


    public $sponsor_id;


    /**
     * @param $user_id
     * @param $position
     * @return Counter
     */
    public function pvCounter($user_id,$position)
    {
        return Counter::where('user_id',$user_id)->where('position',$position)->sum('sum');

    }

    /**
     * @param $user_id
     * @param $position
     * @return Counter
     */
    public function pvWeekCounter($user_id,$position)
    {
        return Counter::where('user_id',$user_id)->where('position',$position)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');
    }

    /**
     * @param $user_id
     * @return Counter
     */
    public function pvCounterAll($user_id)
    {
        return Counter::where('user_id',$user_id)->sum('sum');
    }

    /**
     * @param $user_id
     * @return Counter
     */
    public function pvCounterAllForStatus($user_id)
    {
        $user_program = UserProgram::where('user_id',$user_id)->first();
        return Counter::where('user_id',$user_id)->where('status_id',$user_program->status_id)->sum('sum');
    }

    /**
     * @param $user_id
     * @param $status
     * @param $program_id
     */
    public function moveNextStatus($user_id,$status,$program_id)
    {
        DB::table('user_programs')
            ->where('program_id',$program_id)
            ->where('user_id', $user_id)
            ->update(['status_id' => $status]);
    }

    /**
     * @param $inviter_id
     * @return int
     */
    public function getSponsorId($inviter_id)
    {
        $sponsor_user = User::find($inviter_id);

        if($sponsor_user->default_position == 0){
            $left_pv = $this->pvCounter($inviter_id,1);
            $right_pv = $this->pvCounter($inviter_id,2);
            if($left_pv > $right_pv) $branch_position = 2;
            else $branch_position = 1;
        }
        else{
            $branch_position = $sponsor_user->default_position;
        }

        $this->getSponsorIdRecursion($inviter_id,$branch_position);

        $data = [];
        $data[] = $this->sponsor_id;
        $data[] = $branch_position;
        return $data;
    }

    public function getSponsorIdRecursion($inviter_id,$branch_position)
    {
        $position_user = User::where('sponsor_id',$inviter_id)
            ->where('position',$branch_position)
            ->where('users.status',1)
            ->first();

        if(!is_null($position_user)){
            $this->getSponsorIdRecursion($position_user->id,$branch_position);
        }
        else  $this->sponsor_id = $inviter_id;
    }

    /**
     * @param $sponsor_id
     * @param $str
     * @return string
     */
    public function getSponsorsList($sponsor_id,$str)
    {
        $user = User::where('id',$sponsor_id)->where('sponsor_id','!=',0);

        if($user->exists())
        {
            $user = $user->first();

            if(!is_null($user->id))
            {
                $str .= ",$user->sponsor_id";
                $str = Hierarchy::getSponsorsList($user->sponsor_id,$str);
            }
        }


        return $str;//substr($str, 1,-1);
    }

    /**
     * @param $inviter_id
     * @param $str
     * @return string
     */
    public function getInviterList($inviter_id,$str)
    {
        $user = User::where('id',$inviter_id)->where('inviter_id','!=',0);

        if($user->exists())
        {
            $user = $user->first();

            if(!is_null($user->id))
            {
                $str .= ",$user->inviter_id";
                $str = Hierarchy::getInviterList($user->inviter_id,$str);
            }
        }


        return $str;//substr($str, 1,-1);
    }

    public function getFollowersList($user_id, $str)
    {
        $users = User::where('sponsor_id', $user_id)->get();

        if($users->count())
        {
            foreach ($users as $user) {
                if(!is_null($user->id))
                {
                    $str .= ",$user->id";
                    $str = Hierarchy::getFollowersList($user->id,$str);
                }
            }
        }

        return $str;
    }

    public function getInviterFollowerList($user_id, $str)
    {
        $users = User::where('inviter_id', $user_id)->get();

        if($users->count())
        {
            foreach ($users as $user) {
                if(!is_null($user->id))
                {
                    $str .= ",$user->id";
                    $str = Hierarchy::getInviterFollowerList($user->id,$str);
                }
            }
        }

        return $str;
    }

    public function followersList($user_id)
    {
        $count =  DB::table('user_programs')
            ->where('list', 'like', '%,' . $user_id . ',%')
            ->orWhere('list', 'like', '%,' . $user_id)
            ->orWhere('list', 'like', $user_id . ',%')
            ->orWhere('user_id', $user_id)
            ->groupBy('user_id')
            ->get();

        return $count;
    }

    public function inviterList($user_id)
    {
        $count =  DB::table('user_programs')
            ->where('inviter_list', 'like', '%,' . $user_id . ',%')
            ->orWhere('inviter_list', 'like', '%,' . $user_id)
            ->orWhere('inviter_list', 'like', $user_id . ',%')
            ->orWhere('user_id', $user_id)
            ->groupBy('user_id')
            ->get();

        return $count;
    }

    /**
     * @param $id
     * @return string
     */
    public function getTree($id)
    {

        $render = '<ul>';

        $items = User::where('sponsor_id',$id)->where('status',1)->orderBy('position')->get();

        foreach ($items as $item) {
            $render .= '<li><div><a href="/tree/'.$item->id.'" target="blank">' . $item->name.'</a></div>';

            $innerItem = User::where('sponsor_id',$id)->where('status',1)->orderBy('position')->get();
            if (count($innerItem) > 0) {
                $render .= $this->getTree($item->id);
            }
            $render .= '</li>';
        }

        return $render . '</ul>';

    }

    /**
     * @param $id
     * @return string
     */
    public function getNewTree($id){

        $items = User::where('sponsor_id',$id)->where('status',1)->orderBy('position')->get();
        $render= [];
        foreach ($items as $key => $item) {
            $child = User::where('sponsor_id',$item->id)->where('status',1)->count();
            if($item->position == 1) $pos = 'L:';
            else $pos = 'R:';

            if ($child){
                $render[$key]['value'] = $pos.$item->name;
                $render[$key]['parent'] = $id;
                $render[$key]['children'] = $this->getNewTree($item->id);
            }
            else $render[$key]['value'] = $pos.$item->name;
        }

        return $render;


    }

    /**
     * @param $id
     */
    public function setQS()
    {
        $user_programs = UserProgram::where(DB::raw("WEEKDAY(user_programs.created_at)"),Carbon::now()->format('N')-1)->get();

        foreach ($user_programs as $item){

            $users = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.inviter_id',$item->user_id)
                ->where('users.status',1)
                ->whereBetween('users.created_at', [Carbon::now()->subDay(8), Carbon::now()])
                ->get();

            if(count($users) >= 2){
                foreach ($users as $innerItem){
                    if($item->package_id != 0){
                        if($innerItem->package_id != 0){
                            $package = Package::find($innerItem->package_id);
                            $sum = $package->pv*20/100*env('COURSE');
                            $check = Processing::where('user_id',$item->user_id)->where('in_user',$innerItem->user_id)->where('status','quickstart_bonus')->first();
                            if(is_null($check)){
                                echo $item->user_id."<br>";
                                Balance::changeBalance($item->user_id,$sum,'quickstart_bonus',$innerItem->user_id,1,$package->id,$item->status_id,$package->pv);
                            }
                        }
                    }
                }
            }
        }
    }

    public function setTempQS()
    {
        echo "<br>";

        for($i = 1; $i <= 5; $i++){ $date = new \DateTime();
            $date->setDate(2020, 5, $i);

            $dt = Carbon::create($date->format('Y'), $date->format('m'), $date->format('d'), 0, 0, 0, 'Asia/Almaty');

            $user_programs = UserProgram::where(DB::raw("WEEKDAY(user_programs.created_at)"),$date->format('N')-1)->get();

            foreach ($user_programs as $item){
                $dt2 = $dt->copy();
                $users = User::join('user_programs','users.id','=','user_programs.user_id')
                    ->where('users.inviter_id',$item->user_id)
                    ->where('users.status',1)
                    ->whereBetween('users.created_at', [$dt2->subDay(7), $dt])
                    ->get();

                if(count($users) >= 2){
                    foreach ($users as $innerItem){
                        if($item->package_id != 0){
                            if($innerItem->package_id != 0){

                                $package = Package::find($innerItem->package_id);
                                $sum = $package->pv*20/100*env('COURSE');
                                $check = Processing::where('user_id',$item->user_id)->where('in_user',$innerItem->user_id)->where('status','quickstart_bonus')->first();
                                if(is_null($check)){
                                    echo $item->user_id."<br>";
                                    Balance::changeBalance($item->user_id,$sum,'quickstart_bonus',$innerItem->user_id,1,$package->id,$item->status_id,$package->pv);
                                }
                            }
                        }

                    }
                }
            }
        }

    }

    /**
     * @param $id
     */
    public function revitalization()
    {
        $dt = Carbon::now()->addDay(1);
        $end = $dt->toDateString();
        $start = Carbon::now()->subMonth()->toDateString();


       $user_programs = User::whereDay('created_at', '=', date('d'))->get();

       foreach ($user_programs as $item) {

           $balance = Balance::getWeekBalanceByRange($item->id,$start,$end);
           if($balance >= 200){

               $order_amount = Order::where('type','shop')
                   ->where('user_id',$item->id)
                   ->where('not_original',null)
                   ->whereBetween('created_at', [$start, $end])
                   ->sum('amount');

               $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
                   ->join('basket_good','basket_good.basket_id','=','baskets.id')
                   ->join('products','basket_good.good_id','=','products.id')
                   ->where('orders.type','shop')
                   ->where('orders.user_id',$item->id)
                   ->where('orders.not_original',null)
                   ->whereBetween('orders.created_at', [$start, $end])
                   ->sum(DB::raw('basket_good.quantity * products.pv'));

               if($balance >= 400){
                   $commission_sum = 125;
                   $commission_pv = 100;
                   if($order_amount < $commission_sum){
                       $commission_sum = $commission_sum - $order_amount;
                       $commission_pv = $commission_pv - $order_pv;
                   }
                   else{
                       $commission_sum = 0;
                       $commission_pv = 0;
                   }
               }
               else{
                   $commission_sum = 65;
                   $commission_pv = 50;

                   if($order_amount < $commission_sum){
                       $commission_sum = $commission_sum - $order_amount;
                       $commission_pv = $commission_pv - $order_pv;
                   }
                   else{
                       $commission_sum = 0;
                       $commission_pv = 0;
                   }
               }

               Revitalization::insert([
                   'end_date'       => $end,
                   'start_date'     => $start,
                   'earn_amount'    => $balance,
                   'order_amount'   => $order_amount,
                   'user_id'        => $item->id,
                   'pv'             => $order_pv,
                   'cron_status'    => 0,
                   'commission_sum' => $commission_sum,
                   'commission_pv'  => $commission_pv,
               ]);
           }
       }

    }

    public function revitalizationCron()
    {
        $list = Revitalization::where('cron_status',0)->get();

        foreach ($list as $item){

            $user_program = UserProgram::where('user_id',$item->user_id)->first();

           if($item->commission_sum > 0) {
               Balance::changeBalance($item->user_id,$item->commission_sum,'revitalization',$item->user_id,1,$user_program->package_id,$user_program->status_id,$item->commission_pv);
               Balance::changeBalance($item->user_id,$item->commission_sum*0.2,'cashback',$item->user_id,1,$user_program->package_id,$user_program->status_id,$item->commission_pv);

               $data = [];
               $data['pv'] = $item->commission_pv;
               $data['user_id'] = $item->user_id;

               event(new ShopTurnover($data = $data));
           }

            $item->cron_status = 1;
            $item->save();
        }
    }

    public function orderPv($order_id,$user_id)
    {
        $order_pv = Order::join('baskets','baskets.id','=','orders.basket_id')
            ->join('basket_good','basket_good.basket_id','=','baskets.id')
            ->join('products','basket_good.good_id','=','products.id')
            ->where('orders.type','shop')
            ->where('orders.user_id',$user_id)
            ->where('orders.not_original',null)
            ->where('orders.id', $order_id)
            ->sum(DB::raw('basket_good.quantity * products.pv'));

        return $order_pv;
    }

    public function userCount($user_id,$position)
    {
        $position_user = User::whereSponsorId($user_id)->wherePosition($position)->whereStatus(1)->first();

        if(!is_null($position_user)){
            return UserProgram::join('users','user_programs.user_id','=','users.id')
                    ->where('list','like','%,'.$position_user->id.','.$user_id.',%')
                    ->where('users.inviter_id',$user_id)
                    ->count() + 1;
        }

        return 0;

    }

    /*************************** OLD METHODS ****************************/

    /**
     * @param $sponsor
     * @param $step
     * @param $program_id
     */
    public function sponsor_advance($sponsor,$step,$program_id)//6 1 1
    {
        $sponsor_users = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.sponsor_id', $sponsor)
            ->where('users.program_id',$program_id)
            ->where('users.id','!=',$program_id)
            ->where('user_programs.step','>=',$step+1)
            /*->where('user_programs.is_done',0)*/
            ->get(['users.*']);

        $program = Program::where('id',$program_id)->first();

        if(count($sponsor_users) == $program->tree){
            $current_user_sponsor = User::where('id', $sponsor)->first();

            $user_program = UserProgram::where('user_id',$sponsor)->first();
            $bonus_steps = explode(',', $program->step_bonus);

            $end_step = $program->steps;

            if($user_program->step == $end_step) {
                $this->setIsDone($current_user_sponsor->id,$end_step,$program_id);
                Balance::changeBalance($current_user_sponsor->id,$bonus_steps[$step],'step_bonus',0,$program_id);

                for ($i = 0; $i < $program->tree; $i++){
                    $this->writeUserSubscribers($current_user_sponsor->id,$sponsor_users[$i]->id,$step+1,$program_id);
                }

            }
            else {
                $this->moveNextStep($current_user_sponsor->id,$step+1,$program_id);
                Balance::changeBalance($current_user_sponsor->id,$bonus_steps[$step],'step_bonus',0,$program_id);

                for ($i = 0; $i < $program->tree; $i++){
                    $this->writeUserSubscribers($current_user_sponsor->id,$sponsor_users[$i]->id,$step+1,$program_id);
                }

                $this->sponsor_advance($current_user_sponsor->sponsor_id,$step+1,$program_id);
            }
        }
    }

    public function getPositionUsers($user_id, $action, $position)
    {
        $query = User::join('user_programs','users.id','=','user_programs.user_id');

        $position_user = User::where('sponsor_id',$user_id)
            ->where('position',$position)
            ->where('users.status',1)
            ->first();

        if (!is_null($position_user)){
            $query = $query->where('users.status',1)
                ->where('user_programs.list','like','%'.$position_user->id.','.$user_id.'%');

            if($action == 'count'){
                return $query->count()+1;
            }
            elseif($action == 'first'){
                return $query->first();
            }
            else{
                $query = $query->get(['users.*']);
                $query[] = $position_user;

                return $query;
            }
        }
        else return 0;
    }

    public function getSubscriberUsers($author_id,$subscriber_id,$program_id)
    {
        return UserSubscriber::where('author_id',$author_id)
            ->where('subscriber_id',$subscriber_id)
            ->where('program_id',$program_id)
            ->count();
    }

    /**
     * @param $program_id
     * @param $step
     * @param $user_id
     */
    public function setIsDone($user_id,$step,$program_id)
    {
        DB::table('user_programs')
            ->where('program_id',$program_id)
            ->where('step',$step)
            ->where('is_done',0)
            ->where('user_id', $user_id)
            ->update(['is_done' => 1]);
    }

    public function followersCount($user_id)
    {
        $count =  DB::table('user_programs')
            ->where('sponsors_list', 'like', '%,' . $user_id . ',%')
            ->orWhere('sponsors_list', 'like', '%,' . $user_id)
            ->orWhere('sponsors_list', 'like', $user_id . ',%')
            ->count();

        return $count;
    }

    /**
     * @param $user_id
     * @param $stage
     * @param $step
     */
    public function moveNextStep($user_id,$step,$program_id)
    {
        DB::table('user_programs')
            ->where('program_id',$program_id)
            ->where('step',$step)
            ->where('is_done',0)
            ->where('user_id', $user_id)
            ->update(['step' => $step + 1]);

        //set balance
    }

    /**
     * @param $author_id
     * @param $subscriber_id
     * @param $stage
     * @param $step
     */
    public function writeUserSubscribers($author_id,$follower_id,$step,$program_id)
    {
        DB::table('user_subscribers')->insert([
            'author_id'   => $author_id,
            'subscriber_id' => $follower_id,
            'program_id'  => $program_id,
            'step'        => $step,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @param $program_id
     * @param $step
     * @param $user_id
     */
    public function moveNextProgram($program_id,$step,$user_id)
    {
        DB::table('user_programs')
            ->where('program_id',$program_id)
            ->where('step',$step)
            ->where('is_done',0)
            ->where('user_id', $user_id)
            ->update(['step' => 1,'program_id' => $program_id + 1]);
    }

    public function deleteNonActivations()
    {
        $users = User::whereStatus('0')->whereBetween('created_at', [Carbon::now()->subDay(2),Carbon::now()->subDay(1)])->get();

        foreach ($users as $item){
            $result = UserProgram::where('user_id',$item->id)->first();
            if(is_null($result))  $item->delete();
        }
    }

    public function sponsorUsersInFirstStep($sponsor_id,$program_id)
    {
        return User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.sponsor_id', $sponsor_id)
            ->where('users.program_id',$program_id)
            ->where('users.status',1)
            ->get(['users.*']);
    }

    public function getUserSubscribersInThisStatus($user_id,$program_id)
    {
        $userProgram = UserProgram::whereUserId($user_id)->first();

        if($userProgram->status_id == 1) {
            return User::where('mentor_id',$user_id)
                ->where('status',1)
                ->where('program_id',$program_id)
                ->count();
        }
        else{
            return UserProgram::where('list','like','%,'.$user_id.',%')->where('status_id',$userProgram->status_id)->count();

        }
    }

    public function getUserSubscribersInThisStatusPersonally($user_id,$program_id)
    {
        $userProgram = UserProgram::whereUserId($user_id)->first();

            return User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.mentor_id',$user_id)
                ->where('users.status',1)
                ->where('user_programs.status_id', '>=',$userProgram->status_id)
                ->where('users.program_id',$program_id)
                ->count();
    }

    public function getPersonallyUsers($user_id)
    {
        return User::where('mentor_id',$user_id)->count();
    }

    public function qvCounterDeleted($user_id,$position)
    {
        $month = date('m');
        return Counter::where('user_id',$user_id)->where('position',$position)
            ->where(function($query) use ($month){
                $query->whereMonth('created_at',$month)
                     ->orWhereMonth('created_at',1);
            })
            ->sum('sum');
    }

    public function qvCounterByMonth($user_id,$position,$month)
    {
        return Counter::where('user_id',$user_id)->where('position',$position)
            ->where(function($query) use ($month){
                $query->whereMonth('created_at',$month)
                    ->orWhereMonth('created_at',1);
            })
            ->sum('sum');
    }

    public function qvCounterForTree($user_id,$position)
    {
        $month = date('m');
        return Counter::where('user_id',$user_id)
            ->where(function($query) use ($month){
                $query->whereMonth('created_at',$month)
                    ->orWhereMonth('created_at',1);
            })
            ->sum('sum');
    }

    public function qvCounterAllDeleted($user_id)
    {
        $month = date('m');
        return Counter::where('user_id',$user_id)
            ->where(function($query) use ($month){
                $query->whereMonth('created_at',$month)
                    ->orWhereMonth('created_at',1);
            })
            ->sum('sum');
    }

    public function qvCounterAllLastMonth($user_id)
    {
        $month = date('m')-1;
        //$month = 7;
        return Counter::where('user_id',$user_id)->whereMonth('created_at',$month)->sum('sum');
    }

    public function activationCheck()
    {
        $status = UserProgram::where('user_id',Auth::user()->id)->first();

        if($status->status_id < 5) return true;

        $sp_date = Notification::where('status_id','=',5)->where('user_id','=',Auth::user()->id)->first();
        $start_activation = Carbon::createFromDate(2020, 01, 01);

        if($sp_date->created_at < $start_activation) $startDate = date("Y-m-d",strtotime($start_activation));
        else $startDate = date("Y-m-d",strtotime($sp_date->created_at));

        $endDate = date('Y-m-d',strtotime(date("Y-m-d") . "+1 days"));

        $sum = Order::whereBetween('updated_at',[$startDate,$endDate])
            ->where('type','shop')
            ->where('user_id', Auth::user()->id)
            ->where(function($query){
                $query->where('status',4)
                    ->orWhere('status',6);
            })
            ->sum('amount');

        $sum = $sum/env('DOLLAR_COURSE');
        $endDate = date("Y-m-d");
        $months = Balance::getMonthByRange($startDate,$endDate);
        $checker_sum = env('ACTIVATION_COST')*count($months);

        return $sum >= $checker_sum;
    }
}

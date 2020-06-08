<?php

namespace App\Http\Controllers;


use App\Facades\Balance;
use App\Models\Counter;
use App\Models\Order;
use App\Models\UserProgram;
use DB;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Notification;
use App\Models\Processing;
use App\Models\Status;
use App\Models\Package;
use App\Facades\Hierarchy;
use App\Events\Activation;
use App\Events\ShopTurnover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{

    public function tester()
    {
        dd(Carbon::now()->subDay(7));
        $user  = User::find(2534);

        event(new Activation($user = $user));
    }

    public function tester2()
    {
        $users = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.status',1)
            ->get();

        foreach ($users as $item){
            $package_title = 'Без пакета';

            if($item->package_id != 0)  $package_title = Package::find($item->package_id)->title;

            echo $item->name.','.$item->number.','.$package_title.','.$item->created_at."<br>";
        }
    }


    public function delete()
    {
        $id = $_GET['id'];
        UserProgram::where('user_id',$id)->delete();
        Processing::where('user_id',$id)->delete();
        Counter::where('user_id',$id)->delete();
        User::find($id)->delete();

    }

    public function testerActivation()
    {
        $user  = User::find(1867);

        event(new Activation($user = $user));

    }

    public function changeStatusesPercentage()
    {
        dd(0);
        $list = DB::select('SELECT * FROM `processing` WHERE `created_at` >= \'2019-11-01 00:00:00\' AND `status` != \'register\'');

        foreach ($list as $item){
            $inviter_status = Status::find($item->status_id);
            $package = Package::find($item->package_id);

            if($item->status == 'sponsor_bonus'){
                Processing::where('user_id',$item->user_id)
                    ->where('sum',$item->sum)
                    ->where('status',$item->status)
                    ->where('program_id',$item->program_id)
                    ->where('package_id',$item->package_id)
                    ->where('status_id',$item->status_id)
                    ->where('created_at',$item->created_at)
                    ->update(['sum' => $package->bv*$inviter_status->sponsor_bonus/100*1]);
            }
            if($item->status == 'partner_bonus'){
                Processing::where('user_id',$item->user_id)
                    ->where('sum',$item->sum)
                    ->where('status',$item->status)
                    ->where('program_id',$item->program_id)
                    ->where('package_id',$item->package_id)
                    ->where('status_id',$item->status_id)
                    ->where('created_at',$item->created_at)
                    ->update(['sum' => $package->bv*$inviter_status->partner_bonus/100*1]);
            }
        }
    }

    public function setQS()
    {
        Hierarchy::setQSforManager(3);
        Hierarchy::setQSforManager(4);
    }

}

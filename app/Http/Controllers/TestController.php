<?php

namespace App\Http\Controllers;

use App\Facades\Balance;
use App\Models\UserProgram;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Notification;
use App\Models\Processing;
use App\Models\Status;
use App\Models\Package;
use App\Facades\Hierarchy;

use App\Events\ShopTurnover;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function tester()
    {
        /*start set  matching_bonus  */
        $inviter_list = Hierarchy::getInviterList(1,'').',';
        $inviter_list = explode(',',trim($inviter_list,','));

        $inviter_list = array_slice($inviter_list, 0, 3);

        foreach ($inviter_list as $inviter_key => $inviter_item){

            $inviter_user_program = UserProgram::where('user_id',$inviter_item)->first();
            $list_inviter_status = Status::find($inviter_user_program->status_id);
        }
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

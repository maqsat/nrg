<?php

namespace App\Helpers;

use App\Models\Counter;
use App\Models\UserProgram;
use App\User;
use App\Models\UserSubscriber;
use App\Models\Status;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Facades\Hierarchy;
use App\Models\Processing;

class Balance {

    public function changeBalance($user_id,$sum,$status,$in_user,$program_id,$package_id,$status_id,$pv = 0)
    {
        Processing::insert(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'status' => $status,
                'in_user' => $in_user,
                'program_id' => $program_id,
                'package_id' => $package_id,
                'status_id' => $status_id,
                'pv' => $pv,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function setQV($user_id,$sum,$in_user,$package_id,$position)
    {
        Counter::insert(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'inner_user_id' => $in_user,
                'package_id' => $package_id,
                'position' => $position,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                //'created_at' => '2019-07-13 07:55:45',
            ]
        );
    }

    public function getBalance($user_id)
    {
        $sum = $this->getIncomeBalance($user_id) - $this->getBalanceOut($user_id) - $this->getWeekBalance($user_id);
        return round($sum, 2);
    }


    public function getWeekBalance($user_id)
    {
        //dd(Carbon::now()->startOfWeek());
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['sponsor_bonus','partner_bonus', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','mentoring_bonus','matching_bonus','auto_bonus'])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');
        return round($sum, 2);
    }


    public function getIncomeBalance($user_id)
    {
        $sum =  Processing::whereUserId($user_id)->whereIn('status', ['sponsor_bonus','partner_bonus', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','mentoring_bonus','matching_bonus','auto_bonus'])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByStatus($user_id,$date_from,$date_to,$status)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->toDateTimeString();

        $sum = Processing::whereUserId($user_id)->where('status', $status)->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByRange($user_id,$date_from,$date_to)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->toDateTimeString();
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['sponsor_bonus','partner_bonus', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','mentoring_bonus','auto_bonus'])->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceAllUsers()
    {
        $sum = Processing::whereIn('status', ['sponsor_bonus','partner_bonus', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','mentoring_bonus','auto_bonus'])->sum('sum') - Processing::whereStatus('out')->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceOut($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereStatus('out')->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceOutAllUsers()
    {
        $sum = Processing::whereStatus('out')->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceWithOut($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereStatus('in')->sum('sum') + Processing::whereUserId($user_id)->whereStatus('bonus')->sum('sum') + Processing::whereUserId($user_id)->whereStatus('percentage')->sum('sum')  + Processing::where('in_user',$user_id)->whereStatus('transfered_in')->sum('sum') - Processing::whereUserId($user_id)->whereStatus('out')->sum('sum')  - Processing::whereUserId($user_id)->whereStatus('transfered')->sum('sum')  - Processing::whereUserId($user_id)->whereStatus('request')->sum('sum') - Processing::whereUserId($user_id)->whereStatus('transfer')->sum('sum');
        return round($sum, 2);
    }

    public function getMondaysInRange($dateFromString, $dateToString)
    {
        $dateFrom = new \DateTime($dateFromString);
        $dateTo = new \DateTime($dateToString);
        $dates = [];

        if ($dateFrom > $dateTo) {
            return $dates;
        }

        if (1 != $dateFrom->format('N')) {
            $dateFrom->modify('next monday');
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format('Y-m-d');
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }

    public function getMonthByRange($start, $end)
    {

        $months = [];

        $period = CarbonPeriod::create($start, '1 month', $end);

        foreach ($period as $dt) {
            $months[] = $dt->format("Y-m");
        }

        return $months;

    }
}

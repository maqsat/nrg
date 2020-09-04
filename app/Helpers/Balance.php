<?php

namespace App\Helpers;

use App\Models\Counter;
use App\Models\Log;
use App\Models\UserProgram;
use App\User;
use App\Models\UserSubscriber;
use App\Models\Status;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Facades\Hierarchy;
use App\Models\Processing;

class Balance {

    public function changeBalance($user_id,$sum,$status,$in_user,$program_id,$package_id=0,$status_id=0,$pv = 0,$limited_sum = 0,$matching_line = 0,$card_number = 0,$message = '', $withdrawal_method = null)
    {
        $processing = new Processing(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'status' => $status,
                'in_user' => $in_user,
                'program_id' => $program_id,
                'package_id' => $package_id,
                'status_id' => $status_id,
                'pv' => $pv,
                'card_number' => $card_number,
                'matching_line' => $matching_line,
                'limited_sum' => $limited_sum,
                'message' => $message,
                'withdrawal_method' => $withdrawal_method,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
        $processing->save();
        return $processing->id;
    }

    public function setQV($user_id,$sum,$in_user,$package_id,$position,$status_id, $alias = null)
    {
        Counter::insert(
            [
                'user_id' => $user_id,
                'sum' => $sum,
                'inner_user_id' => $in_user,
                'package_id' => $package_id,
                'position' => $position,
                'status_id' => $status_id,
                'alias' => $alias,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                //'created_at' => '2019-07-13 07:55:45',
            ]
        );
    }

    public function getBalance($user_id)
    {
        $sum = $this->getIncomeBalance($user_id) - $this->getBalanceOut($user_id);// - $this->getWeekBalance($user_id)
        return round($sum, 2);
    }

    public function getWeekBalance($user_id)
    {
        return 0;
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['turnover_bonus','matching_bonus'])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('sum');
        return round($sum, 2);
    }

    public function getIncomeBalance($user_id)
    {
        $sum =  Processing::whereUserId($user_id)->whereIn('status', ['admin_add', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->sum('sum');
        return round($sum, 2);
    }

    public function getBalanceOut($user_id)
    {
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['out','shop','revitalization'])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByStatus($user_id,$date_from,$date_to,$status)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->subday(1)->toDateTimeString();

        $sum = Processing::whereUserId($user_id)->where('status', $status)->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }

    public function getWeekBalanceByRange($user_id,$date_from,$date_to)
    {
        $date_from = explode('-',$date_from);
        $date_from = Carbon::create($date_from[0], $date_from[1], $date_from[2],0,0,0, date_default_timezone_get())->toDateTimeString();

        $date_to = explode('-',$date_to);
        $date_to = Carbon::create($date_to[0], $date_to[1], $date_to[2],23,59,59, date_default_timezone_get())->toDateTimeString();
        $sum = Processing::whereUserId($user_id)->whereIn('status', ['turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->whereBetween('created_at', [$date_from, $date_to])->sum('sum');
        return round($sum, 2);
    }

    public function revitalizationBalance($user_id)
    {
        $sum1 = Processing::whereUserId($user_id)->whereIn('status', ['cashback'])->sum('sum');
        $sum2 = Processing::whereUserId($user_id)->whereIn('status', ['revitalization-shop'])->sum('sum');

        return round($sum1-$sum2, 2);
    }

    /*************************** OLD METHODS ****************************/

    public function getBalanceAllUsers()
    {
        $sum = Processing::whereIn('status', ['admin_add', 'turnover_bonus', 'status_bonus', 'invite_bonus','quickstart_bonus','matching_bonus'])->sum('sum') - Processing::whereStatus('out')->sum('sum');
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

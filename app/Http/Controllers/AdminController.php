<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Models\Package;
use App\Models\Status;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
    }


    public function notCashBonuses(Request$request)
    {
        $not_cash_bonuses = DB::table('not_cash_bonuses')->get();
        return  view('admin.travel', compact('not_cash_bonuses'));
    }

    public function notCashBonusesAnswer($user_id, $status_id,$status)
    {
        DB::table('user_travels')->insert([
                'user_id' => $user_id,
                'status_id' => $status_id,
                'status' => $status,
            ]);

        return redirect()->back();
    }

    public function progress(Request$request)
    {
        $from = '';
        $to = '';
        if(isset($request->from)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '>=',$request->from)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $from = $request->from;
            return view('profile.progress',compact('list','to','from'));
        }

        if(isset($request->to)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '<=',$request->to)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $to = $request->to;
            return view('profile.progress',compact('list','to','from'));
        }

        if(isset($request->from) && isset($request->to)){
            $list = User::where('inviter_id','!=',0)
                ->whereDate('created_at', '>=',$request->from)
                ->whereDate('created_at', '<=',$request->to)
                ->groupBy('inviter_id')
                ->select(['inviter_id', DB::raw('count(*) as count')])
                ->orderBy('count','desc')
                ->get();
            $from = $request->from;
            $to = $request->to;
            return view('profile.progress',compact('list','to','from'));
        }

        $list = User::where('inviter_id','!=',0)
            ->groupBy('inviter_id')
            ->select(['inviter_id', DB::raw('count(*) as count')])
            ->orderBy('count','desc')
            ->get();

        return view('admin.progress',compact('list','to','from'));

    }

    public function programs()
    {

        $package = Package::where('status',1)->get();
        $user_package = Package::find(Auth::user()->package_id);
        return  view('admin.packages', compact('package','user_package'));
    }
}

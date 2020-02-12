<?php

namespace App\Http\Controllers;

use DB;
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


    public function travel(Request$request)
    {
        $statuses = Status::where('travel_bonus',1)->get();
        $ids = [];
        foreach ($statuses as $status){
            $ids[] = $status->id;
        }

        $user_program = UserProgram::whereIn('status_id',$ids)->get();

        return  view('admin.travel', compact('user_program'));
    }

    public function travelAnswer($user_id, $status_id,$status)
    {
        DB::table('user_travels')->insert([
                'user_id' => $user_id,
                'status_id' => $status_id,
                'status' => $status,
            ]);

        return redirect()->back();
    }

    public function programs()
    {

        $package = Package::where('status',1)->get();
        $user_package = Package::find(Auth::user()->package_id);
        return  view('admin.packages', compact('package','user_package'));
    }
}

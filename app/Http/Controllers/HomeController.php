<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use PayPost;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Counter;
use App\Models\Package;
use App\Models\Status;
use App\Models\Product;
use App\Models\Notification;
use App\Facades\Balance;
use App\Models\Processing;
use App\Models\Program;
use App\Events\Activation;
use App\Facades\Hierarchy;
use App\Models\UserProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('activation')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check KazPost order status
        $orders = Order::where('user_id',Auth::user()->id)->where('status',0)->where('uuid','!=',null)->where('uuid','!=',0)->get();

        foreach ($orders as $item){
            $order_id = $item->id;
            $payment_webhook = "http://nrg-max.kz/pay-processing/$order_id/";
            return redirect($payment_webhook);
        }
        //end check KazPost order status

        if(Auth::user()->status == 1){

            $user = Auth::user();

            if(isset($request->default_position)){
                if($request->default_position == 0) $default_position = 0;
                if($request->default_position == 1) $default_position = 1;
                if($request->default_position == 2) $default_position = 2;

                User::whereId($user->id)->update([
                    'default_position' => $default_position
                ]);

                return redirect('/home')->with('status', 'Тип размещение успешно изменено');
            }

            $user_program = UserProgram::where('user_id',$user->id)->first();
            $invite_list = User::whereInviterId($user->id)->whereStatus(1)->get();
            $pv_counter_all = Hierarchy::pvCounterAll($user->id);
            $pv_counter_left = Hierarchy::pvCounter($user->id,1);
            $pv_counter_right = Hierarchy::pvCounter($user->id,2);
            $list = UserProgram::where('list','like','%,'.$user->id.',%')->count();
            $package = Package::find($user_program->package_id);
            $non_activate_count = User::whereSponsorId($user->id)->whereStatus(0)->count();
            $balance = Balance::getBalance($user->id);
            $out_balance = Balance::getBalanceOut($user->id);
            $status = UserProgram::join('statuses','user_programs.status_id','=','statuses.id')
                ->where('user_programs.user_id',$user->id)
                ->select(['statuses.*'])
                ->first();

            $not_cash_bonuses = DB::table('not_cash_bonuses')->where('user_id', $user->id)->where('status',0)->get();

            $registered_week_day = $user_program->created_at->weekday();


            $today_week_day =  Carbon::now()->weekday();
            if($today_week_day < $registered_week_day){
                $quickstart_date = Carbon::now()->weekday($registered_week_day)->format('M d, Y')." 00:00:00";
            }
            else{
                $quickstart_date = Carbon::now()->addDays(7)->weekday($registered_week_day)->format('M d, Y')." 00:00:00";
            }
            $display_day    = new \DateTime($quickstart_date);
            $display_day = $display_day->format('l');

            $registered_day = $user_program->created_at->format('d');
            $today_day =  Carbon::now()->format('d');


            if($today_day < $registered_day){
                $revitalization_date = Carbon::now()->day($registered_week_day)->format('M d, Y')." 00:00:00";
            }
            else{
                $revitalization_date = Carbon::now()->addMonth(1)->day($registered_day)->format('M d, Y')." 00:00:00";
            }

            return view('profile.home', compact('user', 'invite_list', 'pv_counter_all', 'balance', 'out_balance', 'status', 'list', 'package','pv_counter_left','pv_counter_right','not_cash_bonuses','quickstart_date','revitalization_date','display_day'));
        }
        else{
            $orders = Order::where('user_id',Auth::user()->id)->where('type','register')->where('payment','manual')->orderBy('id','desc')->first();

            if(Auth::user()->country_id == 1){
                $currency_symbol = '₸';
                $current_currency = env('DOLLAR_COURSE');
            }
            else{
                $currency_symbol = '$';
                $current_currency = 1;
            }

            return view('profile.non-activated', compact('orders','current_currency','currency_symbol'));
        }
    }

    public function processing(Request $request)
    {
        $balance = Balance::getBalance(Auth::user()->id);
        $all = Balance::getIncomeBalance(Auth::user()->id);;
        $out = Balance::getBalanceOut(Auth::user()->id);
        $week = Balance::getWeekBalance(Auth::user()->id);
        //$activation = Hierarchy::activationCheck();


        if(isset($request->weeks)){
            $dateFromString = date('Y-m-d',strtotime(Auth::user()->created_at));
            $dateToString = date('Y-m-d');

            $weeks = Balance::getMondaysInRange($dateFromString, $dateToString);
            array_push($weeks, $dateToString);
            $weeks = array_reverse($weeks);

            return view('profile.processing.weeks', compact('weeks','balance', 'all', 'out','week'));
        }


        $list = Processing::whereUserId(Auth::user()->id)->where('sum','!=','0')->orderBy('created_at','desc')->paginate(100);


        return view('profile.processing.processing', compact('list', 'balance', 'all', 'out','week'));
    }

    public function profile()
    {
        $feed = User::whereSponsorId(Auth::user()->id)->orderBy('created_at','desc')->get();
        $list = User::whereSponsorId(Auth::user()->id)->get();
        $balance = Balance::getBalance(Auth::user()->id);
        return view('profile.profile', compact('list','balance','feed'));
    }

    public function marketing()
    {
        $program = Program::whereId(Auth::user('program_id')->program_id)->first();
        return view('page.marketing', compact('program'));
    }

    public function tree($user_id)
    {
        $current_user = User::join('user_programs','users.id','=','user_programs.user_id')
                        ->where('users.id',$user_id)
                        ->first();

        $left_user = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.sponsor_id',$current_user->user_id)
            ->where('users.position',1)
            ->where('users.status',1)
            ->first();

        if(!is_null($left_user)){
            $left_user_l = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.sponsor_id',$left_user->user_id)
                ->where('users.position',1)
                ->where('users.status',1)
                ->first();
            $left_user_r =  User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.sponsor_id',$left_user->user_id)
                ->where('users.position',2)
                ->where('users.status',1)
                ->first();
        }
        else{
            $left_user_l = null;
            $left_user_r =   null;
        }

        $right_user = User::join('user_programs','users.id','=','user_programs.user_id')
            ->where('users.sponsor_id',$current_user->user_id)
            ->where('users.position',2)
            ->where('users.status',1)
            ->first();

        if(!is_null($right_user)){
            $right_user_l = User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.sponsor_id',$right_user->user_id)
                ->where('users.position',1)
                ->where('users.status',1)
                ->first();
            $right_user_r =  User::join('user_programs','users.id','=','user_programs.user_id')
                ->where('users.sponsor_id',$right_user->user_id)
                ->where('users.position',2)
                ->where('users.status',1)
                ->first();
        }
        else{
           $right_user_l = null;
           $right_user_r = null;
        }


        return view('profile.tree', compact('current_user','left_user','right_user','left_user_l','left_user_r','right_user_l','right_user_r'));

    }

    public function invitations()
    {
        $list = User::where('inviter_id',Auth::user()->id)->whereStatus(1)->get();

        return view('profile.invitations', compact('list'));
    }

    public function hierarchy()
    {
        /* old*/
        $tree = Hierarchy::getTree(Auth::user()->id);
        return view('profile.hierarchy', compact('tree'));

        /* new*/

        $data = [];
        $data['value'] = Auth::user()->name;
        $data['children'] = Hierarchy::getNewTree(Auth::user()->id);
        $data = json_encode($data);

        return view('profile.hierarchy1', compact('data'));
    }

    public function hierarchyTree($id)
    {
        return response()->json(['value' => Auth::user()->name, 'children' => Hierarchy::getNewTree(Auth::user()->id)]);
    }

    public function team(Request $request)
    {
        if(isset($request->own)){
            $list = UserProgram::where('inviter_list','like','%,'.Auth::user()->id.',%')->paginate(30);
        }
        else{
            $list = UserProgram::where('list','like','%,'.Auth::user()->id.',%')->paginate(30);
        }


        return view('profile.team', compact('list'));
    }

    public function updateAvatar(Request $request){
        $user = User::find(Auth::user()->id);

        $tmp_path = date('Y')."/".date('m')."/".date('d')."/".$request->avatar->getFilename().'.'.$request->avatar->getClientOriginalExtension();
        $path = $request->avatar->storeAs('public/images', $tmp_path);
        $request->avatar = str_replace("public", "storage", $path);
        $user->photo=$request->avatar;
        $user->save();

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    public function updateProfile(Request$request)
    {

        $id = Auth::user()->id;

        $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            'email'         => ['required', 'string', 'email', 'max:255',"unique:users,email,$id"],
            'gender'        => 'required',
            'birthday'      => 'required',
            'country_id'    => 'required',
            'city_id'       => 'required',
            'address'       => 'required',
            'card'          => 'required',
            'bank'          => 'required',
        ]);


        $user = User::find(Auth::user()->id);

            if ($request->card !== $user->card) {
                DB::table('user_changes')->insert([
                    'new' => $request->card,
                    'old' => $user->card,
                    'type' => 1,
                    'user_id' => Auth::user()->id,
                ]);
            }

            if ($request->email !== $user->email) {
                DB::table('user_changes')->insert([
                    'new' => $request->email,
                    'old' => $user->email,
                    'type' => 2,
                    'user_id' => Auth::user()->id,
                ]);
            }

            if ($request->password !== null & $request->password !== "") {
                $password = bcrypt($request->password);
            } else {
                $password = $user->password;
            }

            User::whereId(Auth::user()->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'number' => $request->number,
                'birthday' => $request->birthday,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'password' => $password,//change
                'card' => $request->card,//hostory
                'gender'        =>  $request->gender,
                'bank'          =>  $request->bank,
            ]);



            return redirect()->back()->with('status', 'Успешно изменено');

    }

    public function notifications()
    {
        $in = [];
        $in[] = Auth::user()->id;
        $list = \App\Models\UserProgram::where('list','like','%,'.Auth::user()->id.',%')->get();

        foreach ($list as $item){
            $in[] = $item->user_id;
        }

        $all = Notification::whereIn('user_id',$in)->paginate(30);
        return view('profile.notifications', compact('all'));
    }

    public function programs()
    {
        $orders = Order::where('user_id',Auth::user()->id)->where('type','upgrade')->where('status','!=',4)->orderBy('id','desc')->first();

        $user_program = UserProgram::where('user_id',Auth::user()->id)->first();

        $current_package = Package::find($user_program->package_id);

        $packages_query = Package::where('status',1);

        if(!is_null($current_package)){
            $packages_query->where('pv','>',$current_package->pv);
        }

        $packages = $packages_query->get();

        $diff = Carbon::createFromFormat('Y-m-d H:i:s', $user_program->created_at)->diffInDays(Carbon::now());

        return view('profile.programs', compact('orders','packages','current_package','diff'));
    }

}

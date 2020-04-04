<?php

namespace App\Http\Controllers;

use App\Facades\Hierarchy;
use App\Models\Office;
use App\Models\Order;
use App\Models\Package;
use App\Models\Status;
use DB;
use App\User;
use Carbon\Carbon;
use Validator;
use App\Facades\Balance;
use App\Models\Processing;
use App\Models\Counter;
use App\Models\UserProgram;
use App\Models\MobileApp\UserView;
use App\Models\MobileApp\Like;
use Auth;
use Illuminate\Validation\Rule;
use App\Models\Program;
use App\Models\MobileApp\Course;
use App\Models\MobileApp\CompletedCourse;
use App\Events\Activation;
use App\Events\Upgrade;


use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class UserController extends Controller
{

    public function __construct()
    {
        //$this->middleware('admin')->except('copy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request->s)){
            $list = User::whereNotNull('program_id')->where('name','like','%'.$request->s.'%')
                ->orWhere('id','like','%'.$request->s.'%')
                ->orWhere('email','like','%'.$request->s.'%')
                ->orderBy('id','desc')
                ->paginate(30);
        }
        else{
            if(isset($request->non_activate))
                $list = User::whereNotNull('program_id')->whereStatus('0')->orderBy('id','desc')->paginate(30);
            elseif (isset($request->program))
                $list = User::whereProgramId($request->program)->orderBy('id','desc')->paginate(30);
            elseif (isset($request->upgrade_request)){

                $list = User::join('orders','users.id','=','orders.user_id')
                    ->where('orders.status',11)
                    ->where('orders.type',"upgrade")
                    ->select('users.*')
                    ->paginate(30);
                return view('user.upgrade', compact('list'));
            }
            else
                $list = User::whereNotNull('program_id')->orderBy('id','desc')->paginate(30);
        }


        return view('user.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = \App\User::whereStatus(1)->get();
        return view('user.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            //'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            //gender'        => 'required',
            //'birthday'      => 'required',
            'country_id'    => 'required',
            //'address'       => 'required',
            'password'      => [ 'required', 'string', 'min:6'],
            'created_at'    => 'required',
            'city_id'       => 'required',
            'inviter_id'    => ['required', "sponsor_in_program:1", 'exists:users,id'],
            'sponsor_id'    => 'required',
            'position'      => 'required',
            'package_id'    => 'required',
            'office_id'    => 'required',
        ]);

        $checker = User::where('sponsor_id',$request->sponsor_id)->where('position',$request->position)->count();
        if($checker > 0) return  redirect()->back()->with('status', 'Позиция занята, проверьте, есть не активированный партнер в этой позиции');

        $user = User::create([
            'name'          => $request->name,
            'number'        => $request->number,
            'email'         => $request->email,
            'gender'        => $request->gender,
            'birthday'      => $request->birthday,
            'address'       => $request->address,
            'password'      => bcrypt($request->password),
            'created_at'    => $request->created_at,
            'country_id'    => $request->country_id,
            'city_id'       => $request->city_id,
            'inviter_id'    => $request->inviter_id,
            'sponsor_id'    => $request->sponsor_id,
            'position'      => $request->position,
            'package_id'    => $request->package_id,
            'status_id'     =>  $request->status_id,
            'office_id'     =>  $request->office_id,
            'program_id'     =>  1,
        ]);


        if($request->package_id != 0){
            $package = Package::find($request->package_id);
            $cost = $package->cost + env('REGISTRATION_FEE');
            $package_id  = $package->id;
        }
        else $cost = env('REGISTRATION_FEE');

        $order =  Order::updateOrCreate(
            [
                'type' => 'register',
                'status' => 0,
                'payment' => 'manual',
                'uuid' => 0,
                'user_id' => $user->id,
            ],
            ['amount' => $cost, 'package_id' => $request->package_id]
        );

        event(new Activation($user = $user));

        return redirect('/user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Auth::loginUsingId($id);
        return redirect('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            'email'         => ['required', 'string', 'email', 'max:255',"unique:users,email,$id"],
            'gender'        => 'required',
            //'birthday'      => 'required',
            'country_id'    => 'required',
            'city_id'       => 'required',
            //'address'       => 'required',
            //'card'          => 'required',
            //'bank'          => 'required',
        ]);

        $user = User::find($id);

        if ($request->card !== $user->card) {
            DB::table('user_changes')->insert([
                'new' => $request->card,
                'old' => $user->card,
                'type' => 1,
                'user_id' => $id,
            ]);
        }

        if ($request->email !== $user->email) {
            DB::table('user_changes')->insert([
                'new' => $request->email,
                'old' => $user->email,
                'type' => 2,
                'user_id' => $id,
            ]);
        }
        if ( $request->password !== null & $request->password !== "" ){
            $password = bcrypt($request->password);
        }
        else{
            $password = $user->password;
        }

        User::whereId($id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'birthday' => $request->birthday,
            'country_id' => $request->country_id,
            'city_id' => $request->city_id,
            'address' => $request->address,
            'password' => $password,//change
            'card'          => $request->card,//hostory
            'gender'        =>  $request->gender,
            'bank'          =>  $request->bank,
        ]);

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result_sponsor = User::whereSponsorId($id);
        $result_inviter = User::whereInviterId($id);

        if($result_sponsor->exists()){
            return redirect()->back()->with('status', 'У данного пользователя имеется структура, сначала удалите людей под этим наставником');
        }
        elseif($result_inviter->exists()){
            return redirect()->back()->with('status', 'У данного пользователя имеется лично приглашенные, сначала удалите людей который данный спонсор приглашал');
        }
        elseif(!is_null(UserProgram::where('user_id',$id)->first())){
            return redirect()->back()->with('status', 'Данный пользователь активирован, удаление осуществляется только через администраторов сайта');
        }
        else
        {
            Processing::where('in_user',$id)->delete();
            User::destroy($id);
            return redirect()->back()->with('status', 'Успешно удалено');
        }

    }

    public function activation($user_id)
    {
        $user  = User::find($user_id);

        event(new Activation($user = $user));

        return "<h2>Пользователь успешно активирован!</h2>";
    }

    public function deactivation($user_id)
    {
        if (isset($_GET['upgrade'])){
            $order =  Order::where( 'type','upgrade')
                ->where('user_id',$user_id)
                ->where('status' ,11)
                ->update(
                    [
                        'status' => 12,
                    ]
                );

            return "<h2>Квитанция успешно отклонена!</h2>";
        }else{
            $order =  Order::where( 'type','register')
                ->where('user_id',$user_id)
                ->where('status' ,11)
                ->update(
                    [
                        'status' => 12,
                    ]
                );

            return "<h2>Пользователь успешно деактивирован!</h2>";
        }

    }

    public function activationUpgrade($order_id)
    {
        $order = Order::find($order_id);

        event(new Upgrade($order = $order));
        Order::where( 'id',$order_id)
            ->update(
                [
                    'status' => 4,
                ]
            );
        return "<h2>Success upgraded!</h2>";
    }

    public function deactivationUpgrade($order_id)
    {
        $order =  Order::where( 'id',$order_id)
            ->update(
                [
                    'status' => 12,
                ]
            );

        return "<h2>Квитанция успешно отклонена!</h2>";

    }


    public function registerValidate(Request $request)
    {
        $result['status'] = true;
        $program_id = $request['program_id'];
        $sponsor_id = $request['sponsor_id'];
        $result['p'] =  $request;
        $result['s'] =  $sponsor_id;

        if($request->step == 0){
            $validator = Validator::make($request->all(), [
                'program_id'    => ['required','integer', 'exists:programs,id'],
                'name'          => ['required', 'string', 'max:255'],
                'number'        => ['required','min:7'],
                'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password'      => ['required', 'string', 'min:6', 'confirmed'],
                'gender'           => ['required', Rule::notIn([0])],//,'size:12'
                'birthday'      => ['required'],
                'inviter_id'    => ['required', 'string', 'max:255',"sponsor_in_program:$program_id", 'exists:users,id'],
                ],[
                    'required' => 'Пожалуйста, заполните это поле.',
                    'inviter_id.exists' => 'Такого инвайтера не существует.',
                ]);
        }

        if($request->step == 1){
            $validator = Validator::make($request->all(), [
                'city_id'       => ['required', Rule::notIn([0])],
                'terms'         => ['required','accepted'],
            ],[
                'required' => 'Пожалуйста, заполните это поле.'
            ]);
        }


        /*if($request->step == 3){
            $validator = Validator::make($request->all(), [
                'country_id'    => ['required'],
                'city_id'       => ['required'],
                'address'       => ['required'],
                'post_index'       => ['required'],
            ]);
        }*/

        if($request->step == 4){
            /*$validator = Validator::make($request->all(), [
                'package_id'       => ['required'],
            ]);*/
        }


        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['status'] = false;
            $result['error_list'] = $messages;
            $result['error'] = $error[0];
            $result['error_messages'] = $error;
        }

        return $result;
    }

    public function sponsor_users(Request $request)
    {
        $request->validate([
            'inviter_id' => 'required', 'integer'
        ]);

        $sponsor_users = Hierarchy::followersList($request->inviter_id);
        $text = '';

        foreach ($sponsor_users  as $item){

            $left_user = User::whereSponsorId($item->user_id)->wherePosition(1)->whereStatus(1)->first();
            $right_user = User::whereSponsorId($item->user_id)->wherePosition(2)->whereStatus(1)->first();

            if(is_null($left_user) or is_null($right_user)){
                $name = User::find($item->user_id)->name;
                $tmt_text = '<option value='.$item->user_id.'>'.$name.'</option>';
                $text .= $tmt_text;
            }

        }

        return $text;
    }

    public function sponsor_positions(Request $request)
    {
        $request->validate([
            'sponsor_id' => 'required', 'integer'
        ]);

        $text = '';
        $left_user = User::whereSponsorId($request->sponsor_id)->wherePosition(1)->whereStatus(1)->first();
        $right_user = User::whereSponsorId($request->sponsor_id)->wherePosition(2)->whereStatus(1)->first();

        if(is_null($left_user))   $text .= '<option value=1>Слева</option>';
        if(is_null($right_user))   $text .= '<option value=2>Справа</option>';


        return $text;
    }

    public function user_offices(Request $request)
    {
        $request->validate([
            'city_id' => 'required', 'integer'
        ]);

        $text = '';

        $offices = Office::where('city_id',$request->city_id)->get();

        foreach ($offices  as $item){
            $tmt_text = '<option value='.$item->id.'>'.$item->title.'</option>';
            $text .= $tmt_text;
        }


        return $text;
    }

    public function transfer($id)
    {
        $users = User::whereStatus(1)->get();

        $user = User::find($id);
        $sponsor_users = Hierarchy::followersList($user->inviter_id);
        $sponsor = User::find($user->sponsor_id);


        $sponsor_users_list = [];

        foreach ($sponsor_users  as $key => $item){
            $left_user = User::whereSponsorId($item->user_id)->wherePosition(1)->whereStatus(1)->first();
            $right_user = User::whereSponsorId($item->user_id)->wherePosition(2)->whereStatus(1)->first();

            if($item->user_id != $id && $item->user_id != $sponsor->id){
                if(is_null($left_user) or is_null($right_user)){
                    $name = User::find($item->user_id)->name;
                    $sponsor_users_list[$key]['id'] = $item->user_id;
                    $sponsor_users_list[$key]['name'] = $name;
                }
            }

        }

        return view('user.transfer',compact('id','users','user','sponsor_users_list','sponsor'));
    }

    public function transferStore(Request $request)
    {
        $request->validate([
            'user_id'    => 'required',
            'inviter_id' => 'required',
            'sponsor_id' => 'required',
            'position'   => 'required',
        ]);

        $user = User::find($request->user_id);

        if ($user->sponsor_id !== $request->sponsor_id){
            $list_checker = UserProgram::where('user_id',$request->sponsor_id)->first();
           if($list_checker->user_id != $request->inviter_id){
               $pos = strpos($list_checker->list, ",$request->inviter_id,");
               if ($pos === false)  return  redirect()->back()->with('status', 'Наставник не находиться в структуре спонсора');
           }
        }

        if ($user->sponsor_id !== $request->sponsor_id){
            $checker = User::where('sponsor_id',$request->sponsor_id)->where('position',$request->position)->count();
            if($checker > 0) return  redirect()->back()->with('status', 'Позиция занята');
        }

        if ($user->inviter_id !== $request->inviter_id) {
            DB::table('user_changes')->insert([
                'new' => $request->inviter_id,
                'old' => $user->inviter_id,
                'type' => 4,
                'user_id' => $request->user_id,
            ]);

            $user->inviter_id = $request->inviter_id;
            $user->save();

            $followers = Hierarchy::inviterList($request->user_id);

            foreach ($followers as $item){
                $user_program = UserProgram::where('id',$item->id)->first();

                $inviter_list = str_replace(",$user->inviter_id,", ",$request->inviter_id,", $user_program->inviter_list);

                $user_program->inviter_list = $inviter_list;
                $user_program->save();
            }
        }

        if ($user->sponsor_id !== $request->sponsor_id) {
            DB::table('user_changes')->insert([
                'new' => $request->sponsor_id,
                'old' => $user->sponsor_id,
                'type' => 5,
                'user_id' => $request->user_id,
            ]);

            $user->sponsor_id = $request->sponsor_id;
            $user->save();

            $followers = Hierarchy::followersList($request->user_id);

            foreach ($followers as $item){
                $user_program = UserProgram::where('id',$item->id)->first();

                $list = str_replace(",$user->sponsor_id,", ",$request->sponsor_id,", $user_program->list);

                $user_program->list = $list;
                $user_program->save();
            }
        }

        if ($user->position !== $request->position) {
            DB::table('user_changes')->insert([
                'new' => $request->position,
                'old' => $user->position,
                'type' => 6,
                'user_id' => $request->user_id,
            ]);

            $user->position = $request->position;
            $user->save();
        }

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    public function program($id)
    {
        $user = User::find($id);
        $user_program = UserProgram::whereUserId($id)->first();
        $offices = Office::all();
        return view('user.program',compact('id','user','user_program','offices'));
    }

    public function programStore(Request $request,$id)
    {
        $request->validate([
            'package_id'    => 'required',
            'office_id'     => 'required',
            'status_id'     =>  'required',
        ]);

        $user = User::find($id);
        $user_program = UserProgram::where('user_id',$id)->first();

        if ($user->package_id !== $request->package_id) {
            DB::table('user_changes')->insert([
                'new' => $request->package_id,
                'old' => $user->package_id,
                'type' => 3,
                'user_id' => $id,
            ]);

            $user->package_id = $request->package_id;
            $user->save();

            $user_program->package_id = $request->package_id;
            $user_program->save();
        }

        if ($user_program->status_id !== $request->status_id) {
            DB::table('user_changes')->insert([
                'new' => $request->status_id,
                'old' => $user->status_id,
                'type' => 4,
                'user_id' => $id,
            ]);

            $user_program->status_id = $request->status_id;
            $user_program->save();
        }

        if ($user->office_id !== $request->office_id) {
            DB::table('user_changes')->insert([
                'new' => $request->office_id,
                'old' => $user->office_id,
                'type' => 5,
                'user_id' => $id,
            ]);

            $user->office_id = $request->office_id;
            $user->save();
        }

        return redirect()->back()->with('status', 'Успешно изменено');
    }

    public function processing($id)
    {
        $user  = User::find($id);
        $balance = Balance::getBalance($id);
        $all = Balance::getIncomeBalance($id);
        $out = Balance::getBalanceOut($id);
        $week = Balance::getWeekBalance($id);

        $list = Processing::whereUserId($id)->where('sum','!=','0')->orderBy('created_at','desc')->paginate(100);

        return view('user.processing', compact('list', 'balance', 'all', 'out','week','user'));
    }

    public function processingStore(Request $request)
    {

        $request->validate([
            'sum'   => 'required', 'integer',/* ,'min:1000',*/
            'user_id' => 'required', 'integer'/* ,'min:1000',*/
        ]);

        $balance = Balance::getBalance($request->user_id);
        $sum = $request->sum/385;

        if($balance < $sum) return redirect()->back()->with('status', 'У аккаунта недостаточно средств!');

        $user = User::find($request->user_id);
        $user_program = UserProgram::where('user_id',$user->id)->first();

        $data = Processing::create([
            'status' => 'out',
            'sum' => $request->sum,
            'in_user' => 0,
            'user_id' => $user->id,
            'program_id' => $user->program_id,
            'status_id' => $user_program->status_id,
            'package_id' => $user->package_id,
            'is_admin' => Auth::user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('status', 'Успешно выполнено!');
    }

    /*
     *
     * not used methods
     * */
    public function autoActivate()
    {
        // go sql query
        // $course = 1.5;
        // packages - 476
        // status old
        // if value
        dd(0);
        $users = User::where('id','!=',1)->where('id','>=',4951)->whereStatus(2)->get();//

        foreach ($users as $item){
            $user = User::find($item->id);

            if($user->status == 1) echo "<h4>$item->id => Пользователь уже активирован!</h4>";

            event(new Activation($user = $user));
            echo  "<h4>$item->id => Пользователь успешно активирован!</h4>";
        }

    }

    public function copyUsers()
    {
        dd('done');
        $users = DB::connection('mysql2')->select('select * from users');

        foreach ($users as $item){

            $position = 0;
            if(count(DB::connection('mysql2')->select('select * from users where child_id_left = ?',[$item->id])) > 0) $position = 1;
            if(count(DB::connection('mysql2')->select('select * from users where child_id_right = ?',[$item->id])) > 0) $position = 2;
            if(count(DB::connection('mysql2')->select('select * from users where child_id_center = ?',[$item->id])) > 0) $position = 3;

            if($item->id == 1){
                $item->mentor_id = 0;
                $item->inviter_id = 0;
            }

            User::create([
                'id'            => $item->id,
                'name'          => $item->name,
                'email'         => $item->email,
                'password'      => $item->password,
                'sponsor_id'    => $item->mentor_id,
                'inviter_id'    => $item->inviter_id,
                'program_id'    => 1,
                'iin'           => $item->doc_number,
                'number'        => $item->phone_number,
                'birthday'      => $item->birth_date,
                'country_id'    => 1,
                'city_id'       => 1,
                'address'       => '',
                'post_index'    => '',
                'package_id'    => 0,
                'position'      => $position,
                'created_at'    => $item->created_at,
            ]);

        }
    }

    public function rangHistory()
    {

        $userProgram = UserProgram::where('user_id',Auth::user()->id)->first();
        $list = DB::select('select sum(sum) as sum, created_at from counters where user_id = ? GROUP By MONTH(created_at) Order By created_at DESC', [Auth::user()->id]);

        return view('profile.rang-history',compact('list','userProgram'));
    }

    /*
     *
     *
     * NEw methods*/
    private function getToken($email, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt( ['email'=>$email, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            ['email' => 'required',
                'password' => 'required'],
            ['email.required'=>'Email required',
                'password.required'=>'Password required']
        );

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['error'] = $error[0];
            $result['error_code'] = 500;
            $result['status'] = false;
            return response()->json($result);
        }


        $user = \App\User::where('email', $request->email)->get()->first();

        //если пакет юзера 4 или 3
        if($user->package_id==4 || $user->package_id==3){
            if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
            {
                $token = self::getToken($request->email, $request->password);
                $user->auth_token = $token;
                $user->save();
                $response = ['success'=>true, 'data'=>[
                    'id'=>$user->id,
                    'auth_token'=>$user->auth_token,
                    'name'=>$user->name,
                    'email'=>$user->email,
                    'nickname'=>$user->nickname,
                    'photo'=>$user->photo,
                ]];
            }
            else {
                $response = ['success' => false, 'data' => 'Record doesnt exists'];
            }
        }
        else{
            $response = ['success' => false, 'message' => 'Ваш пакет не подходит'];
        }


        return response()->json($response, 201);
    }

    public function getshopusers(Request $request){
        if(isset($request->s)){
            $list = User::Where('name','like','%'.$request->s.'%')
                ->orWhere('id','like','%'.$request->s.'%')
                ->orWhere('email','like','%'.$request->s.'%')
                ->orderBy('id','desc')
                ->paginate(30);
        }
        else{

            $list = User::whereNull('program_id')->orderBy('id','desc')->paginate(30);
        }
        return view('user.shopusers', compact('list'));
    }

    //Обновляем никнейм и фото юзера
    public function updateprofile(Request $request){
        $validator = Validator::make($request->all(),
            ['nickname' => 'unique:users']
        );
        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            $result['error'] = $error[0];
            $result['error_code'] = 500;
            $result['status'] = false;
            return response()->json($result);
        }

        $user = JWTAuth::parseToken()->authenticate();

        if($request->has('nickname')){
            $user->nickname=$request->nickname;
        }
        else if($request->has('photo')){

            $tmp_path = $request->photo->getFilename().'.'.$request->photo->getClientOriginalExtension();
            $request->photo->storeAs('public/images', $tmp_path);
            //$request->photo = str_replace("public", "storage", $path);
            //dd($tmp_path);
            $user->photo="https://en-rise.com/storage/images". '/' . $tmp_path;
        }
        if ($user->save())
        {
            $response = ['success'=>true,'data'=>$user->photo];
        }
        else
            $response = ['success'=>false];

        return response()->json($response, 201);
    }

    public function userview(Request $request){
        $user = JWTAuth::parseToken()->authenticate();


        $is_there_view=UserView::where([
            ['user_id',$user->id],
            ['lesson_id',$request->lesson_id],
            ['course_id',$request->course_id]
        ])->first();

        //если нету в базе  записи то записываем
        if(!$is_there_view){

            $userview = UserView::create([
                'user_id' => $user->id,
                'lesson_id' => $request->lesson_id,
                'course_id' => $request->course_id
            ]);

            //считать сколько урокрв всего этого курса посмотрел юзер
            $coursecount = UserView::where('course_id', $request->course_id)
                ->where('user_id',$user->id)->get()->count();

            //количество всех уроков этого курса
            $courselessoncount = Course::where('id', $request->course_id)->first();

            if($coursecount==$courselessoncount['lessons_quantity']){
                CompletedCourse::create(['user_id'=>$user->id,'course_id'=>$request->course_id]);
            }
            if ($userview) {
                $response = ['success'=>true];
            }
            else
                $response = ['success'=>false];

        }else{
            $response = ['success'=>true];
        }
        return response()->json($response, 201);
    }

    public function getActivity(Request $request){
        //$data["digit"]=50;
        $data["monday"]['likes_count']=0;
        $data["tuesday"]['likes_count']=0;
        $data["wednesday"]['likes_count']=0;
        $data["thursday"]['likes_count']=0;
        $data["friday"]['likes_count']=0;
        $data["saturday"]['likes_count']=0;
        $data["sunday"]['likes_count']=0;
        $data["monday"]['views_count']=0;
        $data["tuesday"]['views_count']=0;
        $data["wednesday"]['views_count']=0;
        $data["thursday"]['views_count']=0;
        $data["friday"]['views_count']=0;
        $data["saturday"]['views_count']=0;
        $data["sunday"]['views_count']=0;
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

        $likes_count = Like::where(['user_id'=>$user->id])->whereBetween('created_at',
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        $view_count = UserView::where(['user_id'=>$user->id])->whereBetween( 'created_at',
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

        foreach($likes_count as $like){
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $like["created_at"]);
            $dayOfTheWeek = $dt->toArray()["dayOfWeek"];

            switch ($dayOfTheWeek) {
                case 1:
                    $data["monday"]['likes_count']++;
                    break;
                case 2:
                    $data["tuesday"]['likes_count']++;
                    break;
                case 3:
                    $data["wednesday"]['likes_count']++;
                    break;
                case 4:
                    $data["thursday"]['likes_count']++;
                    break;
                case 5:
                    $data["friday"]['likes_count']++;
                    break;
                case 6:
                    $data["saturday"]['likes_count']++;
                    break;
                case 7:
                    $data["sunday"]['likes_count']++;
                    break;
            }
        }

        foreach($view_count as $view){
            $dt = Carbon::createFromFormat('Y-m-d H:i:s', $view["created_at"]);
            $dayOfTheWeek = $dt->toArray()["dayOfWeek"];
            switch ($dayOfTheWeek) {
                case 1:
                    $data["monday"]['views_count']++;
                    break;
                case 2:
                    $data["tuesday"]['views_count']++;
                    break;
                case 3:
                    $data["wednesday"]['views_count']++;
                    break;
                case 4:
                    $data["thursday"]['views_count']++;
                    break;
                case 5:
                    $data["friday"]['views_count']++;
                    break;
                case 6:
                    $data["saturday"]['views_count']++;
                    break;
                case 7:
                    $data["sunday"]['views_count']++;
                    break;
            }
        }

        if ($user)
        {
            /*Выявляем максимумм и передаем *2*/
            $array=[];
            foreach($data as $item) {
                $count=0;
                foreach ($item as $it){
                    $count=$count+$it;
                }
                array_push($array,$count);
            }
            $data = array('digit' =>max($array)*1.3) + $data;
            /*********/
            $response = ['success'=>true,'data'=>$data ];
        }
        else
            $response = ['success'=>false];

        return response()->json($response, 201);
    }

    public function completedcourses(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $courses=User::find($user->id)->completedcourses;

        if ($courses) {
            $response = ['success'=>true , 'data'=>$courses];
        }
        else
            $response = ['success'=>false];

        return response()->json($response, 201);
    }
    /*End new methods*/

}

<?php

namespace App\Http\Controllers;

use App\Facades\Hierarchy;
use App\Models\Office;
use App\Models\Order;
use App\Models\Package;
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
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'gender'        => 'required',
            'birthday'      => 'required',
            'country_id'    => 'required',
            'address'       => 'required',
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
        if($checker > 0) return  redirect()->back();

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
        $user = User::find($id);
        if($user->status == 1){
            $list = User::whereSponsorId($id)->get();
            $non_activate_count = User::whereSponsorId($id)->whereStatus(0)->count();
            $in_sum = Program::find($user->program_id)->in_sum;
            $balance = Balance::getBalance($id);

            return view('profile.home', compact('list','non_activate_count','in_sum','balance', 'user'));
        }
        else
            return view('profile.non-activated');
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
        $result = User::whereSponsorId($id);
        $result_mentor = User::whereInviterId($id);

        if($result->exists()){
            return redirect()->back()->with('status', 'У данного пользователя имеется структура, сначала удалите людей который данный спонсор приглашал');
        }
        elseif($result_mentor->exists()){
            return redirect()->back()->with('status', 'У данного пользователя имеется лично приглашенные, сначала удалите людей который данный спонсор приглашал');
        }
        elseif(User::find($id)->status == 1){
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

    public function transfer($id)
    {
        $users = \App\User::whereStatus(1)->get();
        $user = User::find($id);
        return view('user.transfer',compact('id','users','user'));
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

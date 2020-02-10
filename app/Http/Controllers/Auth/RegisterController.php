<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Facades\Hierarchy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $program_id = $data['program_id'];

        //$sponsor_id = $data['sponsor_id'];
        //$position = $data['position'];

        return Validator::make($data, [
            'inviter_id' => ['required', 'string', 'max:255',"sponsor_in_program:$program_id", 'exists:users,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'birthday' => ['required'],
            'terms' => ['required', 'accepted'],
            'city_id' => ['required'],
            //'sponsor_id' => ['required', 'string', 'max:255', 'exists:users,id', "sponsor_in_program:$program_id"],
            //'position' => ['required', "is_exist_position_sponsor:$sponsor_id", "third_position:$sponsor_id"],

            //'iin' => ['required'],//,'size:12'
            //'country_id' => ['required'],
            //'address' => ['required'],
            //'post_index' => ['required'],
            //'package_id' => ['required'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $sponsor_data = Hierarchy::getSponsorId($data['inviter_id']);
        $sponsor_id = $sponsor_data[0];
        $position = $sponsor_data[1];

        return User::create([
            'name' => $data['name'],
            'number' => $data['number'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'inviter_id' => $data['inviter_id'],
            'program_id' => $data['program_id'],
            'birthday' => $data['birthday'],
            'city_id' => $data['city_id'],
            'sponsor_id' => $sponsor_id,
            'position' => $position,

            //'country_id' => $data['country_id'],
            //'address' => $data['address'],
            //'post_index' => $data['post_index'],
            //'package_id' => $data['package_id'],

        ]);
    }
}

<?php

namespace App\Providers;

use App\Models\UserProgram;
use App\User;
use App\Models\Program;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('sponsor_in_program', function ($attribute, $value, $parameters, $validator) {
            $count = User::where('program_id',$parameters[0])->where('id',$value)->where('status',1)->count();
            if($count > 0) return true;
            else return false;
        });

        Validator::extend('is_exist_position_sponsor', function ($attribute, $value, $parameters, $validator) {

            $count = User::where('sponsor_id',$parameters[0])->where('position',$value)->count();
            if($count == 0) return true;
            else return false;
        });

        Validator::extend('third_position', function ($attribute, $value, $parameters, $validator) {

            if($value == 3) {
                $userProgram = UserProgram::where('user_id',$parameters[0])->first();

                if($userProgram->status_id >= 4) return true;
                else return false;
            }
            else return true;

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

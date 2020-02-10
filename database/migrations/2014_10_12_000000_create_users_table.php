<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('program_id');
            $table->string('login');
            $table->integer('sponsor_id');
            $table->string('name');
            $table->string('iin');
            $table->string('number');
            $table->string('card');
            $table->string('email')->unique();
            $table->string('nickname')->nullable();
            $table->string('birthday');
            $table->integer('country_id');
            $table->integer('city_id');
            $table->string('address');
            $table->integer('status')->default(0);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

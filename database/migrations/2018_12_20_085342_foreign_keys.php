<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processing', function (Blueprint $table) {
            /*$table->foreign('in_user')->references('id')->on('users');*/
            /*$table->foreign('user_id')->references('id')->on('users');*/
            /*$table->foreign('sponsor_id')->references('id')->on('users');*/
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processing', function (Blueprint $table) {
            /*$table->dropForeign(['in_user']);*/
            /*$table->dropForeign(['user_id']);*/
            /*$table->dropForeign(['sponsor_id']);*/
        });
    }
}

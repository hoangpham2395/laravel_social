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
            $table->bigIncrements('id');
            $table->string('name', 128);
            $table->string('email', 64)->nullable();
            $table->string('tel', 32)->nullable();
            $table->string('password', 60)->nullable();
            $table->rememberToken();
            $table->char('type_login', 1)->default(0)->comment('0:password, 1:facebook, 2:google, 3:yahoo, 4:zalo, 5:line');
            $table->string('social_id', 255)->nullable();
            $table->char('status', 1)->default(1)->comment('0:blocked, 1:active');
            $table->integer('ins_id')->nullable();
            $table->integer('upd_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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

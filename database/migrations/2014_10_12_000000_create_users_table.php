<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('image')->default('/public/images/user/default.png');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone');
            $table->string('commercial_reg_num')->nullable();
            $table->integer('category_id');
            $table->string('otp')->nullable();
            $table->string('fcm_token');
            $table->string('device_token');
            $table->string('role')->comment('1=>shopper,2=>vendor');
            $table->integer('status')->comment('1=>active')->default(0);
            $table->integer('is_deleted')->comment('1=>deleted')->default(0);
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

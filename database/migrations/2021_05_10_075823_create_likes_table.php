<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->comment('shopper_id');
            $table->integer('vendor_id')->comment('vendor_id');
            $table->integer('user_id')->comment('shopper_id');
            $table->integer('status')->comment('1=>active')->default(1);
            $table->integer('is_deleted')->comment('1=>deleted')->default(0);
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
        Schema::dropIfExists('likes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsubcategories', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('/public/images/subcategroy/default.png');
            $table->string('en_subsubcategory');
            $table->string('ar_subsubcategory');
            $table->string('subcategory_id');
            $table->string('category_id');
            $table->integer('status')->comment('1=>active')->default(0);
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
        Schema::dropIfExists('subsubcategories');
    }
}

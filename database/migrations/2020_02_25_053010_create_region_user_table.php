<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('region_name')->nullable()->comment('Region Group Name');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('user_id')->comment('User ID except SA');
            $table->unique(['region_id','user_id']);
            $table->timestamps();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region_user');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssociateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associate_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('Id of Admin and Regional Admin');
            $table->unsignedBigInteger('staff_id');
            $table->integer('staff_status')->default('0')->comment('3: Assign by Super Admin,4: Created by Admin or Regional Admin');
            $table->timestamps();
            $table->unique(['user_id','staff_id']);
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('associate_user');
    }
}

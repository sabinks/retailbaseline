<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesFormDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities_form_datas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('image');
            $table->json('input_datas');
            $table->unsignedBigInteger('user_id')->comment('Staff Id');
            $table->unsignedBigInteger('entities_form_id');

            $table->index(["user_id"], 'fk_entities_form_datas_user_idx');
            $table->index(["entities_form_id"], 'fk_entities_form_datas_form_idx');


            $table->foreign('user_id', 'fk_entities_form_datas_user_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('entities_form_id', 'fk_entities_form_datas_form_idx')
                ->references('id')->on('entities_forms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('entities_form_datas');
    }
}

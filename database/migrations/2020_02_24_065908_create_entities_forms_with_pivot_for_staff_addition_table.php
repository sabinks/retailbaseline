<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntitiesFormsWithPivotForStaffAdditionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('form_title');
            $table->json('inputs');
            $table->unsignedBigInteger('user_id');

            $table->index(["user_id"], 'fk_entities_forms_user_idx');


            $table->foreign('user_id', 'fk_entities_forms_user_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();
        });

        Schema::create('entities_form_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('entities_form_id');
            $table->unsignedBigInteger('assigner_id');
            $table->unsignedInteger('entity_visit_count');

            $table->index(["user_id"], 'fk_entities_form_user_user_idx');
            $table->index(["entities_form_id"], 'fk_entities_form_user_form_idx');
            $table->index(["assigner_id"], 'fk_entities_form_user_assigner_idx');


            $table->foreign('user_id', 'fk_entities_form_user_user_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('entities_form_id', 'fk_entities_form_user_form_idx')
                ->references('id')->on('entities_forms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('assigner_id', 'fk_entities_form_user_assigner_idx')
                ->references('id')->on('users')
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
        Schema::dropIfExists('entities_form_user');
        Schema::dropIfExists('entities_forms');
    }
}

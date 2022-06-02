<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTableForSubmittingEntityFormToSuperAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entities_form_super_admin', function (Blueprint $table) {
            $table->unsignedBigInteger('entities_form_id');
            $table->unsignedBigInteger('super_admin_id');

            $table->index(["entities_form_id"], 'fk_entities_form_super_admin_entities_form_idx');
            $table->index(["super_admin_id"], 'fk_entities_form_super_admin_super_admin_idx');


            $table->foreign('entities_form_id', 'fk_entities_form_super_admin_entities_form_idx')
                ->references('id')->on('entities_forms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('super_admin_id', 'fk_entities_form_super_admin_super_admin_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entities_form_super_admin');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotToAddClientInEntitiesForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_entities_form', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('entities_form_id');
            $table->unsignedBigInteger('client_id');


            $table->index(["entities_form_id"], 'fk_client_entities_form_entities_form_idx');
            $table->index(["client_id"], 'fk_client_entities_form_client_idx');
            $table->timestamps();

            $table->foreign('entities_form_id', 'fk_client_entities_form_entities_form_idx')
                ->references('id')->on('entities_forms')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('client_id', 'fk_client_entities_form_client_idx')
                ->references('id')->on('companies')
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
        Schema::dropIfExists('client_entities_form');
    }
}

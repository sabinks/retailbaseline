<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('entity_id');
            $table->json('data')->nullable();
            $table->date('assigned_date');
            $table->date('filled_date')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: assigned, 2: pending, 3: approved, 4: rejected');
            $table->text('note')->nullable();

            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('staff_id')->references('id')->on('users');
            $table->foreign('entity_id')->references('id')->on('entities_form_datas');
            $table->foreign('region_id')->references('id')->on('regions');

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
        Schema::dropIfExists('report_data');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedReportFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_report_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('assigner_id')->comment('who assigned ');
            $table->unsignedBigInteger('assigned_id')->comment('To whom form is assign');
            $table->unsignedBigInteger('company_id')->comment('Company of assigner');
            $table->unsignedBigInteger('report_id')->comment('Id of Report created by assigner');
            $table->unique(['assigned_id','assigner_id','report_id']);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('assigned_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assigned_report_forms');
    }
}

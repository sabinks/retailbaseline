<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntitygroupIdToReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->unsignedBigInteger('entitygroup_id')->after('region_id')->nullable();
            $table->foreign('entitygroup_id')->references('id')->on('entitygroups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn('entitygroup_id');
        });
    }
}

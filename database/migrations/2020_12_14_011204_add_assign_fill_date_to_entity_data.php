<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignFillDateToEntityData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities_form_datas', function (Blueprint $table) {
            $table->dateTime('assigned_date')->nullable()->after('input_datas');
            $table->dateTime('filled_date')->nullable()->after('input_datas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities_form_datas', function (Blueprint $table) {
            $table->dropColumn('assigned_date');
            $table->dropColumn('filled_date');
        });
    }
}

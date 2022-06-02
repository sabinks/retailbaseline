<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusRegionToEntitiesFormDatasAndCreatePivotTableForAssigningEntityToClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entities_form_datas', function (Blueprint $table) {
            $table->tinyInteger('status')->after('id')->default(1)->comment('1: created, 2: accepted, 3: rejected');
            $table->unsignedBigInteger('region_id')->after('entities_form_id');

            $table->index(["region_id"], 'fk_entities_form_datas_region_idx');

            $table->foreign('region_id', 'fk_entities_form_datas_region_idx')
                ->references('id')->on('regions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('client_entities_form_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('entities_form_data_id');
            $table->timestamps();
            $table->index(["client_id"], 'fk_client_entities_form_data_client_idx');
            $table->index(["entities_form_data_id"], 'fk_client_entities_form_data_entities_form_data_idx');


            $table->foreign('client_id', 'fk_client_entities_form_data_client_idx')
                ->references('id')->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('entities_form_data_id', 'fk_client_entities_form_data_entities_form_data_idx')
                ->references('id')->on('entities_form_datas')
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
        Schema::dropIfExists('client_entities_form_data');
        Schema::table('entities_form_datas', function (Blueprint $table) {
            $table->dropForeign('fk_entities_form_datas_region_idx');
            $table->dropIndex('fk_entities_form_datas_region_idx');
            $table->dropColumn('region_id');
            $table->dropColumn('status');
        });
    }
}

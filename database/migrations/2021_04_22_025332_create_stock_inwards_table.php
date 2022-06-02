<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockInwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_inwards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('company_id');
            $table->string('unique_id');
            $table->string('po_number');
            // $table->string('model_name');
            $table->string('esn');
            $table->string('iccid');
            $table->date('date');
            $table->string('zone');
            $table->text('details');
            $table->text('remarks');
            $table->text('remarks_2');
            // $table->string('plan');
        
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('company_id')->references('id')->on('companies');
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
        Schema::dropIfExists('stock_inwards');
    }
}

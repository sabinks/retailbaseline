<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutwardStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outward_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('entity_id');
            $table->string('owner');
            $table->string('contact')->nullable();
            $table->unsignedBigInteger('quantity')->nullable();
            $table->date('entry_date');
            $table->text('particular');
            $table->unsignedBigInteger('company_id');

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('entity_id')->references('id')->on('entities_form_datas');
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
        Schema::dropIfExists('outward_stocks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unique_id');
            $table->string('name');
            $table->string('address');
            $table->unsignedBigInteger('document_id');
            $table->string('form_image');
            $table->string('card_front');
            $table->string('card_back');
            $table->string('photo');
            $table->string('reg_detail');
            $table->string('amount')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->dateTime('filled_date');
            $table->dateTime('sync_date');
            $table->unsignedBigInteger('form_id')->default(1);  // 1 for sim
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('staff_id')->references('id')->on('users');
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::dropIfExists('subscription_forms');
    }
}

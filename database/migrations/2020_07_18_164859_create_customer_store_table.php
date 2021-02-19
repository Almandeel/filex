<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_store', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('store_id');

            $table->index(["store_id"], 'fk_customer_store_stores1_idx');

            $table->index(["customer_id"], 'fk_customer_store_customers1_idx');
            $table->timestamps();


            $table->foreign('customer_id', 'fk_customer_store_customers1_idx')
                ->references('id')->on('customers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('store_id', 'fk_customer_store_stores1_idx')
                ->references('id')->on('stores')
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
        Schema::dropIfExists('customer_store');
    }
}

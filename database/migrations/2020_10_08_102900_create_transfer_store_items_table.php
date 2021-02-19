<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferStoreItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_store_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('item_store_unit_id')->nullable();
            $table->unsignedBigInteger('transfer_store_id');
            $table->integer('quantity')->nullable();

            $table->index(["item_store_unit_id"], 'fk_items_transfer_stores_items1_idx');

            $table->timestamps();

            $table->foreign('item_store_unit_id', 'fk_items_transfer_stores_items1_idx')
                ->references('id')->on('item_store_unit')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('transfer_store_id')
                ->references('id')->on('transfer_stores')
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
        Schema::dropIfExists('transfer_store_items');
    }
}

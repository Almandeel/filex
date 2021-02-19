<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('from_store');
            $table->unsignedInteger('to_store');
            $table->unsignedInteger('user_id')->nullable();

            $table->index(["from_store"], 'fk_from_store_stores1_idx');
            $table->index(["to_store"], 'fk_to_store_stores2_idx');

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('from_store', 'fk_from_store_stores1_idx')
                ->references('id')->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('to_store', 'fk_to_store_stores2_idx')
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
        Schema::dropIfExists('transfer_stores');
    }
}

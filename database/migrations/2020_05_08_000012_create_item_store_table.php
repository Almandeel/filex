<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemStoreTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'item_store';

    /**
     * Run the migrations.
     * @table item_store
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('store_id');
            // $table->integer('quantity')->nullable();
            // $table->double('price_purchase')->nullable()->default('0');
            // $table->double('price_sell')->nullable()->default('0');

            $table->index(["item_id"], 'fk_items_has_stores_items1_idx');

            $table->index(["store_id"], 'fk_items_has_stores_stores1_idx');
            $table->timestamps();


            $table->foreign('item_id', 'fk_items_has_stores_items1_idx')
                ->references('id')->on('items')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('store_id', 'fk_items_has_stores_stores1_idx')
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
       Schema::dropIfExists($this->tableName);
     }
}

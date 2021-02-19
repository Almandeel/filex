<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemStoreUnitTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'item_store_unit';

    /**
     * Run the migrations.
     * @table item_store_unit
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->unsignedInteger('item_store_id');
            $table->integer('quantity')->nullable()->default('0');
            $table->double('price_purchase')->nullable()->default('0');
            $table->double('price_sell')->nullable()->default('0');
            $table->unsignedInteger('item_unit_id');

            $table->index(["item_store_id"], 'fk_item_store_item_store_item_item_store1_idx');
            $table->index(["item_unit_id"], 'fk_item_store_unit_item_unit1_idx');
            $table->timestamps();


            $table->foreign('item_store_id', 'fk_item_store_item_store_item_item_store1_idx')
                ->references('id')->on('item_store')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('item_unit_id', 'fk_item_store_unit_item_unit1_idx')
                ->references('id')->on('item_unit')
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

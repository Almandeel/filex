<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemUnitTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'item_unit';

    /**
     * Run the migrations.
     * @table item_unit
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('unit_id');
            $table->float('price')->default(0.00);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->index(["unit_id"], 'fk_items_has_units_units1_idx');

            $table->index(["item_id"], 'fk_items_has_units_items1_idx');
            $table->timestamps();


            $table->foreign('item_id', 'fk_items_has_units_items1_idx')
                ->references('id')->on('items')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('unit_id', 'fk_items_has_units_units1_idx')
                ->references('id')->on('units')
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

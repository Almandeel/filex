<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillItemTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'bill_item';
    
    /**
    * Run the migrations.
    * @table bill_item
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('item_store_unit_id')->nullable();
            $table->integer('quantity')->nullable()->default('0');
            $table->double('price')->nullable()->default('0');
            $table->double('expense')->default(0);
            $table->double('expenses')->default(0);
            $table->unsignedInteger('bill_id');
            $table->unsignedInteger('bill_item_id')->nullable();
            
            $table->index(["bill_id"], 'fk_bill_item_unit_bills1_idx');
            
            $table->index(["item_store_unit_id"], 'fk_bill_item_unit_item_store_unit1_idx');
            
            $table->index(["bill_item_id"], 'fk_bill_item_unit_bill_item_id1_idx');
            $table->timestamps();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            
            $table->index(["item_id"], 'fk_bill_item_items1_idx');
            $table->index(["unit_id"], 'fk_bill_item_units1_idx');
            
            
            $table->foreign('item_id', 'fk_bill_item_items1_idx')
            ->references('id')->on('items')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('unit_id', 'fk_bill_item_units1_idx')
            ->references('id')->on('units')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('bill_item_id', 'fk_bill_item_unit_bill_item_id1_idx')
            ->references('id')->on('bill_item')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('item_store_unit_id', 'fk_bill_item_unit_item_store_unit1_idx')
            ->references('id')->on('item_store_unit')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('bill_id', 'fk_bill_item_unit_bills1_idx')
            ->references('id')->on('bills')
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
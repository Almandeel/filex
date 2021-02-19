<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'invoice_item';
    
    /**
    * Run the migrations.
    * @table invoice_item
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('item_store_unit_id')->nullable();
            $table->integer('quantity')->nullable()->default('0');
            $table->double('price_sell')->nullable()->default('0');
            $table->double('price_purchase')->nullable()->default('0');
            $table->double('expenses')->nullable()->default('0');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('invoice_item_id')->nullable();
            $table->unsignedInteger('bill_item_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            
            $table->index(["item_id"], 'fk_invoice_item_items1_idx');
            $table->index(["unit_id"], 'fk_invoice_item_units1_idx');
            
            
            $table->foreign('item_id', 'fk_invoice_item_items1_idx')
            ->references('id')->on('items')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('unit_id', 'fk_invoice_item_units1_idx')
            ->references('id')->on('units')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->index(["invoice_id"], 'fk_invoice_item_invoices1_idx');
            
            // $table->index(["bill_id"], 'fk_invoice_item_bills1_idx');
            
            $table->index(["item_store_unit_id"], 'fk_invoice_item_unit_item_store_unit1_idx');
            
            $table->index(["invoice_item_id"], 'fk_invoice_item_unit_invoice_item_id1_idx');
            $table->timestamps();
            
            
            $table->foreign('bill_item_id', 'fk_invoice_item_bills1_idx')
            ->references('id')->on('bill_item')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('invoice_item_id', 'fk_invoice_item_invoices1_idx')
            ->references('id')->on('invoice_item')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('item_store_unit_id', 'fk_invoice_item_unit_item_store_unit1_idx')
            ->references('id')->on('item_store_unit')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('invoice_id', 'fk_invoice_item_unit_invoices1_idx')
            ->references('id')->on('invoices')
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
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_supplier', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('store_id');

            $table->index(["store_id"], 'fk_store_supplier_stores1_idx');

            $table->index(["supplier_id"], 'fk_store_supplier_suppliers1_idx');
            $table->timestamps();


            $table->foreign('supplier_id', 'fk_store_supplier_suppliers1_idx')
                ->references('id')->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('store_id', 'fk_store_supplier_stores1_idx')
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
        Schema::dropIfExists('store_supplier');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillEntryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_entry', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('bill_id');
            $table->unsignedInteger('entry_id');
            
            
            $table->index(["bill_id"], 'fk_bill_entry_bills_idx1');
            $table->index(["entry_id"], 'fk_bill_entry_entries_idx1');
            $table->timestamps();


            $table->foreign('entry_id', 'fk_bill_entry_entries_idx1')
                ->references('id')->on('entries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('bill_id', 'fk_bill_entry_bills_idx1')
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
        Schema::dropIfExists('bill_entry');
    }
}

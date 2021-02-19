<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntryInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entry_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('entry_id');
            
            
            $table->index(["invoice_id"], 'fk_entry_invoice_invoices_idx1');
            $table->index(["entry_id"], 'fk_entry_invoice_entries_idx1');
            $table->timestamps();


            $table->foreign('entry_id', 'fk_entry_invoice_entries_idx1')
                ->references('id')->on('entries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('invoice_id', 'fk_entry_invoice_invoices_idx1')
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
        Schema::dropIfExists('entry_invoice');
    }
}

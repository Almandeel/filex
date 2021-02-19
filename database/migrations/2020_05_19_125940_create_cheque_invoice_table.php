<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('cheque_id');
            
            
            $table->index(["invoice_id"], 'fk_cheque_invoice_invoices_idx1');
            $table->index(["cheque_id"], 'fk_cheque_invoice_cheques_idx1');
            $table->timestamps();


            $table->foreign('cheque_id', 'fk_cheque_invoice_cheques_idx1')
                ->references('id')->on('cheques')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('invoice_id', 'fk_cheque_invoice_invoices_idx1')
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
        Schema::dropIfExists('cheque_invoice');
    }
}

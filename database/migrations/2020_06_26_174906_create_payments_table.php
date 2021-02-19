<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('safe_id');
            $table->index(["entry_id"], 'fk_payments_entries_idx1');
            $table->index(["safe_id"], 'fk_payments_safes_idx1');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->index(["bill_id"], 'fk_payments_bills_idx1');
            $table->index(["invoice_id"], 'fk_payments_invoices_idx1');
            $table->timestamps();


            $table->foreign('bill_id', 'fk_payments_bills_idx1')
                ->references('id')->on('bills')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->foreign('invoice_id', 'fk_payments_invoices_idx1')
                ->references('id')->on('invoices')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->foreign('user_id', 'fk_payments_users_idx1')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->foreign('safe_id', 'fk_payments_safes_idx1')
                ->references('id')->on('safes')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('entry_id', 'fk_payments_entries_idx1')
                ->references('id')->on('entries')
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
        Schema::dropIfExists('payments');
    }
}

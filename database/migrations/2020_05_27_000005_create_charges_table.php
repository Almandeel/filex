<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'charges';

    /**
     * Run the migrations.
     * @table charges
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->double('amount')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->unsignedInteger('entry_id');
            $table->index(["entry_id"], 'fk_charges_entries_idx1');
            $table->index(["bill_id"], 'fk_charges_bills_idx1');
            $table->index(["invoice_id"], 'fk_charges_invoices_idx1');
            $table->timestamps();


            $table->foreign('invoice_id', 'fk_charges_invoices_idx1')
                ->references('id')->on('invoices')
                ->onDelete('cascade')
                ->onUpdate('cascade');



            $table->foreign('bill_id', 'fk_charges_bills_idx1')
                ->references('id')->on('bills')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('entry_id', 'fk_charges_entries_idx1')
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
       Schema::dropIfExists($this->tableName);
     }
}

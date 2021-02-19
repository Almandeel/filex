<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillChequeTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('bill_cheque', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('bill_id');
            $table->unsignedInteger('cheque_id');
            
            
            $table->index(["bill_id"], 'fk_bill_cheque_bills_idx1');
            $table->index(["cheque_id"], 'fk_bill_cheque_cheques_idx1');
            $table->timestamps();


            $table->foreign('cheque_id', 'fk_bill_cheque_cheques_idx1')
                ->references('id')->on('cheques')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('bill_id', 'fk_bill_cheque_bills_idx1')
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
        Schema::dropIfExists('bill_cheque');
    }
}
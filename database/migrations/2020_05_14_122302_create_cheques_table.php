<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('cheques', function (Blueprint $table) {
            $table->increments('id');
            $table->string('bank_name')->nullable();
            $table->integer('number')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->double('amount')->default(0);
            $table->string('details')->nullable();
            $table->date('due_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('benefit')->nullable();
            $table->unsignedInteger('benefit_id')->nullable();
            $table->unsignedInteger('account_id')->nullable();
            $table->unsignedInteger('entry_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->index(["account_id"], 'fk_cheques_accounts_idx1');
            $table->index(["user_id"], 'fk_cheques_users_idx1');
            $table->index(["bill_id"], 'fk_cheques_bills_idx1');
            $table->index(["invoice_id"], 'fk_cheques_invoices_idx1');
            // $table->unsignedInteger('collaborator_id')->nullable();
            // $table->index(["collaborator_id"], 'fk_cheques_collaborators_idx1');
            $table->timestamps();
            
            
            // $table->foreign('collaborator_id', 'fk_cheques_collaborators_idx1')
            // ->references('id')->on('collaborators')
            // ->onDelete('no action')
            // ->onUpdate('no action');
            
            
            $table->foreign('bill_id', 'fk_cheques_bills_idx1')
            ->references('id')->on('bills')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            
            $table->foreign('invoice_id', 'fk_cheques_invoices_idx1')
            ->references('id')->on('invoices')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            
            $table->foreign('account_id', 'fk_cheques_accounts_idx1')
            ->references('id')->on('accounts')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            
            $table->foreign('user_id', 'fk_cheques_users_idx1')
            ->references('id')->on('users')
            ->onDelete('no action')
            ->onUpdate('no action');
            
        });
    }
    
    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('cheques');
    }
}
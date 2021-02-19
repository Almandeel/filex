<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'expenses';
    
    /**
    * Run the migrations.
    * @table expenses
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->double('amount')->nullable();
            $table->text('details')->nullable();
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('safe_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->index(["entry_id"], 'fk_expenses_entries_idx1');
            $table->index(["safe_id"], 'fk_expenses_safes_idx1');
            $table->index(["user_id"], 'fk_expenses_users_idx1');
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->index(["bill_id"], 'fk_expenses_bills_idx1');
            $table->index(["invoice_id"], 'fk_expenses_invoices_idx1');
            $table->timestamps();


            $table->foreign('bill_id', 'fk_expenses_bills_idx1')
                ->references('id')->on('bills')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->foreign('invoice_id', 'fk_expenses_invoices_idx1')
                ->references('id')->on('invoices')
                ->onDelete('no action')
                ->onUpdate('no action');
            
            
            $table->foreign('safe_id', 'fk_expenses_safes_idx1')
            ->references('id')->on('safes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            
            $table->foreign('entry_id', 'fk_expenses_entries_idx1')
            ->references('id')->on('entries')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('user_id', 'fk_expenses_users_idx1')
            ->references('id')->on('users')
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
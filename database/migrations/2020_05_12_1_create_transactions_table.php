<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'transactions';
    
    /**
    * Run the migrations.
    * @table transactions
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('type')->default('0');
            $table->string('month');
            $table->string('details')->nullable();
            $table->double('amount');
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('entry_id')->nullable();
            $table->unsignedInteger('safe_id')->nullable();
            
            $table->index(["entry_id"], 'fk_transactions_entries_idx');
            $table->index(["safe_id"], 'fk_transactions_safes_idx');
            $table->index(["user_id"], 'fk_transactions_users_idx');
            $table->index(["employee_id"], 'fk_transactions_employees_idx');
            $table->timestamps();
            
            
            $table->foreign('safe_id', 'fk_transactions_safes_idx')
            ->references('id')->on('safes')
            ->onDelete('set null')
            ->onUpdate('set null');
            
            $table->foreign('entry_id', 'fk_transactions_entries_idx')
            ->references('id')->on('entries')
            ->onDelete('set null')
            ->onUpdate('set null');
            
            $table->foreign('employee_id', 'fk_transactions_employees_idx')
            ->references('id')->on('employees')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('user_id', 'fk_transactions_users_idx')
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
        Schema::dropIfExists($this->tableName);
    }
}
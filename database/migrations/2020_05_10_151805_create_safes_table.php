<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSafesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'safes';
    
    /**
    * Run the migrations.
    * @table customers
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name');
            $table->boolean('show_bills')->default(0);
            $table->boolean('show_invoices')->default(0);
            $table->boolean('show_expenses')->default(0);
            $table->boolean('show_cheques')->default(0);
            $table->double('opening_balance')->nullable()->default(0);
            $table->tinyInteger('type');
            $table->unsignedInteger('account_id');
            
            $table->index(["account_id"], 'safes_accounts1_idx');
            $table->timestamps();
            
            
            $table->foreign('account_id', 'safes_accounts1_idx')
            ->references('id')->on('accounts')
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
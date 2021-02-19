<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'accounts';
    
    /**
    * Run the migrations.
    * @table accounts
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name', 45)->nullable();
            $table->unsignedInteger('group_id')->nullable();
            $table->unsignedInteger('accountable_id')->nullable();
            $table->string("accountable_type")->nullable();
            
            $table->index(["group_id"], 'fk_accounts_groups1_idx');
            $table->timestamps();
            
            
            $table->foreign('group_id', 'fk_accounts_groups1_idx')
            ->references('id')->on('groups')
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
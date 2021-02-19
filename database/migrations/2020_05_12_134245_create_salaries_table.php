<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'salaries';
    
    /**
    * Run the migrations.
    * @table salaries
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('month');
            $table->double('total')->default(0);
            $table->double('debts')->default(0);
            $table->double('bonus')->default(0);
            $table->double('deducations')->default(0);
            $table->double('net')->default(0);
            $table->unsignedInteger('employee_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('entry_id')->nullable();
            
            $table->index(["entry_id"], 'salaries_entry_id');
            $table->index(["user_id"], 'salaries_user_id');
            $table->index(["employee_id"], 'salaries_employee_id');
            $table->timestamps();
            
            
            $table->foreign('employee_id', 'salaries_employee_id')
            ->references('id')->on('employees')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('user_id', 'salaries_user_id')
            ->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('entry_id', 'salaries_entry_id')
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
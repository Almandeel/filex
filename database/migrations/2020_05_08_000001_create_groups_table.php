<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'groups';
    
    /**
    * Run the migrations.
    * @table groups
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('name', 45)->nullable();
            $table->unsignedInteger('group_id')->nullable();
            
            $table->index(["group_id"], 'fk_groups_groups1_idx');
            $table->timestamps();
            
            
            $table->foreign('group_id', 'fk_groups_groups1_idx')
            ->references('id')->on('groups')
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
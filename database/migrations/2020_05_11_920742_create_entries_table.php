<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'entries';
    
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            
            $table->increments('id');
            $table->string('details')->nullable();
            $table->double('amount')->nullable()->default('0');
            $table->tinyInteger('type')->default('1');;
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->unsignedInteger('user_id');
            $table->index(["user_id"], 'fk_entries_user_id');
            
            $table->index(["from_id"], 'fk_entries_from_idx');
            $table->index(["to_id"], 'fk_entries_to_idx');
            $table->timestamps();
            
            $table->foreign('from_id', 'fk_entries_from_idx')
            ->references('id')->on('accounts')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('to_id', 'fk_entries_to_idx')
            ->references('id')->on('accounts')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('user_id', 'fk_entries_user_id')
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
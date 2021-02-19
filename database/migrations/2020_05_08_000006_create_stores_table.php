<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'stores';

    /**
     * Run the migrations.
     * @table stores
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->string('name', 145);
            
            $table->unsignedInteger('account_id')->nullable();
            
            $table->index(["account_id"], 'stores_accounts1_idx');
            $table->timestamps();
            
            
            $table->foreign('account_id', 'stores_accounts1_idx')
            ->references('id')->on('accounts')
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

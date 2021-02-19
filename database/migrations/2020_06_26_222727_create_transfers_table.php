<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount');
            $table->string('details')->nullable();
            $table->unsignedInteger('from_id');
            $table->unsignedInteger('to_id');
            $table->unsignedInteger('entry_id');
            $table->unsignedInteger('user_id');
            
            $table->index(["user_id"], 'fk_transfers_users_idx');
            $table->index(["from_id"], 'fk_transfers_from_idx');
            $table->index(["to_id"], 'fk_transfers_to_idx');
            $table->index(["entry_id"], 'fk_transfers_entries_idx1');
            $table->timestamps();
            
            $table->foreign('entry_id', 'fk_transfers_entries_idx1')
            ->references('id')->on('entries')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('from_id', 'fk_transfers_from_idx')
            ->references('id')->on('safes')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('to_id', 'fk_transfers_to_idx')
            ->references('id')->on('safes')
            ->onDelete('no action')
            ->onUpdate('no action');
            
            $table->foreign('user_id', 'fk_transfers_users_idx')
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
        Schema::dropIfExists('transfers');
    }
}
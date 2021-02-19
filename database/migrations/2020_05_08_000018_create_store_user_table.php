<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreUserTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'store_user';

    /**
     * Run the migrations.
     * @table store_user
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('store_id');

            $table->index(["store_id"], 'fk_users_has_stores_stores1_idx');

            $table->index(["user_id"], 'fk_users_has_stores_users1_idx');
            $table->timestamps();


            $table->foreign('user_id', 'fk_users_has_stores_users1_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('store_id', 'fk_users_has_stores_stores1_idx')
                ->references('id')->on('stores')
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

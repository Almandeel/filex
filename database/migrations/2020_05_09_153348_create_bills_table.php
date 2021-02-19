<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bills';

    /**
     * Run the migrations.
     * @table bills
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
             
            $table->increments('id');
            $table->string('number')->nullable();
            $table->double('amount')->default(0);
            $table->double('payed')->default(0);
            $table->double('remain')->default(0);
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('user_id');

            $table->index(["supplier_id"], 'fk_bills_suppliers1_idx');
            $table->index(["store_id"], 'fk_bills_stores1_idx');
            $table->index(["user_id"], 'fk_bills_users_idx');
            $table->index(["bill_id"], 'fk_bill_bills_idx');
            $table->timestamps();


            $table->foreign('bill_id', 'fk_bill_bills_idx')
                ->references('id')->on('bills')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('user_id', 'fk_bills_users_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('store_id', 'fk_bills_stores1_idx')
                ->references('id')->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('supplier_id', 'fk_bills_suppliers1_idx')
                ->references('id')->on('suppliers')
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

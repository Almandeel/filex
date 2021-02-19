<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
    * Schema table name to migrate
    * @var string
    */
    public $tableName = 'invoices';
    
    /**
    * Run the migrations.
    * @table invoices
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
            $table->unsignedInteger('invoice_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('bill_id')->nullable();
            $table->unsignedInteger('store_id')->nullable();
            $table->unsignedInteger('user_id');
            
            $table->index(["invoice_id"], 'fk_invoices_invoices1_idx');
            
            $table->index(["customer_id"], 'fk_invoices_customers1_idx');
            $table->index(["bill_id"], 'fk_invoices_bills1_idx');
            $table->index(["store_id"], 'fk_invoices_stores1_idx');
            $table->index(["user_id"], 'fk_invoices_users_idx');
            $table->timestamps();


            $table->foreign('user_id', 'fk_invoices_users_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('bill_id', 'fk_invoices_bills1_idx')
                ->references('id')->on('bills')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('store_id', 'fk_invoices_stores1_idx')
                ->references('id')->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            
            $table->foreign('invoice_id', 'fk_invoices_invoices1_idx')
            ->references('id')->on('invoices')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->foreign('customer_id', 'fk_invoices_customers1_idx')
            ->references('id')->on('customers')
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
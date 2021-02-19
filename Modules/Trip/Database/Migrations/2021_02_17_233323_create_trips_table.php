<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->unsignedBigInteger('car_id');
            $table->string('amount');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('from')
            ->references('id')->on('states')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('to')
            ->references('id')->on('states')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('car_id')
            ->references('id')->on('cars')
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
        Schema::dropIfExists('trips');
    }
}

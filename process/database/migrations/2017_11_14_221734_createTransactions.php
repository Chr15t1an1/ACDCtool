<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::create('transactions', function (Blueprint $table) {
          $table->increments('id');
          $table->string('isDone');
          $table->string('publicPath')->nullable();
          //$table->string('isDone');
          $table->timestamps();
      });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('transactions');
    }
}

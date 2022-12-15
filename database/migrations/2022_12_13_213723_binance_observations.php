<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binance_observations', function (Blueprint $table) {
            $table->string('symbol', 12);
            $table->float('price', 24, 12);
            $table->dateTime('session_time');
        
            $table->index(['symbol']);
            $table->index(['session_time']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('binance_observations');
    }
};

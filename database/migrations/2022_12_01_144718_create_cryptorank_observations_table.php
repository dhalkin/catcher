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
        Schema::create('cryptorank_observations', function (Blueprint $table) {
            $table->id();
            $table->integer('cryptorank_id');
            $table->dateTime('sessionTime');
            $table->dateTime('lastUpdated');
            $table->string('name', 96);
            $table->string('symbol', 96);
            $table->string('type', 96)->nullable();
    
            $table->bigInteger('circulatingSupply')->nullable();
            $table->bigInteger('totalSupply')->nullable();
            
            $table->float('price', 24, 12)->nullable();
            $table->bigInteger('volume24h')->nullable();
            $table->float('percentChange24h',8, 4)->nullable();
            
            $table->integer('created_at')->unsigned();
    
            $table->index(['symbol']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cryptorank_observations');
    }
};

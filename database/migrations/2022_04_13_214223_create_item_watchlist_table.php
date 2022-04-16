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
        Schema::create('item_watchlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('watchlist_id')->references('id')->on('watchlists');
            $table->integer('item_order');
            $table->foreignId('item_id')->references('id')->on('items');
            $table->double('rating');
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
        Schema::dropIfExists('watchlist_items');
    }
};

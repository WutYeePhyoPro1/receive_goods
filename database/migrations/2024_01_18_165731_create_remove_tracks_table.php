<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remove_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('received_goods_id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->integer('remove_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remove_tracks');
    }
};

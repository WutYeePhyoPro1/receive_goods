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
        Schema::create('add_product_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('authorize_user');
            $table->integer('by_user');
            $table->integer('truck_id');
            $table->integer('added_qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_product_tracks');
    }
};

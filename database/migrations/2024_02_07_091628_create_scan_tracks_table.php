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
        Schema::create('scan_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_info_id');
            $table->integer('product_id');
            $table->integer('user_id');
            $table->string('unit',5);
            $table->integer('per');
            $table->integer('count');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_tracks');
    }
};

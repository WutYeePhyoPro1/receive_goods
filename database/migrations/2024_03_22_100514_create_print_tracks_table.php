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
        Schema::create('print_tracks', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('by_user');
            $table->integer('quantity');
            $table->string('bar_type',56);
            $table->string('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_tracks');
}
};

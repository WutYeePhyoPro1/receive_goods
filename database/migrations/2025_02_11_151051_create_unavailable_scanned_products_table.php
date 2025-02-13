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
        Schema::create('unavailable_scanned_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('received_goods_id');
            $table->string('ip_address')->nullable();
            $table->string('scanned_barcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unavailable_scanned_products');
    }
};

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
        Schema::create('r008_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('r008_document_id');
            $table->string('product_code');
            $table->string('product_name');
            $table->double('gr_qty'); // ref from po
            $table->double('physical_qty');
            $table->double('diff');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r008_products');
    }
};

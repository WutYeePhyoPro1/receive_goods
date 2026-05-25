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
        Schema::create('receive_good_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receive_good_document_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_code');
            $table->string('product_name');
            $table->string('unit')->default('PC');
            $table->double('po_qty');
            $table->double('gr_qty');
            $table->float('price')->nullable();
            $table->float('amount')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_good_products');
    }
};

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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id');
            $table->string('bar_code');
            $table->string('supplier_name')->nullable();
            $table->double('qty');
            $table->double('scanned_qty')->nullable();
            $table->string('remark')->nullable();
            $table->string('unit')->default('PC');
            $table->float('price')->nullable();
            $table->float('amount')->nullable();
            $table->double('rg_pulled_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};

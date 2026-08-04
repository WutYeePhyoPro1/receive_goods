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
        Schema::create('product_unit_backfills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->unique();
            $table->unsignedBigInteger('document_id');
            $table->string('document_no');
            $table->string('outbound')->nullable();
            $table->string('bar_code');
            $table->string('old_unit')->nullable();
            $table->string('new_unit')->nullable();
            $table->string('source')->nullable();
            $table->timestamp('backfilled_at')->nullable();
            $table->timestamps();

            $table->index(['document_no', 'bar_code']);
            $table->index('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_unit_backfills');
    }
};

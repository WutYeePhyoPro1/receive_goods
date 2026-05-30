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
        Schema::create('r008_documents', function (Blueprint $table) {
            $table->id();
            $table->date('document_date');
            $table->string('product_type');
            $table->string('rg_no');
            $table->string('vendor_code');
            $table->string('truck_container_no')->nullable();
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r008_documents');
    }
};

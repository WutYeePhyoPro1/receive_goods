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
        Schema::create('receive_good_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->string('vendor_code');
            $table->string('po_no');
            $table->unsignedBigInteger('branch_id');
            $table->string('delivery_note');
            $table->date("delivery_date");
            $table->string('ship_by',20);
            $table->string('receive_type',20);
            $table->boolean('r008');
            $table->float('total_amount');
            $table->string('rg_no')->nullable();
            $table->string('r008_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_good_documents');
    }
};

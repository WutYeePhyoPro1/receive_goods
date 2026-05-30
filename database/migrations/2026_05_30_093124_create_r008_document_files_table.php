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
        Schema::create('r008_document_files', function (Blueprint $table) {
            $table->id();
            $table->integer('r008_document_id')->unsigned();
            $table->string('file')->nullable();
            $table->string('name')->nullable();
            $table->longText('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r008_document_files');
    }
};

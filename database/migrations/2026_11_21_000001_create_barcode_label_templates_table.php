<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barcode_label_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->json('layout');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barcode_label_templates');
    }
};

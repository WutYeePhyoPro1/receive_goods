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
        Schema::create('goods_receives', function (Blueprint $table) {
            $table->id();
            $table->string('document_no');
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('status')->nullable();
            $table->string('user_id');
            $table->string('edit_user')->nullable();
            $table->time('duration')->nullable();
            $table->datetime('edit_start_time')->nullable();
            $table->time('edit_duration')->nullable();
            $table->integer('remaining_qty')->nullable();
            $table->integer('exceed_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receives');
    }
};

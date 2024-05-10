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
        Schema::create('driver_infos', function (Blueprint $table) {
            $table->id();
            $table->string('ph_no')->nullable();
            $table->string('type_truck',56)->nullable();
            $table->integer('received_goods_id');
            $table->string('driver_name');
            $table->string('truck_no')->nullable();
            $table->string('nrc_no')->nullable();
            $table->double('scanned_goods')->nullable();
            $table->date('start_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('duration')->nullable();
            $table->integer('user_id');
            $table->integer('gate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_infos');
    }
};

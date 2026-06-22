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
        Schema::create('receive_good_rejects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('receive_good_document_id');
            $table->unsignedBigInteger('branch_id');
            $table->text('remark');
            $table->string("image")->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('approved_user_id')->nullable();
            $table->dateTime('approved_datetime')->nullable();
            $table->string('status')->nullable()->default('Pending Mgr Review');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receive_good_rejects');
    }
};

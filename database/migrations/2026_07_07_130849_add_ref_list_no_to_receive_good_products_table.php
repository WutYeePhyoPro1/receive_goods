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
        Schema::table('receive_good_products', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_list_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_good_products', function (Blueprint $table) {
            $table->dropColumn("ref_list_no");
        });
    }
};

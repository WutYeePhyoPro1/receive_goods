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
            $table->float('discount')->nullable()->default(0);
            $table->double('r8damqty')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receive_good_products', function (Blueprint $table) {
            $table->dropColumn("discount");
            $table->dropColumn("r8damqty");
        });
    }
};

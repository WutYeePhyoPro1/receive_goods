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
        Schema::table('documents', function (Blueprint $table) {
            $table->integer("creditday")->nullable()->default(0);
            $table->date("purchasedate")->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_code')->nullable();
            $table->float('total_amount')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn("creditday");
            $table->dropColumn("purchasedate");
            $table->dropColumn("vendor_name");
            $table->dropColumn("vendor_code");
            $table->dropColumn("total_amount");
            $table->dropColumn("status");
        });
    }
};

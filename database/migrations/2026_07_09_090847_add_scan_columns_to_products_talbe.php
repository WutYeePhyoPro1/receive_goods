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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'scann_count')) {
                $table->integer('scann_count')->nullable();
            }

            if (!Schema::hasColumn('products', 'scann_pause')) {
                $table->integer('scann_pause')->nullable();
            }

            if (!Schema::hasColumn('products', 'not_scan_remark')) {
                $table->string('not_scan_remark')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn("scann_count");
            $table->dropColumn("scann_pause");
            $table->dropColumn("not_scan_remark");
        });
    }
};

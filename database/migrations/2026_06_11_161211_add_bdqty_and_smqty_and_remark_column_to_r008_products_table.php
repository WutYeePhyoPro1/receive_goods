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
        Schema::table('r008_products', function (Blueprint $table) {
            $table->double('bdqty')->nullable()->default(0);
            $table->double('sdqty')->nullable()->default(0);
            $table->string('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('r008_products', function (Blueprint $table) {
            $table->dropColumn("bdqty");
            $table->dropColumn("sdqty");
            $table->dropColumn("remark");
        });
    }
};

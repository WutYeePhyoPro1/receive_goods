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
            $table->string('employeecode')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('emp_comname')->nullable();
            $table->timestamp('time_emp')->nullable();

            $table->string('approvecode')->nullable();
            $table->string('approve_name')->nullable();
            $table->string('approve_comname')->nullable();
            $table->timestamp('time_approve')->nullable();

            $table->string('check_emp')->nullable();
            $table->string('check_name')->nullable();
            $table->string('chekc_comname')->nullable();
            $table->timestamp('time_check')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn("employeecode");
            $table->dropColumn("employee_name");
            $table->dropColumn("emp_comname");
            $table->dropColumn("time_emp");

            $table->dropColumn("approvecode");
            $table->dropColumn("approve_name");
            $table->dropColumn("approve_comname");
            $table->dropColumn("time_approve");

            $table->dropColumn("check_emp");
            $table->dropColumn("check_name");
            $table->dropColumn("chekc_comname");
            $table->dropColumn("time_check");
        });
    }
};

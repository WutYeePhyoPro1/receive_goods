<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('change_log_id');
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('change_type_id');
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_changes');
    }
}

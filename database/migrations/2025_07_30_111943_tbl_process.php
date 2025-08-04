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
        Schema::create('tbl_process', function (Blueprint $table) {
            $table->id();
            $table->string('part_name');
            $table->string('process_name');
            $table->integer('amount');
            $table->integer('officer_amount');
            $table->integer('total_time');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_process');
    }
};

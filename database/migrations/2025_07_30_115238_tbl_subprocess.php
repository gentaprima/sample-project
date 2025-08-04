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
        Schema::create('tbl_subprocess', function (Blueprint $table) {
            $table->id();
            $table->string('subprocess_name');
            $table->string('material_name');
            $table->string('officer_name');
            $table->integer('processing_time'); // atau gunakan integer jika ingin menyimpan dalam menit
            $table->string('group_process');
            $table->string('material_results');
            $table->unsignedBigInteger('id_process');
            $table->string('status_subprocess');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_process')
                  ->references('id')
                  ->on('tbl_process')
                  ->onDelete('cascade'); // otomatis hapus subproses jika proses utama dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_subprocess');
    }
};

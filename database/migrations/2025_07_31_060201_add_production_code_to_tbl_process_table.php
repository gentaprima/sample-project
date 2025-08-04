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
        Schema::table('tbl_process', function (Blueprint $table) {
            $table->string('production_code')->after('id')->nullable(); // tambahkan nullable jika tidak ingin isi langsung
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_process', function (Blueprint $table) {
            $table->dropColumn('production_code');
        });
    }
};

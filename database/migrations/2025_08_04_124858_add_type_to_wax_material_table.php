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
        Schema::table('wax_material', function (Blueprint $table) {
            $table->string('type')->nullable()->after('nama_material');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wax_material', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

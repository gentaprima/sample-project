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
        Schema::create('mould', function (Blueprint $table) {
            $table->id();
            $table->string('mould_name');
            $table->integer('stock')->default(0);
    
            // Relasi ke wax_material
            $table->unsignedBigInteger('id_wax_material');
            $table->foreign('id_wax_material')
                  ->references('id')->on('wax_material')
                  ->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mould');
    }
};

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
    Schema::create('casetas', function (Blueprint $table) {
        $table->id();
        
        // Datos de Identificación
        $table->string('nombre'); // Ej: "Caseta Norte"
        $table->string('codigo_unico')->unique(); // Ej: "CST-001"
        $table->string('ubicacion')->nullable(); // Dirección física
        
        // Estado (Para saber si está operativa)
        $table->boolean('activa')->default(true); 

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casetas');
    }
};

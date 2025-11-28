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
        Schema::create('recojos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('preventa_id')->constrained('preventas')->onDelete('cascade');
            
            $table->date('fecha_programada');
            $table->string('placa_camion', 20);
            $table->string('nombre_chofer');
            
            // --- NUEVOS CAMPOS LOGÍSTICOS ---
            $table->decimal('distancia_km', 8, 2)->nullable(); // Ej: 45.50 km
            $table->string('tiempo_estimado')->nullable();     // Ej: "1h 20min"
            
            $table->string('estado')->default('pendiente'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recojos');
    }
};

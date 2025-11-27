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
    Schema::create('analisis_calidad', function (Blueprint $table) {
        $table->id();

        // 1. Relación: ¿Qué trato estamos evaluando?
        // Usamos preventa_id porque ahí nace el trato
        $table->foreignId('preventa_id')->constrained('preventas');

        // 2. Relación: ¿Qué operario hizo el análisis?
        $table->foreignId('user_caseta_id')->constrained('users');
        
        // 3. Relación: ¿En qué caseta física se hizo?
        $table->foreignId('caseta_id')->constrained('casetas');

        // 4. Los Datos Reales (Lo que sale de la máquina)
        $table->decimal('peso_real_sacos', 10, 2); // Peso total en Kilos
        $table->integer('cantidad_sacos_real');    // Conteo físico
        $table->decimal('humedad_real', 5, 2);     // Ej: 13.50
        $table->decimal('quebrado_real', 5, 2);    // Ej: 5.00
        $table->decimal('impurezas_real', 5, 2);   // Ej: 2.00
        
        // 5. Extras
        $table->text('observaciones')->nullable();
        $table->string('foto_ticket_balanza')->nullable(); // Para subir foto si deseas luego

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_calidad');
    }
};

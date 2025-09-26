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
        Schema::create('preventas', function (Blueprint $table) {
            $table->id();

            // --- CLAVE FORÁNEA PARA EL AGRICULTOR ---
            // Conecta esta preventa con el usuario que la creó.
            $table->foreignId('user_id')->constrained('users');

            // --- DATOS DE LA OFERTA ---
            $table->integer('cantidad_sacos');
            $table->decimal('precio_por_saco', 8, 2); // 8 dígitos en total, 2 para decimales
            $table->decimal('humedad', 5, 2); // Ej. 14.50 %
            $table->decimal('quebrado', 5, 2); // Ej. 15.00 %
            $table->text('notas')->nullable(); // Notas adicionales opcionales
            $table->string('estado')->default('activa'); // 'activa', 'acordada', 'finalizada', 'cancelada'

            $table->timestamps(); // Crea las columnas created_at y updated_at
            $table->softDeletes(); // ¡Importante! Añade la columna para el borrado lógico
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventas');
    }
};

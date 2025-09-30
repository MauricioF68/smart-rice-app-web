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
        Schema::create('campana_lote', function (Blueprint $table) {
            $table->id();

            // --- Conexiones ---
            $table->foreignId('campana_id')->constrained('campanas')->onDelete('cascade');
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // El agricultor que aplica

            // --- Datos del acuerdo ---
            $table->integer('cantidad_comprometida');
            $table->string('estado')->default('pendiente_aprobacion'); // 'pendiente_aprobacion', 'aprobado', 'rechazado'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campana_lote');
    }
};

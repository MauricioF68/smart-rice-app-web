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
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();

            // --- CLAVE FORÁNEA PARA EL MOLINO ---
            $table->foreignId('user_id')->constrained('users');

            // --- DATOS DE LA CAMPAÑA ---
            $table->string('nombre_campana');
            $table->string('tipo_arroz')->nullable();
            $table->integer('cantidad_total');
            $table->integer('cantidad_acordada')->default(0); // Inicia en 0
            $table->decimal('precio_base', 8, 2);
            $table->string('estado')->default('activa'); // 'activa', 'completada', 'cancelada'

            // --- REGLAS DE CALIDAD (RANGOS) ---
            $table->decimal('humedad_min', 5, 2)->nullable();
            $table->decimal('humedad_max', 5, 2)->nullable();
            $table->decimal('quebrado_min', 5, 2)->nullable();
            $table->decimal('quebrado_max', 5, 2)->nullable();

            // --- REGLAS POR AGRICULTOR ---
            $table->integer('min_sacos_por_agricultor')->nullable();
            $table->integer('max_sacos_por_agricultor')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campanas');
    }
};

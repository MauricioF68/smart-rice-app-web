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
        Schema::create('cuentas_bancarias', function (Blueprint $table) {
            $table->id();

            // Llave foránea para vincularla al agricultor
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Datos del Banco (como propusiste)
            $table->string('banco_nombre'); // BCP, Interbank, etc.
            $table->string('tipo_cuenta'); // Ahorros, Corriente

            // Datos del Titular (como propusiste)
            $table->string('titular_nombres');
            $table->string('titular_apellidos');

            // Números de Cuenta (como propusiste)
            $table->string('numero_cuenta');
            $table->string('cci')->nullable(); // El CCI puede ser opcional

            // Para marcar una cuenta como la principal
            $table->boolean('is_primary')->default(false);

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};

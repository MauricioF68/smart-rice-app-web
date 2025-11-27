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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            // 1. Relación: ¿Qué trato se está pagando?
            $table->foreignId('preventa_id')->constrained('preventas')->onDelete('cascade');

            // 2. Relación: ¿Quién está pagando? (El Molino)
            $table->foreignId('user_id')->constrained('users'); // Usuario que registra el pago

            // 3. Detalles de la Transferencia
            $table->decimal('monto_total', 10, 2); // Ej: 15000.00
            $table->string('banco_origen');        // Ej: BCP, Interbank
            $table->string('numero_operacion');    // El código único del voucher
            $table->timestamp('fecha_pago');       // Cuándo se hizo

            // 4. Evidencia
            $table->string('foto_voucher');        // Ruta de la imagen

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preventas', function (Blueprint $table) {
            // Añadimos la columna para la llave foránea
            $table->foreignId('lote_id')->nullable()->constrained('lotes')->after('user_id');

            // Hacemos que los campos de calidad sean opcionales, ya que ahora vendrán del lote
            $table->decimal('cantidad_sacos', 8, 2)->nullable()->change();
            $table->decimal('humedad', 5, 2)->nullable()->change();
            $table->decimal('quebrado', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preventas', function (Blueprint $table) {
            //
        });
    }
};

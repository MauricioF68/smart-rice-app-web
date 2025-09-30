<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            // Añadimos la nueva columna después de 'user_id'
            $table->foreignId('tipo_arroz_id')
                ->nullable()
                ->after('user_id')
                ->constrained('tipos_arroz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lotes', function (Blueprint $table) {
            $table->dropForeign(['tipo_arroz_id']);
            $table->dropColumn('tipo_arroz_id');
        });
    }
};

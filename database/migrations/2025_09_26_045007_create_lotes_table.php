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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            // Foreign key to the farmer (user) who owns this lot
            $table->foreignId('user_id')->constrained('users');

            // You could also link to a specific 'hectarea' if you create that module later
            // $table->foreignId('hectarea_id')->constrained('hectareas');

            $table->string('nombre_lote'); // e.g., "Cosecha Principal 2025"
            $table->integer('cantidad_total_sacos');
            $table->integer('cantidad_disponible_sacos'); // This will decrease as the farmer makes deals

            // Farmer's self-assessed quality parameters
            $table->decimal('humedad', 5, 2);
            $table->decimal('quebrado', 5, 2);

            $table->string('estado')->default('disponible'); // 'disponible', 'agotado'

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};

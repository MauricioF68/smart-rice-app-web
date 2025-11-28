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
            
            $table->foreignId('user_id')->constrained('users');

            $table->string('nombre_lote'); 
            $table->integer('cantidad_total_sacos');
            $table->integer('cantidad_disponible_sacos'); 
            
            $table->decimal('humedad', 5, 2);
            $table->decimal('quebrado', 5, 2);

            $table->decimal('latitud', 10, 8)->nullable(); 
            $table->decimal('longitud', 11, 8)->nullable();
            $table->string('referencia_ubicacion')->nullable();

            $table->string('estado')->default('disponible'); 

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

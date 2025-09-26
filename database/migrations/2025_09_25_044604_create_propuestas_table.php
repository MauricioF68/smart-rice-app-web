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
    Schema::create('propuestas', function (Blueprint $table) {
        $table->id();

        // --- CLAVES FORÁNEAS ---
        // Conecta la propuesta con la preventa a la que responde.
        $table->foreignId('preventa_id')->constrained('preventas')->onDelete('cascade');
        
        // Conecta la propuesta con el usuario (Molino) que la hace.
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // --- DATOS DE LA PROPUESTA ---
        // Guardamos los datos de la contrapropuesta. Si es una aceptación directa,
        // estos campos podrían guardar los mismos valores de la preventa original.
        $table->integer('cantidad_sacos_propuesta');
        $table->decimal('precio_por_saco_propuesta', 8, 2);
        $table->string('estado')->default('enviada'); // 'enviada', 'aceptada', 'rechazada'
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propuestas');
    }
};

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
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->id();

            $table->decimal('monto', 12, 2);
            $table->enum('tipo', ['entrada', 'salida']);
            $table->string('motivo')->nullable();
            $table->timestamp('fecha')->useCurrent();

            // Relación polimórfica al origen del movimiento
            $table->nullableMorphs('origen'); // crea origen_id y origen_type, pero permite NULL
            $table->foreignId('usuario_id')->constrained('users');

            // Nuevo campo para controlar cancelaciones
            $table->boolean('activo')->default(true); // 1 = activo, 0 = cancelado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_movimientos');
    }
};

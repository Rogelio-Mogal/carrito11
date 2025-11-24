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
        Schema::create('caja_turnos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');

            $table->integer('turno'); // número de turno dentro del día
            $table->decimal('efectivo_inicial', 10, 2);

            $table->decimal('efectivo_calculado', 10, 2)->default(0); // calculado con movimientos
            $table->decimal('efectivo_real', 10, 2)->nullable(); // contado al cierre
            $table->decimal('diferencia', 10, 2)->nullable(); // real - calculado

            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();

            $table->enum('estado', ['abierto', 'cerrado'])->default('abierto');

            $table->json('detalle_calculo')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_turnos');
    }
};

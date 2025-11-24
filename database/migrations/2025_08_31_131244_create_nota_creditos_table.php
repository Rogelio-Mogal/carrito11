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
        Schema::create('nota_creditos', function (Blueprint $table) {
            $table->id();
            
            $table->string('folio')->unique();
            $table->morphs('notable'); // notable_id y notable_type
            $table->unsignedBigInteger('cliente_id');
            $table->decimal('monto', 10, 2);
            $table->string('motivo')->nullable();
            // Tipo de nota: DEVOLUCION, GARANTIA, CANCELACION, OTRO
            $table->string('tipo')->default('CANCELACION','DEVOLUCION','GARANTIA');
            $table->enum('estado', ['PENDIENTE', 'APLICADA', 'DEVUELTO'])->default('PENDIENTE');
             // Estado de la nota (activa, anulada)
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_creditos');
    }
};

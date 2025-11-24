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
        Schema::create('venta_devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('venta_detalle_id')->nullable()->constrained('venta_detalles')->onDelete('cascade');
            $table->foreignId('nota_credito_id')->nullable()->constrained('nota_creditos')->onDelete('set null');

             $table->foreignId('venta_aplicada_id')->nullable()->constrained('ventas')->onDelete('set null'); //id de la venta en la cual se utilizó la nota de venta

            $table->integer('cantidad')->nullable(); // piezas devueltas
            $table->decimal('monto', 12, 2); // subtotal devuelto
            $table->string('motivo')->nullable(); // opcional, ejemplo: defecto, cancelación, etc.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_devoluciones');
    }
};

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
        Schema::create('venta_creditos', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
             $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade'); 
             
            $table->decimal('monto_credito', 12, 2); // Monto inicial a crédito
            $table->decimal('saldo_actual', 12, 2); // Cuánto falta por pagar
            $table->boolean('liquidado')->default(false); // true cuando saldo_actual llega a 0
            $table->boolean('activo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_creditos');
    }
};

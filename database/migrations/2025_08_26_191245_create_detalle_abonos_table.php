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
        Schema::create('detalle_abonos', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('abono_id')->constrained('abonos')->onDelete('cascade');

            // Relación opcional con venta_creditos
            $table->foreignId('venta_credito_id')->nullable()->constrained('venta_creditos')->onDelete('cascade');

            // Polimórfico: puede ser venta, apartado o anticipo
            $table->unsignedBigInteger('abonado_a_id');
            $table->string('abonado_a_type');

            $table->decimal('monto_antes', 12, 2);  // Cuánto debía antes del abono
            $table->decimal('abonado', 12, 2);      // Cuánto abonó en esta operación
            $table->decimal('saldo_despues', 12, 2);// Cuánto debe después del abono
            $table->boolean('activo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_abonos');
    }
};

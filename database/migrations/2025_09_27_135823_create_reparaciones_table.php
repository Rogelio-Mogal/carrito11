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
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique(); // Folio interno de reparación
            $table->foreignId('cliente_id')->constrained('clientes'); 
            $table->timestamp('fecha_ingreso')->nullable();   // cuando llega al taller
            $table->timestamp('fecha_listo')->nullable();     // cuando se marca como listo
            $table->timestamp('fecha_entregado')->nullable(); // cuando se entrega al cliente
            $table->string('equipo'); // Ejemplo: "Laptop Dell", "Celular Samsung"
            $table->string('tel1')->nullable(); // Ejemplo: "Laptop Dell", "Celular Samsung"
            $table->string('tel2')->nullable(); // Ejemplo: "Laptop Dell", "Celular Samsung"
            $table->text('fallo'); // Descripción del problema
            $table->text('nota_adicional')->nullable();

            $table->foreignId('reparador_id')->nullable()->constrained('users'); // Interno o externo
            $table->enum('estatus', ['taller', 'listo', 'entregado','eliminado'])->default('taller');

            $table->text('solucion')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->text('nota_general')->nullable();

            $table->decimal('costo_servicio', 10, 2)->default(0); // Solo si el reparador es externo

            $table->boolean('finalizada')->default(false); // Marcar cuando se concluya la reparación
            $table->foreignId('venta_id')->nullable()->constrained('ventas'); // Relación opcional con venta

            $table->boolean('activo')->default(true);
            $table->uuid('wci')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reparaciones');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('garantias', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique(); // folio interno o consecutivo
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Cliente
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tel1')->nullable();
            $table->string('tel2')->nullable();

            // Producto recibido en garantía
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('producto_personalizado')->nullable();

            // Información de la venta original
            $table->integer('cantidad');
            $table->decimal('precio_producto', 12, 2);
            $table->decimal('importe', 12, 2);

            // Relación con la venta original (opcional)
            $table->foreignId('venta_id')->nullable()->constrained('ventas')->onDelete('set null');
            $table->string('folio_venta_text')->nullable(); // folio ingresado manualmente si no hay venta_id

            // Descripción del fallo y datos adicionales
            $table->text('descripcion_fallo');
            $table->text('informacion_adicional')->nullable();

            // Solución y nota
            $table->enum('solucion', ['Nota de crédito', 'Cambio físico', 'No procede'])->nullable();
            $table->text('nota_solucion')->nullable();

            // Destino del producto
            $table->enum('destino_producto', ['reasignado', 'baja'])->nullable();
            $table->timestamp('fecha_destino')->nullable();

            // Estatus de la garantía
            $table->enum('estatus', ['pendiente', 'en_revision', 'resuelto'])->default('pendiente');

            // Fechas
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();

            $table->integer('wci');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garantias');
    }
};

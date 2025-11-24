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
        Schema::create('kardex', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('sucursal_id')
            ->constrained('sucursales')
            ->onDelete('cascade');

            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->integer('movimiento_id');
            $table->enum('tipo_movimiento', ['ENTRADA','SALIDA']);
            $table->enum('tipo_detalle', [
                'COMPRA',
                'VENTA',
                'APARTADO',
                'CANCELACION',
                'DEVOLUCION',
                'AJUSTE',
                'INVENTARIO',
                'GARANTIA',
                'SERVICIO'
            ]);
            $table->timestamp('fecha');
            $table->string('folio')->nullable();
            $table->mediumText('descripcion')->nullable();
            $table->integer('debe')->default(0); // debia. Cantidad que se añade al inventario (entradas).
            $table->integer('haber')->default(0); // abona. Cantidad que se resta del inventario (salidas).
            $table->integer('saldo')->default(0); //debe. El saldo actual del inventario después del movimiento.
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardex');
    }
};

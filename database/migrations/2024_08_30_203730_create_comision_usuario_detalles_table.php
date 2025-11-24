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
        Schema::create('comision_usuario_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comision_usuario_id') 
            ->references('id')
            ->on('comision_usuarios')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('venta_id') 
            ->references('id')
            ->on('compras')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('ticket_venta');
            $table->integer('piezas_minimo');
            $table->integer('piezas_vendidas');
            $table->decimal('comision_por_pieza',$precision = 10, $scale = 2);
            $table->decimal('comision_total',$precision = 10, $scale = 2);
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
        Schema::dropIfExists('comision_usuario_detalles');
    }
};

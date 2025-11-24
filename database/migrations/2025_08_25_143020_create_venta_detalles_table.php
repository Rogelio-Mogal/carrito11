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
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_id') 
            ->references('id')
            ->on('ventas')
            ->onUpdate('no action')
            ->onDelete('no action');

            $table->enum('tipo_item', ['PRODUCTO', 'SERVICIO']);

            // Uno u otro según tipo_item
            $table->foreignId('producto_id')->nullable()
                ->constrained('productos')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->text('producto_comun')->nullable();

            $table->integer('cantidad');
            $table->decimal('precio',12,2);
            $table->decimal('total',12,2);
            $table->boolean('activo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};

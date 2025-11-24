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
        Schema::create('producto_numero_series', function (Blueprint $table) {
            $table->id();

            // Producto asociado
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->onUpdate('no action')
                ->onDelete('no action');
            
            // Proveedor y compra (origen)
            $table->foreignId('proveedor_id')->nullable()
            ->constrained('proveedores')
            ->onUpdate('no action')
            ->onDelete('no action');
                
            $table->foreignId('compra_id')->nullable()
            ->constrained('compras')
            ->onUpdate('no action')
            ->onDelete('no action');
            
            // Opcionales: venta, devolución, garantía
            $table->foreignId('venta_id')->nullable()
                ->constrained('ventas')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreignId('nota_credito_id')->nullable()
                ->constrained('nota_creditos')
                ->onUpdate('no action')
                ->onDelete('no action');
                
            $table->foreignId('garantia_id')->nullable()
                ->constrained('garantias')
                ->onUpdate('no action')
                ->onDelete('no action');
            
            $table->string('numero_serie')->unique();
            
            // Indica si está disponible para venta/apartado
            $table->boolean('disponible')->default(true);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_numero_series');
    }
};

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
        Schema::create('compras_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id') 
            ->references('id')
            ->on('compras')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->integer('cantidad');
            $table->decimal('precio',$precision = 10, $scale = 2);
            $table->decimal('importe',$precision = 10, $scale = 2);
            $table->mediumText('nota_compra')->nullable();
            $table->string('tipo_movimiento')->default('ENTRADA');
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
        Schema::dropIfExists('compras_detalles');
    }
};

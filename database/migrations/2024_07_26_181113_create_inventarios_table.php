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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sucursal_id')
            ->constrained('sucursales')
            ->onDelete('cascade');

            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->integer('cantidad');
            $table->integer('producto_apartado')->default(0);
            $table->integer('producto_servicio')->default(0);
            $table->integer('producto_garantia')->default(0);
            $table->decimal('precio_costo',$precision = 10, $scale = 2);
            $table->decimal('precio_anterior',$precision = 10, $scale = 2);
            $table->decimal('precio_publico',$precision = 10, $scale = 2);
            $table->decimal('precio_medio_mayoreo',$precision = 10, $scale = 2);
            $table->decimal('precio_mayoreo',$precision = 10, $scale = 2);
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};

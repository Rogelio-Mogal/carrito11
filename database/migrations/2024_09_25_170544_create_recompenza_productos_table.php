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
        Schema::create('recompenza_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id') 
            ->references('id')
            ->on('clientes')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('recompenza_id') 
            ->references('id')
            ->on('recompenzas')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->timestamp('fecha');
            $table->integer('puntos_utilizados');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recompenza_productos');
    }
};

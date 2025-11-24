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
        Schema::create('asignar_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gasto_id') 
                ->references('id')
                ->on('gastos')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->foreignId('forma_pago_id') 
                ->references('id')
                ->on('forma_pagos')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->date('fecha');
            $table->decimal('monto',$precision = 10, $scale = 2);
            $table->text('nota')->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignar_gastos');
    }
};

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
        Schema::create('tipo_pagos', function (Blueprint $table) {
            $table->id();
            // Relación polimórfica
            $table->morphs('pagable'); // pagable_id + pagable_type
            
            $table->enum('metodo', ['Efectivo', 'TDC', 'TDD', 'Transferencia', 'Nota crédito' ,'Otro']);
            $table->decimal('monto', 12, 3);
            $table->string('referencia')->nullable(); // opcional
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
        //Schema::dropIfExists('tipo_pagos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('forma_pagos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};

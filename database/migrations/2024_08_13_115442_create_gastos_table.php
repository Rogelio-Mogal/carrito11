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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_gasto_id')
                ->constrained('tipo_gastos')
                ->onUpdate('no action')
                ->onDelete('no action');
            $table->string('gasto');
            $table->boolean('activo')->default(1);
            $table->timestamps();

            // único por tipo + nombre + activo
            $table->unique(columns: ['tipo_gasto_id', 'gasto', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};

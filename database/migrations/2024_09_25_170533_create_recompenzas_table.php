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
        Schema::create('recompenzas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id') 
            ->references('id')
            ->on('clientes')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('venta_id') 
            ->references('id')
            ->on('compras')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->timestamp('fecha');
            $table->decimal('debe',$precision = 10, $scale = 2);
            $table->decimal('haber',$precision = 10, $scale = 2);
            $table->decimal('saldo',$precision = 10, $scale = 2);
            $table->enum('tipo', [
                'ACTIVO',
                'RECOMPENZA'
            ]);
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recompenzas');
    }
};

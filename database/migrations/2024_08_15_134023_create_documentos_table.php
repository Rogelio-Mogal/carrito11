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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id') 
            ->references('id')
            ->on('clientes')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->enum('tipo', ['COTIZACIÓN','TICKET ALTERNO', 'NOTA DE VENTA', 'VENTA DE PC']);
            $table->enum('tipo_precio', ['CLIENTE PÚBLICO','CLIENTE MEDIO MAYOREO', 'CLIENTE MAYOREO']);
            $table->string('cliente')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamp('fecha');
            $table->decimal('total',$precision = 10, $scale = 2);
            $table->string('ticket_real')->nullable();
            $table->mediumText('nota_general')->nullable();
            $table->enum('estado', ['CREADO','LISTO']);
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
        Schema::dropIfExists('documentos');
    }
};

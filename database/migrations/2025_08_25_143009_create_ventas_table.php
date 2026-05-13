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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('caja_turno_id')
            ->nullable()
            ->constrained('caja_turnos');

            $table->foreignId('user_id')
            ->references('id')
            ->on('users')
            ->onUpdate('no action')
            ->onDelete('no action');

            $table->foreignId('cliente_id')
            ->references('id')
            ->on('clientes')
            ->onUpdate('no action')
            ->onDelete('no action');

            $table->string('folio')->nullable();
            $table->dateTime('fecha');
            $table->decimal('total',12,2);
            $table->decimal('monto_credito',12,2)->default('0');
            $table->decimal('monto_recibido',7,2)->nullable();
            $table->decimal('cambio',7,2)->nullable();
            $table->enum('tipo_venta', ['CONTADO','CRÉDITO']);

            $table->decimal('subtotal',12,2)->default(0);
            $table->decimal('costo_total',12,2)->default(0);
            $table->decimal('utilidad_total',12,2)->default(0);

            $table->boolean('activo')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};

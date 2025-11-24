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
        Schema::create('anticipo_apartados', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha')->useCurrent();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->enum('tipo', ['ANTICIPO', 'APARTADO']);
            $table->string('folio')->unique();
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('debia', 12, 2)->default(0); // monto original
            $table->decimal('abona', 12, 2)->default(0); // monto abonado
            $table->decimal('debe', 12, 2)->default(0);  // saldo pendiente
            $table->enum('estatus', ['ACTIVO', 'PASO_A_VENTA', 'CANCELADO','LIQUIDADO'])->default('ACTIVO');
            $table->foreignId('venta_id')->nullable()
            ->constrained('ventas')
            ->onDelete('set null'); // o cascade si quieres borrar también el anticipo
            $table->boolean('activo')->default(true);
            $table->uuid('wci')->nullable(); // identificador interno

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anticipo_apartados');
    }
};

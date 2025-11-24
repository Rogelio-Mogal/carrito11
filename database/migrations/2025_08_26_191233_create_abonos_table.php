<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();

            $table->string('folio')->unique(); // consecutivo de abono
            $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Polimórfico para usarlo en ventas, apartados u otros módulos
            $table->unsignedBigInteger('abonable_id');
            $table->string('abonable_type');

            $table->foreignId('cliente_id')->nullable()->constrained('clientes');

            $table->decimal('monto', 12, 2);  // monto total del abono
            $table->decimal('saldo_global_antes', 12, 2)->default(0);  // saldo total del cliente antes del abono (cuánto abonó)
            $table->decimal('saldo_global_despues', 12, 2)->default(0); // saldo total del cliente después del abono (cuánto sigue debiendo)

            $table->string('referencia')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('wci');

            $table->timestamps();
        });

        //$table->string('folio')->nullable();
            //$table->dateTime('fecha');
            //$table->decimal('total_abonado', 12, 2);
            //$table->foreignId('cliente_id')->constrained('clientes');
            //$table->foreignId('user_id')->constrained('users');
            //$table->string('referencia')->nullable();
            //$table->boolean('activo')->default(true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};

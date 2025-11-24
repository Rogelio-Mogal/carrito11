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
        Schema::create('comision_usuarios', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_inicio');
            $table->timestamp('fecha_fin');
            $table->foreignId('usuario_id') 
            ->references('id')
            ->on('users')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->decimal('total',$precision = 10, $scale = 2);
            $table->decimal('venta_mensual',$precision = 10, $scale = 2)->default(0);
            $table->enum('tipo_comision', ['COMISION PRODUCTOS','COMISION MENSUAL']);
            $table->enum('estatus', ['GENERADO','PAGADO'])->default('GENERADO');
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
        Schema::dropIfExists('comision_usuarios');
    }
};

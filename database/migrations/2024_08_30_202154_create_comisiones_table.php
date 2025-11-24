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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->string('folio');
            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->integer('minimo_piezas');
            $table->decimal('comision',$precision = 10, $scale = 2);
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
        Schema::dropIfExists('comisiones');
    }
};

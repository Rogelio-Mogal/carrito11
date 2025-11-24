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
        Schema::create('documento_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documento_id') 
            ->references('id')
            ->on('documentos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->foreignId('producto_id') 
            ->references('id')
            ->on('productos')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('producto_comun')->nullable();
            $table->integer('cantidad');
            $table->decimal('precio',$precision = 10, $scale = 2);
            $table->decimal('precio_publico',$precision = 10, $scale = 2);
            $table->decimal('precio_medio_mayoreo',$precision = 10, $scale = 2);
            $table->decimal('precio_mayoreo',$precision = 10, $scale = 2);
            $table->decimal('importe',$precision = 10, $scale = 2);
            $table->mediumText('nota')->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documento_detalles');
    }
};

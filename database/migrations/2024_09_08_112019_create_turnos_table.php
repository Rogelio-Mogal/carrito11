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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->string('turno');
            $table->timestamp('apertura');
            $table->timestamp('cierre');
            $table->foreignId('usuario_id') 
            ->references('id')
            ->on('users')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->decimal('efectivo_inicial',$precision = 10, $scale = 2);
            $table->enum('tipo', ['APERTURA','CIERRE']);
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};

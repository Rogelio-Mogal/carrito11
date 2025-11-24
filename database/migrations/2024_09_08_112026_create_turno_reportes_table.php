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
        Schema::create('turno_reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id') 
            ->references('id')
            ->on('users')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('turno');
            $table->decimal('total_efectivo',$precision = 10, $scale = 2);
            $table->timestamp('apertura');
            $table->timestamp('cierre');
            $table->decimal('efectivo_real',$precision = 10, $scale = 2);
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turno_reportes');
    }
};

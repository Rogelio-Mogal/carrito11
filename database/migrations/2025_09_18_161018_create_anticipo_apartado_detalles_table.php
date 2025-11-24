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
        Schema::create('anticipo_apartado_detalles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anticipo_apartado_id')->constrained('anticipo_apartados')->onDelete('cascade');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->text('producto_comun')->nullable(); // cuando no hay producto de inventario
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->uuid('wci')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anticipo_apartado_detalles');
    }
};

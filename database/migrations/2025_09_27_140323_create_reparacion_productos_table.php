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
        Schema::create('reparacion_productos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reparacion_id')->constrained('reparaciones')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2); 
            $table->decimal('total', 10, 2);

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
        Schema::dropIfExists('reparacion_productos');
    }
};

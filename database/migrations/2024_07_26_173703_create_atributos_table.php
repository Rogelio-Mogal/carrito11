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
        Schema::create('atributos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ejemplo: "Capacidad", "Tipo USB"
            $table->enum('tipo_campo', ['texto','numero','booleano','select','multiselect']);
            $table->json('opciones')->nullable(); // Ej: ["USB 2.0","USB 3.0","USB 3.1"]
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atributos');
    }
};

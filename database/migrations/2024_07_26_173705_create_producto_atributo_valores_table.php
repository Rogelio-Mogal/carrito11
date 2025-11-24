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
        Schema::create('producto_atributo_valores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('atributo_id');
            $table->string('valor'); // Ejemplo: "16 GB", "USB 3.0", "WiFi"

            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('atributo_id')->references('id')->on('atributos')->onDelete('cascade');

            $table->unique(['producto_id','atributo_id','valor']); // evitar duplicados exactos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_atributo_valores');
    }
};

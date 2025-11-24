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
        Schema::create('familia_atributos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('familia_id');   // id de producto_caracteristicas
            $table->unsignedBigInteger('atributo_id');  // id de atributos

            $table->foreign('familia_id')->references('id')->on('producto_caracteristicas')->onDelete('cascade');
            $table->foreign('atributo_id')->references('id')->on('atributos')->onDelete('cascade');

            $table->unique(['familia_id','atributo_id']); // Para no duplicar

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('familia_atributos');
    }
};

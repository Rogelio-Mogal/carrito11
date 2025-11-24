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
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Ej: Matriz, Sucursal Centro
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->boolean('es_matriz')->default(false); // para marcar la principal
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::table("sucursales")
            ->insert([
                [
                'nombre'      =>  'Matriz Pc Servicios',
                'direccion' => 'Rayon',
                'telefono' => '95125899665',
                'es_matriz'     =>  true,
                'created_at'    =>  '2022-01-01 09:00:00',
                'updated_at'    =>  '2022-01-01 09:00:00'
                ],   
                [
                'nombre'      =>  'Sucursal 1 Pc Servicios',
                'direccion' => 'Centro',
                'telefono' => '9511477474',
                'es_matriz'     =>  false,
                'created_at'    =>  '2022-01-01 09:00:00',
                'updated_at'    =>  '2022-01-01 09:00:00'
                ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};

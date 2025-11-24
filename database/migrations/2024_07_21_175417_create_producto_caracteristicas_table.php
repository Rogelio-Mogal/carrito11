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
        Schema::create('producto_caracteristicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['MARCA','FAMILIA', 'SUB_FAMILIA']);
            $table->string('imagen')->nullable();
            $table->string('img_thumb')->nullable();
            $table->boolean('activo')->default(1);
            $table->timestamps();

            // Definir la combinación única de nombre y tipo
            $table->unique(['nombre', 'tipo']);
        });

        DB::table("producto_caracteristicas")
            ->insert([
                [
                    'nombre'      =>  'PRODUCTO COMUN',
                    'tipo' => 'MARCA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'PRODUCTO COMUN',
                    'tipo' => 'FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'PRODUCTO COMUN',
                    'tipo' => 'SUB_FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'SERVICIOS',
                    'tipo' => 'MARCA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'SERVICIOS',
                    'tipo' => 'FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'HP',
                    'tipo' => 'MARCA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'ACER',
                    'tipo' => 'MARCA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'SONOS',
                    'tipo' => 'MARCA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'SONIDO',
                    'tipo' => 'FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'MONITORES',
                    'tipo' => 'FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'MONITOR GAMER',
                    'tipo' => 'SUB_FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'BOCINA BLUETHOOTH',
                    'tipo' => 'SUB_FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'nombre'      =>  'TECLADO GAMER',
                    'tipo' => 'SUB_FAMILIA',
                    'imagen' => '',
                    'img_thumb' => '',
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_caracteristicas');
    }
};

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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['PRODUCTO','SERVICIO']);
            $table->string('nombre')->unique();
            $table->string('codigo_barra')->unique();

            $table->unsignedBigInteger('marca');
            $table->unsignedBigInteger('familia');
            $table->unsignedBigInteger('sub_familia')->nullable();


            $table->integer('cantidad_minima');

            $table->decimal('precio_publico',$precision = 10, $scale = 2)->nullable();
            $table->decimal('precio_medio_mayoreo',$precision = 10, $scale = 2)->nullable();
            $table->decimal('precio_mayoreo',$precision = 10, $scale = 2)->nullable();

            $table->text('descripcion')->nullable();
            $table->string('garantia')->nullable();
            $table->string('imagen_1')->nullable();
            $table->string('imagen_2')->nullable();
            $table->string('imagen_3')->nullable();
            $table->string('img_thumb')->nullable();
            $table->boolean('is_index')->default(0);
            $table->boolean('serie')->default(0);
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            
            // Relaciones con producto_caracteristicas
            $table->foreign('marca')->references('id')->on('producto_caracteristicas');
            $table->foreign('familia')->references('id')->on('producto_caracteristicas');
            $table->foreign('sub_familia')->references('id')->on('producto_caracteristicas');

            $table->timestamps();

        });

        DB::table("productos")
            ->insert([
                [
                    'tipo'      =>  'PRODUCTO',
                    'nombre' => 'PRODUCTO EN COMÚN',
                    'codigo_barra' => 'producto-comun',
                    'marca'     =>  1,
                    'familia'     =>  2,
                    'cantidad_minima' => 1,
                    'wci'     =>  0,
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
        Schema::dropIfExists('productos');
    }
};

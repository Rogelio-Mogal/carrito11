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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sucursal_id')
            ->constrained('sucursales')
            ->onDelete('cascade');

            $table->foreignId('proveedor_id') 
            ->references('id')
            ->on('proveedores')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->enum('tipo', ['COMPRA_INTERNA','COMPRA_WEB']);
            $table->string('num_factura')->unique();
            $table->timestamp('fecha_compra');
            $table->timestamp('fecha_captura');
            $table->decimal('total',$precision = 10, $scale = 2);
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });

        DB::table("compras")
            ->insert([
                [
                'sucursal_id' =>  1,
                'proveedor_id'      =>  1,
                'tipo' => 'COMPRA_INTERNA',
                'num_factura' => '0000',
                'fecha_compra' => '2022-01-01 09:00:00',
                'fecha_captura' =>  '2022-01-01 09:00:00',
                'total'    =>  0,
                'wci'     =>  0,
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
        Schema::dropIfExists('compras');
    }
};

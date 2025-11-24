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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('proveedor')->unique();
            $table->string('telefono');
            $table->string('correo')->unique();
            $table->integer('wci');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });

        DB::table("proveedores")
            ->insert([
                [
                    'proveedor'      =>  'Proveedor default',
                    'telefono' => '0000000000',
                    'correo' => 'proveedor.default@mail.com',
                    'wci'     =>  0,
                    'created_at'    =>  '2022-01-01 09:00:00',
                    'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                    'proveedor'      =>  'Proveedor 1',
                    'telefono' => '0000000001',
                    'correo' => 'proveedor.uno@mail.com',
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
        Schema::dropIfExists('proveedores');
    }
};

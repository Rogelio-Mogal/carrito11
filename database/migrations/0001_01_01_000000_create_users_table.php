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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sucursal_id')
            ->references('id')
            ->on('sucursales')
            ->onUpdate('no action')
            ->onDelete('no action');
            $table->string('name');
            $table->string('last_name');
            $table->string('full_name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('tipo_usuario')->default('punto_de_venta');
            $table->integer('printer_size')->default(80); // 58, 80
            $table->integer('cliente_id')->unsigned()->nullable();
            $table->boolean('es_reparador')->default(0);
            $table->boolean('es_externo')->default(0);
            $table->string('menu_color')->default('bg-indigo-400');
            $table->string('theme')->default('light');
            $table->boolean('activo')->default(1);
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        /*
        DB::table("users")
            ->insert([
                [
                'sucursal_id' => 1,
                'name'      =>  'Rogelio',
                'last_name' => 'Morales',
                'full_name' => 'Rogelio Morales',
                'email'     =>  'rogelio.mogal@gmail.com',
                'email_verified_at'  =>  '2022-04-27 17:41:46',
                'password'  => '$2y$12$8PgjYaOFebas23mmrgdtzOcs80yGC/dkBh/7mL84S5k2kRGEVaupS',
                'tipo_usuario' => 'punto_de_venta',
                'created_at'    =>  '2022-01-01 09:00:00',
                'updated_at'    =>  '2022-01-01 09:00:00'
                ],
                [
                'sucursal_id' => 1,
                'name'      =>  'Abdiel',
                'last_name' => 'Aguayo',
                'full_name' => 'Abdiel Aguayo',
                'email'     =>  'abdiel.aguayo@gmail.com',
                'email_verified_at'  =>  '2022-04-27 17:41:46',
                'password'  => '$2y$12$.F8JRLQyMD9KlpDDI8MrGeQ0I5uSssKIwF34DHL6LLPJrYG2Ot3/m',
                'tipo_usuario' => 'punto_de_venta',
                'created_at'    =>  '2022-01-01 09:00:00',
                'updated_at'    =>  '2022-01-01 09:00:00'
                ]
        ]);
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

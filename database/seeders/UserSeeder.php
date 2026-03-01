<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'Administrador')->first();
        $ventasRole = Role::where('name', 'Ventas')->first();

        $user1 = User::create([
            'sucursal_id' => 1,
            'name' => 'Rogelio',
            'last_name' => 'Morales',
            'full_name' => 'Rogelio Morales',
            'email' => 'rogelio.mogal@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('madagascar'),
            'tipo_usuario' => 'punto_de_venta',
        ]);

        $user1->assignRole($adminRole); // 👈 Aquí asignas el rol

        $user2 = User::create([
            'sucursal_id' => 1,
            'name' => 'Abdiel',
            'last_name' => 'Aguayo',
            'full_name' => 'Abdiel Aguayo',
            'email' => 'abdiel.aguayo@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('madagascar'),
            'tipo_usuario' => 'punto_de_venta',
        ]);

        $user2->assignRole($ventasRole); // 👈 Rol ventas
    }
}

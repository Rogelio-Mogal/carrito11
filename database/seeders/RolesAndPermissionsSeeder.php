<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 🔹 Limpiar cache de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | DEFINICIÓN DE MÓDULOS Y ACCIONES
        |--------------------------------------------------------------------------
        */

        $modulos = [
                'turnos' => ['ver','crear'],
                'roles' => ['ver','crear','editar','eliminar'],
                'permisos' => ['ver','crear','editar','eliminar'],
                'sucursales' => ['ver','crear','editar','eliminar'],
                'usuarios' => ['ver','crear','editar','eliminar'],
                'ventas' => ['ver','crear','cancelar'],
                'inventarios' => ['ver','crear','editar','eliminar'],
                'forma_pago' => ['ver','crear','editar','eliminar'],
                'tipo_gasto' => ['ver','crear','editar','eliminar'],
                'gastos' => ['ver','crear','editar','eliminar'],
                'asignar_gasto' => ['ver','crear','editar','eliminar'],
                'clientes' => ['ver','crear','editar','cancelar'],
                'venta_credito' => ['ver','crear','editar','cancelar'],
            'nota_credito' => ['ver','imprimir','pasar_venta', 'devolver'],
                'reparacion' => ['ver','crear','editar','eliminar'],
                'garantias' => ['ver','crear','editar','eliminar','solucion'],
                'anticipo' => ['ver','crear','editar','eliminar','abonar'],
                'apartado' => ['ver','crear','editar','eliminar','abonar'],
                'proveedores' => ['ver','crear','editar','eliminar'],
                'producto_caracteristica' => ['ver','crear','editar','eliminar'],
                'atributos' => ['ver','crear','editar','eliminar'],
                'familia_atributos' => ['ver','crear','editar','eliminar'],
                'producto_servicio' => ['ver','crear','editar','eliminar'],
                'precios' => ['ver','crear','editar'],
                'compras' => ['ver','crear','editar','eliminar'],
                'cotizaciones' => ['ver','crear','editar','eliminar'],
                'ticket_alterno' => ['ver','crear','editar','eliminar'],
                'nota_venta' => ['ver','crear','editar','eliminar'],
                'venta_pc_nota' => ['ver','crear','editar','eliminar'],
                'caja_movimeinto' => ['ver','crear','editar','eliminar'],
            'reportes' => ['ver','exportar']
        ];

        /*
        |--------------------------------------------------------------------------
        | CREAR PERMISOS DINÁMICAMENTE
        |--------------------------------------------------------------------------
        */

        foreach ($modulos as $modulo => $acciones) {
            foreach ($acciones as $accion) {

                $nombrePermiso = "$modulo.$accion";

                Permission::firstOrCreate([
                    'name' => $nombrePermiso,
                    'guard_name' => 'web'
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR ROLES
        |--------------------------------------------------------------------------
        */

        // 🔹 ADMIN (todos los permisos)
        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // 🔹 VENTAS
        $vendedor = Role::firstOrCreate(['name' => 'Ventas', 'guard_name' => 'web']);
        $permisosVentas = array_merge(
    $this->permisosModulo('turnos', ['ver','crear']),
            $this->permisosModulo('ventas', ['ver','crear','cancelar']),
            $this->permisosModulo('clientes', ['ver','crear','editar']),
            $this->permisosModulo('venta_credito', ['ver','crear']),
            $this->permisosModulo('nota_credito', ['ver','pasar_venta']),

            $this->permisosModulo('reparacion', ['ver','crear','editar','eliminar']),
            $this->permisosModulo('garantias', ['ver','crear','editar','eliminar']),
            $this->permisosModulo('anticipo', ['ver','crear','editar','eliminar']),
            $this->permisosModulo('apartado', ['ver','crear','editar','eliminar']),
            $this->permisosModulo('cotizaciones', ['ver','crear','editar','eliminar']),
            $this->permisosModulo('ticket_alterno',['ver','crear','editar','eliminar']),
            $this->permisosModulo('nota_venta',['ver','crear','editar','eliminar']),
            $this->permisosModulo('venta_pc_nota',['ver','crear','editar','eliminar']),
            $this->permisosModulo('caja_movimeinto',['ver','crear','editar','eliminar'])

        );

        $vendedor->syncPermissions($permisosVentas);

        // 🔹 COMPRAS
        $compra = Role::firstOrCreate(['name' => 'Compras', 'guard_name' => 'web']);
        $permisosCompras = array_merge(
    $this->permisosModulo('compras', ['ver','crear','editar']),
            $this->permisosModulo('proveedores', ['ver','crear','editar']),
            $this->permisosModulo('producto_servicio', ['ver']),
            $this->permisosModulo('inventarios', ['ver'])
        );

        $compra->syncPermissions($permisosCompras);
    }
    function permisosModulo($modulo, array $acciones)
    {
        return collect($acciones)->map(function ($accion) use ($modulo) {
            return "$modulo.$accion";
        })->toArray();
    }
}

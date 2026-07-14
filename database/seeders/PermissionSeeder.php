<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            'ver horas extra',

            'crear horas extra',

            'editar horas extra',

            'aprobar horas extra',

            'rechazar horas extra',

            'eliminar horas extra',

        ];

        foreach ($permisos as $permiso) {

            Permission::firstOrCreate([
                'name' => $permiso,
                'guard_name' => 'web'
            ]);

        }

        $admin = Role::findByName('Admin');

        $admin->givePermissionTo(Permission::all());

        Role::findByName('Supervisor')
            ->givePermissionTo([
                'ver horas extra',
                'crear horas extra'
            ]);

        Role::findByName('Coordinacion')
            ->givePermissionTo([
                'ver horas extra',
                'aprobar horas extra',
                'rechazar horas extra'
            ]);

        Role::findByName('RH')
            ->givePermissionTo([
                'ver horas extra'
            ]);
    }
}
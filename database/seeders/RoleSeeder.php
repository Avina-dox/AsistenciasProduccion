<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => 'RH',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Supervisor',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Coordinacion',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);
    }
}
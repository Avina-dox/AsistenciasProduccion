<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turno;

class TurnoSeeder extends Seeder
{
    public function run(): void
    {
        Turno::firstOrCreate([
            'nombre' => 'MATUTINO',
        ]);

        Turno::firstOrCreate([
            'nombre' => 'NOCTURNO',
        ]);
    }
}
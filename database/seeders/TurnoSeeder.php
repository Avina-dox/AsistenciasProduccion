<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Turno;

class TurnoSeeder extends Seeder
{
   


public function run(): void
{
    Turno::create([
        'nombre' => 'Diurno',
        'hora_entrada' => '07:00:00',
        'hora_salida' => '19:00:00'
    ]);

    Turno::create([
        'nombre' => 'Nocturno',
        'hora_entrada' => '19:00:00',
        'hora_salida' => '07:00:00'
    ]);
}
}

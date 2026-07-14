<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstatusAsistencia;

class EstatusAsistenciaSeeder extends Seeder
{
    public function run(): void
    {
        $estatus = [
            [
                'codigo' => 'A',
                'nombre' => 'Asistencia',
                'color' => 'green'
            ],
            [
                'codigo' => 'F',
                'nombre' => 'Falta',
                'color' => 'red'
            ],
            [
                'codigo' => 'V',
                'nombre' => 'Vacaciones',
                'color' => 'blue'
            ],
            [
                'codigo' => 'R',
                'nombre' => 'Retardo',
                'color' => 'yellow'
            ],
            [
                'codigo' => 'I',
                'nombre' => 'Incapacidad',
                'color' => 'purple'
            ],
            [
                'codigo' => 'PCG',
                'nombre' => 'Permiso con goce',
                'color' => 'indigo'
            ],
            [
                'codigo' => 'PSG',
                'nombre' => 'Permiso sin goce',
                'color' => 'gray'
            ],
            [
                'codigo' => 'O',
                'nombre' => 'Onomástico',
                'color' => 'pink'
            ],
        ];

        foreach ($estatus as $item) {

            EstatusAsistencia::updateOrCreate(
                ['codigo' => $item['codigo']],
                $item
            );
        }
    }
}

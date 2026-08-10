<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TURNO DIURNO / MATUTINO
        |--------------------------------------------------------------------------
        */

        $horarios = [

            [
                'turno_id' => 1,
                'departamento_id' => 1,
                'hora_entrada' => '09:30:00',
                'hora_salida' => '17:30:00',
            ],

            [
                'turno_id' => 1,
                'departamento_id' => 2,
                'hora_entrada' => '09:30:00',
                'hora_salida' => '17:30:00',
            ],

            [
                'turno_id' => 1,
                'departamento_id' => 3,
                'hora_entrada' => '07:30:00',
                'hora_salida' => '15:30:00',
            ],

            [
                'turno_id' => 1,
                'departamento_id' => 4,
                'hora_entrada' => '08:00:00',
                'hora_salida' => '16:00:00',
            ],

            [
                'turno_id' => 1,
                'departamento_id' => 5,
                'hora_entrada' => '07:00:00',
                'hora_salida' => '15:00:00',
            ],

            /*
            |--------------------------------------------------------------------------
            | TURNO NOCTURNO
            |--------------------------------------------------------------------------
            */

            [
                'turno_id' => 2,
                'departamento_id' => 1,
                'hora_entrada' => '21:00:00',
                'hora_salida' => '05:00:00',
            ],

            [
                'turno_id' => 2,
                'departamento_id' => 2,
                'hora_entrada' => '21:00:00',
                'hora_salida' => '05:00:00',
            ],

            [
                'turno_id' => 2,
                'departamento_id' => 3,
                'hora_entrada' => '21:00:00',
                'hora_salida' => '05:00:00',
            ],

            [
                'turno_id' => 2,
                'departamento_id' => 4,
                'hora_entrada' => '21:00:00',
                'hora_salida' => '05:00:00',
            ],

            [
                'turno_id' => 2,
                'departamento_id' => 5,
                'hora_entrada' => '21:00:00',
                'hora_salida' => '05:00:00',
            ],

        ];

        foreach ($horarios as $horario) {

            Horario::updateOrCreate(

                [
                    'turno_id' => $horario['turno_id'],
                    'departamento_id' => $horario['departamento_id'],
                ],

                [
                    'hora_entrada' => $horario['hora_entrada'],
                    'hora_salida' => $horario['hora_salida'],
                ]

            );
        }
    }
}
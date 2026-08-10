<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Turno;
use App\Models\Departamento;
use App\Models\Horario;

class HorarioSeeder extends Seeder
{
    public function run(): void
    {
        $matutino = Turno::where(
            'nombre',
            'MATUTINO'
        )->firstOrFail();

        $nocturno = Turno::where(
            'nombre',
            'NOCTURNO'
        )->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Turno Matutino
        |--------------------------------------------------------------------------
        */

        $horariosMatutino = [

            'Mezclado' => [
                'entrada' => '07:00:00',
                'salida' => '15:00:00',
            ],

            'Formado' => [
                'entrada' => '07:30:00',
                'salida' => '15:30:00',
            ],

            'Horneado' => [
                'entrada' => '08:00:00',
                'salida' => '16:00:00',
            ],

            'Empaque Barras' => [
                'entrada' => '09:30:00',
                'salida' => '17:30:00',
            ],

            'Empaque Granola' => [
                'entrada' => '09:30:00',
                'salida' => '17:30:00',
            ],

        ];

        foreach ($horariosMatutino as $departamento => $horario) {

            $departamentoModel = Departamento::where(
                'nombre',
                $departamento
            )->firstOrFail();

            Horario::updateOrCreate(

                [
                    'turno_id' => $matutino->id,
                    'departamento_id' => $departamentoModel->id,
                ],

                [
                    'hora_entrada' => $horario['entrada'],
                    'hora_salida' => $horario['salida'],
                ]

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Turno Nocturno
        |--------------------------------------------------------------------------
        */

        $departamentos = Departamento::all();

        foreach ($departamentos as $departamento) {

            Horario::updateOrCreate(

                [
                    'turno_id' => $nocturno->id,
                    'departamento_id' => $departamento->id,
                ],

                [
                    'hora_entrada' => '21:00:00',
                    'hora_salida' => '05:00:00',
                ]

            );
        }
    }
}
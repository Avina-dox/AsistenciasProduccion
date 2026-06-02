<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [

            'Empaque Barras',
            'Empaque Granola',
            'Formado',
            'Horneado',
            'Mezclado',

        ];

        foreach ($departamentos as $departamento) {

            Departamento::firstOrCreate([
                'nombre' => $departamento
            ]);

        }
    }
}
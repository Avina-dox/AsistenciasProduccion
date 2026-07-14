<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstatusHoraExtra;

class EstatusHoraExtraSeeder extends Seeder
{
    public function run(): void
    {
        $estatus = [

            [
                'nombre' => 'PENDIENTE',
                'color' => 'yellow'
            ],

            [
                'nombre' => 'AUTORIZADA',
                'color' => 'green'
            ],

            [
                'nombre' => 'RECHAZADA',
                'color' => 'red'
            ],

            [
                'nombre' => 'CANCELADA',
                'color' => 'gray'
            ],

        ];

        foreach ($estatus as $item){

            EstatusHoraExtra::firstOrCreate($item);

        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstatusPermiso;

class EstatusPermisoSeeder extends Seeder
{
    public function run(): void
    {
        $estatus = [

            [
                'nombre'=>'PENDIENTE',
                'color'=>'yellow'
            ],

            [
                'nombre'=>'APROBADO',
                'color'=>'green'
            ],

            [
                'nombre'=>'RECHAZADO',
                'color'=>'red'
            ],

            [
                'nombre'=>'CANCELADO',
                'color'=>'gray'
            ]

        ];

        foreach($estatus as $item){

            EstatusPermiso::firstOrCreate(
                ['nombre'=>$item['nombre']],
                $item
            );

        }
    }
}
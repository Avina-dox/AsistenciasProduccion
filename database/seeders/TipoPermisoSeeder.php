<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPermiso;

class TipoPermisoSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [

            [
                'nombre'=>'Permiso Personal',
                'codigo'=>'PP',
                'goce_sueldo'=>false
            ],

            [
                'nombre'=>'Consulta Médica',
                'codigo'=>'CM',
                'goce_sueldo'=>true
            ],

            [
                'nombre'=>'Trámite Oficial',
                'codigo'=>'TO',
                'goce_sueldo'=>true
            ],

            [
                'nombre'=>'Salida Anticipada',
                'codigo'=>'SA',
                'goce_sueldo'=>false
            ],

            [
                'nombre'=>'Entrada Tarde',
                'codigo'=>'ET',
                'goce_sueldo'=>false
            ],

            [
                'nombre'=>'Capacitación',
                'codigo'=>'CAP',
                'goce_sueldo'=>true
            ],

            [
                'nombre'=>'Otro',
                'codigo'=>'OT',
                'goce_sueldo'=>false
            ]

        ];

        foreach($tipos as $tipo){

            TipoPermiso::firstOrCreate(
                ['codigo'=>$tipo['codigo']],
                $tipo
            );

        }
    }
}
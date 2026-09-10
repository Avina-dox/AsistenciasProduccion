<?php

namespace App\Repositories;

use App\Models\Empleado;

class AsistenciaRepository
{
    public function obtenerAsistencias(
        $desde,
        $hasta,
        $departamento = null,
        $turno = null,
        $estatus = 'ACTIVO'
    ) {

        $empleados = Empleado::query()

            ->with([

                'departamento',

                'turno',

                'asistencias' => function ($query) use ($desde, $hasta) {

                    $query->whereBetween(
                        'fecha',
                        [
                            $desde,
                            $hasta
                        ]
                    );

                },

                'asistencias.estatus',

                'horasExtras.horaExtra.estatus',

            ]);

        if ($departamento) {

            $empleados->where(
                'departamento_id',
                $departamento
            );

        }

        if ($turno) {

            $empleados->where(
                'turno_id',
                $turno
            );

        }

        if ($estatus != 'TODOS') {

            $empleados->where(
                'estatus',
                $estatus
            );

        }

        return $empleados

            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')

            ->get();
    }
}
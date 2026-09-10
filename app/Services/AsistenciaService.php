<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\AsistenciaRepository;

class AsistenciaService
{
    protected AsistenciaRepository $repository;

    public function __construct(
        AsistenciaRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function obtenerReporte(
        $desde,
        $hasta,
        $departamento = null,
        $turno = null,
        $estatus = 'ACTIVO'
    ) {

        $empleados = $this->repository
            ->obtenerAsistencias(
                $desde,
                $hasta,
                $departamento,
                $turno,
                $estatus
            );

        $dias = [];

        $fecha = Carbon::parse($desde);

        while ($fecha->lte($hasta)) {

            $dias[] = $fecha->copy();

            $fecha->addDay();
        }

        return [

            'dias' => $dias,

            'empleados' => $empleados,

            'desde' => $desde,

            'hasta' => $hasta,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Totales por empleado
    |--------------------------------------------------------------------------
    */

    public function obtenerTotalesEmpleado($empleado, $desde, $hasta)
    {
        $desde = Carbon::parse($desde);

        $hasta = Carbon::parse($hasta);

        return [

            'A' => $this->contar(
                $empleado,
                'A',
                $desde,
                $hasta
            ),

            'F' => $this->contar(
                $empleado,
                'F',
                $desde,
                $hasta
            ),

            'R' => $this->contar(
                $empleado,
                'R',
                $desde,
                $hasta
            ),

            'V' => $this->contar(
                $empleado,
                'V',
                $desde,
                $hasta
            ),

            'I' => $this->contar(
                $empleado,
                'I',
                $desde,
                $hasta
            ),

            'PCG' => $this->contar(
                $empleado,
                'PCG',
                $desde,
                $hasta
            ),

            'PSG' => $this->contar(
                $empleado,
                'PSG',
                $desde,
                $hasta
            ),

            'O' => $this->contar(
                $empleado,
                'O',
                $desde,
                $hasta
            ),

            'S' => $this->contar(
                $empleado,
                'S',
                $desde,
                $hasta
            ),

            'HE' => $this->horasExtra(
                $empleado,
                $desde,
                $hasta
            ),

            'RETARDO_MIN' => $this->minutosRetardo(
                $empleado,
                $desde,
                $hasta
            ),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Contar incidencias
    |--------------------------------------------------------------------------
    */

    private function contar(
        $empleado,
        $codigo,
        $desde,
        $hasta
    ) {

        return $empleado->asistencias

            ->filter(function ($a) use (
                $codigo,
                $desde,
                $hasta
            ) {

                return

                    $a->estatus?->codigo == $codigo

                    && Carbon::parse($a->fecha)

                        ->between(
                            $desde,
                            $hasta
                        );

            })

            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Horas Extra
    |--------------------------------------------------------------------------
    */

    private function horasExtra(
        $empleado,
        $desde,
        $hasta
    ) {

        return $empleado->horasExtras

            ->filter(function ($detalle) use (
                $desde,
                $hasta
            ) {

                return

                    $detalle->horaExtra

                    && $detalle->horaExtra->estatus

                    && $detalle
                        ->horaExtra
                        ->estatus
                        ->nombre == 'AUTORIZADA'

                    && Carbon::parse(
                        $detalle
                            ->horaExtra
                            ->fecha
                    )

                    ->between(
                        $desde,
                        $hasta
                    );

            })

            ->sum('horas');

    }

    /*
    |--------------------------------------------------------------------------
    | Minutos de retardo acumulados
    |--------------------------------------------------------------------------
    */

    private function minutosRetardo(
        $empleado,
        $desde,
        $hasta
    ) {

        return $empleado->asistencias

            ->filter(function ($a) use (
                $desde,
                $hasta
            ) {

                return Carbon::parse($a->fecha)
                    ->between(
                        $desde,
                        $hasta
                    );

            })

            ->sum('minutos_retardo');
    }
}
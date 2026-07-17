<?php

namespace App\Services;

use App\Models\HoraExtra;
use App\Models\Departamento;
use App\Models\EstatusHoraExtra;
use App\Repositories\HoraExtraRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HoraExtraService
{
    public function __construct(
        protected HoraExtraRepository $repository
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function listar()
    {
        return $this->repository->listar();
    }

    /*
    |--------------------------------------------------------------------------
    | Datos para formulario
    |--------------------------------------------------------------------------
    */

    public function datosFormulario(): array
    {
        return [

            'departamentos' => Departamento::orderBy(
                'nombre'
            )->get(),

            'empleados' => $this->repository
                ->empleadosActivos(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Detalle
    |--------------------------------------------------------------------------
    */

    public function detalle(
        HoraExtra $horaExtra
    ): HoraExtra {

        return $this->repository
            ->obtener($horaExtra);

    }

    /*
    |--------------------------------------------------------------------------
    | Crear solicitud
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): HoraExtra
    {
        return DB::transaction(function () use ($datos) {

            $estatus = EstatusHoraExtra::where(
                'nombre',
                'PENDIENTE'
            )->firstOrFail();

            $horaExtra = $this->repository->crear([

                'folio' => $this->generarFolio(),

                'tipo' => $datos['tipo'],

                'departamento_id'
                    => $datos['departamento_id'],

                'fecha'
                    => $datos['fecha'],

                'hora_inicio'
                    => $datos['hora_inicio'],

                'hora_fin'
                    => $datos['hora_fin'],

                'motivo'
                    => $datos['motivo'],

                'estatus_hora_extra_id'
                    => $estatus->id,

                'registrado_por'
                    => Auth::id(),

            ]);

            $horas = $this->calcularHoras(

                $datos['hora_inicio'],

                $datos['hora_fin']

            );

            if ($datos['tipo'] == 'AREA') {

                $empleados = $this->repository
                    ->empleadosPorArea(
                        $datos['departamento_id']
                    );

            } else {

                $empleados = $this->repository
                    ->empleadosSeleccionados(
                        $datos['empleados']
                    );

            }

            foreach ($empleados as $empleado) {

                $this->repository->crearDetalle([

                    'hora_extra_id'
                        => $horaExtra->id,

                    'empleado_id'
                        => $empleado->id,

                    'horas'
                        => $horas,

                ]);

            }

            return $horaExtra;

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Aprobar
    |--------------------------------------------------------------------------
    */

    public function aprobar(
        HoraExtra $horaExtra
    ): void {

        $estatus = EstatusHoraExtra::where(
            'nombre',
            'AUTORIZADA'
        )->firstOrFail();

        $this->repository->actualizar(

            $horaExtra,

            [

                'estatus_hora_extra_id'
                    => $estatus->id,

                'autorizado_por'
                    => Auth::id(),

                'fecha_autorizacion'
                    => now(),

            ]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Rechazar
    |--------------------------------------------------------------------------
    */

    public function rechazar(
        HoraExtra $horaExtra,
        string $observaciones
    ): void {

        $estatus = EstatusHoraExtra::where(
            'nombre',
            'RECHAZADA'
        )->firstOrFail();

        $this->repository->actualizar(

            $horaExtra,

            [

                'estatus_hora_extra_id'
                    => $estatus->id,

                'autorizado_por'
                    => Auth::id(),

                'fecha_autorizacion'
                    => now(),

                'observaciones_coordinacion'
                    => $observaciones,

            ]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Generar folio
    |--------------------------------------------------------------------------
    */

    private function generarFolio(): string
    {
        $ultimo = HoraExtra::max('id') + 1;

        return sprintf(

            'HE-%s-%05d',

            now()->year,

            $ultimo

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Calcular horas
    |--------------------------------------------------------------------------
    */

    private function calcularHoras(
        string $inicio,
        string $fin
    ): float {

        return (

            strtotime($fin)
            -
            strtotime($inicio)

        ) / 3600;

    }
}
<?php

namespace App\Services;

use App\Models\Permiso;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\TipoPermiso;
use App\Models\EstatusPermiso;
use App\Models\EstatusAsistencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermisoService
{
    /*
    |--------------------------------------------------------------------------
    | Crear Solicitud
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Permiso
    {
        return DB::transaction(function () use ($datos) {

            $tipo = TipoPermiso::findOrFail(
                $datos['tipo_permiso_id']
            );

            $estatus = EstatusPermiso::where(
                'nombre',
                'PENDIENTE'
            )->firstOrFail();

            return Permiso::create([

                'folio' => $this->generarFolio(),

                'empleado_id' => $datos['empleado_id'],

                'tipo_permiso_id' => $tipo->id,

                'fecha' => $datos['fecha'],

                'hora_salida' => $datos['hora_salida'],

                'hora_regreso' => $datos['hora_regreso'],

                'motivo' => $datos['motivo'],

                'goce_sueldo' => $tipo->goce_sueldo,

                'estatus_permiso_id' => $estatus->id,

                'registrado_por' => Auth::id(),

                'observaciones_supervisor'
                    => $datos['observaciones_supervisor'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Aprobar
    |--------------------------------------------------------------------------
    */

    public function aprobar(
        Permiso $permiso,
        ?string $observaciones = null
    ): void {

        DB::transaction(function () use (
            $permiso,
            $observaciones
        ) {

            $estatus = EstatusPermiso::where(
                'nombre',
                'APROBADO'
            )->firstOrFail();

            $permiso->update([

                'estatus_permiso_id'
                    => $estatus->id,

                'autorizado_por'
                    => Auth::id(),

                'fecha_autorizacion'
                    => now(),

                'observaciones_rh'
                    => $observaciones,

            ]);

            $this->registrarAsistencia(
                $permiso
            );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Rechazar
    |--------------------------------------------------------------------------
    */

    public function rechazar(
        Permiso $permiso,
        ?string $observaciones = null
    ): void {

        $estatus = EstatusPermiso::where(
            'nombre',
            'RECHAZADO'
        )->firstOrFail();

        $permiso->update([

            'estatus_permiso_id'
                => $estatus->id,

            'autorizado_por'
                => Auth::id(),

            'fecha_autorizacion'
                => now(),

            'observaciones_rh'
                => $observaciones,

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Registrar Asistencia
    |--------------------------------------------------------------------------
    */

    private function registrarAsistencia(
        Permiso $permiso
    ): void {

        $codigo = $permiso->goce_sueldo
            ? 'PCG'
            : 'PSG';

        $estatus = EstatusAsistencia::where(
            'codigo',
            $codigo
        )->first();

        Asistencia::updateOrCreate(

            [

                'empleado_id'
                    => $permiso->empleado_id,

                'fecha'
                    => $permiso->fecha,

            ],

            [

                'estatus_asistencia_id'
                    => $estatus->id,

            ]

        );

    }

    /*
    |--------------------------------------------------------------------------
    | Folio
    |--------------------------------------------------------------------------
    */

    private function generarFolio(): string
    {
        $ultimo = Permiso::count() + 1;

        return sprintf(

            'PER-%s-%05d',

            now()->year,

            $ultimo

        );
    }
}

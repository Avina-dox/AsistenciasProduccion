<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\HoraExtra;
use App\Models\Permiso;

class DashboardService
{
    public function obtenerDashboard(): array
    {
        $hoy = Carbon::today();
        $totalActivos = Empleado::where('estatus', 'ACTIVO')->count();

        $presentes = Asistencia::whereDate('fecha', $hoy)
            ->whereHas('estatus', fn($q) => $q->where('codigo', 'A'))
            ->count();

        $faltas = Asistencia::whereDate('fecha', $hoy)
            ->whereHas('estatus', fn($q) => $q->where('codigo', 'F'))
            ->count();

        $porcentajeAsistencia = $totalActivos > 0
            ? round(($presentes / $totalActivos) * 100, 1)
            : 0;

        $porcentajeAusentismo = $totalActivos > 0
            ? round(($faltas / $totalActivos) * 100, 1)
            : 0;

        return [

            /*
            |--------------------------------------------------------------------------
            | KPIs
            |--------------------------------------------------------------------------
            */
            'empleados' => Empleado::count(),

            'usuarios' => User::count(),

            'empleados_activos' => $totalActivos,

            'asistencia_porcentaje' => $porcentajeAsistencia,

            'ausentismo_porcentaje' => $porcentajeAusentismo,

            /*
            |--------------------------------------------------------------------------
            | Asistencia de hoy
            |--------------------------------------------------------------------------
            */

            'presentes' => $presentes,

'faltas' => $faltas,

'retardos' => Asistencia::whereDate(
    'fecha',
    $hoy
)
->whereHas('estatus', function ($q) {

    $q->where('codigo', 'R');

})
->count(),

'vacaciones' => Asistencia::whereDate(
    'fecha',
    $hoy
)
->whereHas('estatus', function ($q) {

    $q->where('codigo', 'V');

})
->count(),

'incapacidades' => Asistencia::whereDate(
    'fecha',
    $hoy
)
->whereHas('estatus', function ($q) {

    $q->where('codigo', 'I');

})
->count(),

            /*
            |--------------------------------------------------------------------------
            | Horas Extra
            |--------------------------------------------------------------------------
            */

            'horas_extra_pendientes' => HoraExtra::where(
                'estatus_hora_extra_id',
                1
            )->count(),

            'horas_extra_autorizadas' => HoraExtra::where(
                'estatus_hora_extra_id',
                2
            )->count(),

            'horas_extra_rechazadas' => HoraExtra::where(
                'estatus_hora_extra_id',
                3
            )->count(),

            /*
            |--------------------------------------------------------------------------
            | Permisos
            |--------------------------------------------------------------------------
            */

            //'permisos_pendientes' => Permiso::where(
            //      'estatus',
            //    'PENDIENTE'
            // )->count(),

            'permisos_pendientes' => Permiso::count(),

            /*
            |--------------------------------------------------------------------------
            | Próximos cumpleaños
            |--------------------------------------------------------------------------
            */

            'cumpleaneros' => Empleado::whereNotNull(
                'onomastico'
            )
                ->orderBy('onomastico')
                ->take(5)
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Últimas horas extra
            |--------------------------------------------------------------------------
            */

            'ultimas_horas_extra' => HoraExtra::with([
                'departamento',
                'supervisor',
                'estatus'
            ])
                ->latest()
                ->take(5)
                ->get(),

            /*
            |--------------------------------------------------------------------------
            | Últimos empleados
            |--------------------------------------------------------------------------
            */

            'ultimos_empleados' => Empleado::latest()
                ->take(5)
                ->get(),

        ];
    }
}

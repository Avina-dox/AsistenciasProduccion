<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\HoraExtra;
use App\Models\Permiso;
use Illuminate\Support\Facades\DB;

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
        $objetivoMatutino = DB::table('plantilla_autorizada')->where('turno', 'dia')->value('objetivo');
        $objetivoNocturno = DB::table('plantilla_autorizada')->where('turno', 'noche')->value('objetivo');

        // "Diurno" y "MATUTINO" son el mismo turno (MATUTINO reemplazó el nombre viejo) — se cuentan juntos.
        $personalMatutino = Empleado::where('estatus', 'ACTIVO')
            ->whereHas('turno', function ($q) {
                $q->whereIn('nombre', ['Diurno', 'MATUTINO']);
            })
            ->count();

        $personalNocturno = Empleado::where('estatus', 'ACTIVO')
            ->whereHas('turno', function ($q) {
                $q->where('nombre', 'Nocturno');
            })
            ->count();

        $coberturaMatutina = $objetivoMatutino > 0
            ? round(($personalMatutino / $objetivoMatutino) * 100, 1)
            : 0;

        $coberturaNocturna = $objetivoNocturno > 0
            ? round(($personalNocturno / $objetivoNocturno) * 100, 1)
            : 0;

        /*
|--------------------------------------------------------------------------
| Gráfico de asistencia
|--------------------------------------------------------------------------
*/

        $inicioMes = $hoy->copy()->startOfMonth();
        $finMes = $hoy->copy()->endOfMonth();

        $registrosGrafica = Asistencia::with('estatus')
            ->whereBetween('fecha', [
                $inicioMes,
                $finMes
            ])
            ->get()
            ->groupBy(function ($asistencia) {
                return Carbon::parse($asistencia->fecha)
                    ->format('Y-m-d');
            });
        $graficaAsistencia = [];

        $periodo = $inicioMes->copy();

        while ($periodo->lte($finMes)) {

            $fecha = $periodo->format('Y-m-d');

            $registros = $registrosGrafica->get(
                $fecha,
                collect()
            );

            $graficaAsistencia[] = [

                'dia' => $periodo->format('d'),

                'asistencia' => $registros
                    ->where('estatus.codigo', 'A')
                    ->count(),

                'faltas' => $registros
                    ->where('estatus.codigo', 'F')
                    ->count(),

                'retardos' => $registros
                    ->where('estatus.codigo', 'R')
                    ->count(),

                'pcg' => $registros
                    ->where('estatus.codigo', 'PCG')
                    ->count(),

                'psg' => $registros
                    ->where('estatus.codigo', 'PSG')
                    ->count(),

                'onomastico' => $registros
                    ->where('estatus.codigo', 'O')
                    ->count(),

                'vacaciones' => $registros
                    ->where('estatus.codigo', 'V')
                    ->count(),

                'incapacidad' => $registros
                    ->where('estatus.codigo', 'I')
                    ->count(),

                'suspension' => $registros
                    ->where('estatus.codigo', 'S')
                    ->count(),

            ];

            $periodo->addDay();
        }


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
            'cobertura' => [

                'matutino' => [
                    'personal' => $personalMatutino,
                    'objetivo' => $objetivoMatutino,
                    'porcentaje' => $coberturaMatutina,
                ],

                'nocturno' => [
                    'personal' => $personalNocturno,
                    'objetivo' => $objetivoNocturno,
                    'porcentaje' => $coberturaNocturna,
                ],

            ],
            'grafica_asistencia' => $graficaAsistencia,



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

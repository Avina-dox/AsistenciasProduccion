<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\HoraExtra;
use App\Models\Horario;
use App\Models\Permiso;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function obtenerDashboard(): array
    {
        $hoy = Carbon::today();

        // Los empleados con cuenta de usuario vinculada (Supervisor, Coordinación, etc.)
        // se excluyen de los KPIs de asistencia/cobertura: no son personal de piso.
        $sinCuentaUsuario = fn($q) => $q->whereNull('user_id');

        $totalActivos = Empleado::where('estatus', 'ACTIVO')
            ->whereNull('user_id')
            ->count();

        $presentes = Asistencia::whereDate('fecha', $hoy)
            ->whereHas('estatus', fn($q) => $q->where('codigo', 'A'))
            ->whereHas('empleado', $sinCuentaUsuario)
            ->count();

        $faltas = Asistencia::whereDate('fecha', $hoy)
            ->whereHas('estatus', fn($q) => $q->where('codigo', 'F'))
            ->whereHas('empleado', $sinCuentaUsuario)
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
            ->whereNull('user_id')
            ->whereHas('turno', function ($q) {
                $q->whereIn('nombre', ['Diurno', 'MATUTINO']);
            })
            ->count();

        $personalNocturno = Empleado::where('estatus', 'ACTIVO')
            ->whereNull('user_id')
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
        | Cobertura por área (departamento) y turno
        |--------------------------------------------------------------------------
        */

        // Se arma a partir de los Horarios del panel de administración:
        // cada turno con horarios es una columna, cada departamento con
        // horarios es una fila y la plantilla autorizada es el objetivo.
        [$coberturaTurnos, $coberturaPorArea] = $this->coberturaPorArea();

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
            ->whereHas('empleado', $sinCuentaUsuario)
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

            'cobertura_turnos' => $coberturaTurnos,

            'cobertura_por_area' => $coberturaPorArea,

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
                ->whereHas('empleado', $sinCuentaUsuario)
                ->count(),

            'vacaciones' => Asistencia::whereDate(
                'fecha',
                $hoy
            )
                ->whereHas('estatus', function ($q) {

                    $q->where('codigo', 'V');
                })
                ->whereHas('empleado', $sinCuentaUsuario)
                ->count(),

            'incapacidades' => Asistencia::whereDate(
                'fecha',
                $hoy
            )
                ->whereHas('estatus', function ($q) {

                    $q->where('codigo', 'I');
                })
                ->whereHas('empleado', $sinCuentaUsuario)
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

    /**
     * Cobertura por área (departamento) y turno según los Horarios registrados.
     *
     * Devuelve [turnos, cobertura]:
     *   turnos    → [['id' => 1, 'nombre' => 'MATUTINO'], ...] ordenados por hora de entrada
     *   cobertura → ['Mezclado' => [turno_id => info|null, ...], ...]
     *
     * info = ['personal', 'objetivo', 'porcentaje']. Es null cuando el
     * departamento no tiene horario en ese turno (no opera). Si el horario
     * no tiene plantilla autorizada, objetivo y porcentaje son null.
     */
    private function coberturaPorArea(): array
    {
        $horarios = Horario::with(['turno:id,nombre', 'departamento:id,nombre'])
            ->get()
            ->filter(fn($horario) => $horario->turno && $horario->departamento);

        // Turnos: el que entra más temprano primero (Matutino antes que Nocturno).
        $turnos = $horarios
            ->groupBy('turno_id')
            ->map(fn($grupo) => [
                'id' => $grupo->first()->turno->id,
                'nombre' => $grupo->first()->turno->nombre,
                'entrada' => $grupo->min(fn($h) => $h->hora_entrada?->format('H:i')),
            ])
            ->sortBy([['entrada', 'asc'], ['nombre', 'asc']])
            ->map(fn($turno) => ['id' => $turno['id'], 'nombre' => $turno['nombre']])
            ->values()
            ->all();

        $conteo = Empleado::where('estatus', 'ACTIVO')
            ->whereNull('user_id')
            ->whereNotNull('turno_id')
            ->whereNotNull('departamento_id')
            ->selectRaw('departamento_id, turno_id, count(*) as total')
            ->groupBy('departamento_id', 'turno_id')
            ->get()
            ->mapWithKeys(fn($fila) => [$fila->departamento_id . ':' . $fila->turno_id => (int) $fila->total]);

        $cobertura = [];

        foreach ($horarios->sortBy(fn($h) => $h->departamento->nombre) as $horario) {
            $area = $horario->departamento->nombre;

            // Todas las columnas existen en cada fila; las que no opera quedan en null.
            $cobertura[$area] ??= array_fill_keys(array_column($turnos, 'id'), null);

            $personal = $conteo[$horario->departamento_id . ':' . $horario->turno_id] ?? 0;
            $objetivo = $horario->plantilla_autorizada;

            $cobertura[$area][$horario->turno_id] = [
                'personal' => $personal,
                'objetivo' => $objetivo,
                'porcentaje' => $objetivo > 0
                    ? round(($personal / $objetivo) * 100, 1)
                    : null,
            ];
        }

        return [$turnos, $cobertura];
    }
}

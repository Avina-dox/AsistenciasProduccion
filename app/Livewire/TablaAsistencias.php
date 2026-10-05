<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\EstatusAsistencia;
use App\Models\Turno;
use App\Models\Departamento;
use App\Models\User;
use App\Services\MicrosoftGraphMailService;

class TablaAsistencias extends Component
{
    // Límite de faltas al mes antes de marcarlas en rojo y notificar a Coordinación/Supervisor
    public const LIMITE_FALTAS_MES = 3;

    public $mes;
    public $anio;
    public $desde;

    public $hasta;

    public $modo = 'MES';

    public $estatus;
    public $estatusEmpleado = 'ACTIVO';
    public $departamento_id;
    public $turno_id;
    public $search = '';

    public $turnos;
    public $departamentos;

    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;

        $this->desde = now()
            ->startOfMonth()
            ->format('Y-m-d');

        $this->hasta = now()
            ->endOfMonth()
            ->format('Y-m-d');

        $this->estatus = EstatusAsistencia::all();

        $this->turnos = Turno::all();

        $this->departamentos = Departamento::all();
    }

    public function actualizarAsistencia(
        $empleadoId,
        $fecha,
        $estatusId
    ) {

        // Seleccionar la opción vacía ("—") quita la asistencia capturada por error
        if (empty($estatusId)) {

            Asistencia::where('empleado_id', $empleadoId)
                ->where('fecha', $fecha)
                ->delete();

            return;
        }

        Asistencia::updateOrCreate(

            [
                'empleado_id' => $empleadoId,
                'fecha' => $fecha,
            ],

            [
                'estatus_asistencia_id' => $estatusId,
            ]

        );

        $this->notificarExcesoFaltas(
            $empleadoId,
            $fecha,
            $estatusId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Notificar exceso de faltas (más de 4 en el mes)
    |--------------------------------------------------------------------------
    */

    private function notificarExcesoFaltas(
        $empleadoId,
        $fecha,
        $estatusId
    ) {

        $estatus = EstatusAsistencia::find($estatusId);

        if (!$estatus || $estatus->codigo !== 'F') {
            return;
        }

        $inicioMes = Carbon::parse($fecha)->startOfMonth();
        $finMes = Carbon::parse($fecha)->endOfMonth();

        $totalFaltas = Asistencia::where('empleado_id', $empleadoId)
            ->whereBetween('fecha', [
                $inicioMes->format('Y-m-d'),
                $finMes->format('Y-m-d'),
            ])
            ->whereHas('estatus', function ($q) {
                $q->where('codigo', 'F');
            })
            ->count();

        // Solo se notifica justo cuando se cruza el umbral,
        // para no reenviar el correo en cada captura posterior.
        if ($totalFaltas !== self::LIMITE_FALTAS_MES + 1) {
            return;
        }

        $empleado = Empleado::with(['departamento', 'turno'])->find($empleadoId);

        if (!$empleado) {
            return;
        }

        $destinatarios = User::role(['Coordinacion', 'Supervisor'])
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($destinatarios)) {
            return;
        }

        $html = view('emails.asistencias.exceso-faltas', [
            'empleado' => $empleado,
            'totalFaltas' => $totalFaltas,
            'mes' => $inicioMes,
            'limiteFaltas' => self::LIMITE_FALTAS_MES,
        ])->render();

        app(MicrosoftGraphMailService::class)->sendHtml(
            $destinatarios,
            'Exceso de faltas - ' .
                $empleado->apellido_paterno . ' ' .
                $empleado->apellido_materno . ' ' .
                $empleado->nombre,
            $html
        );
    }

    public function render()
    {
        $empleados = Empleado::query()

            ->whereIn('estatus', ['ACTIVO', 'BAJA'])

            ->with([

                'departamento',

                'turno',

                'asistencias' => function ($query) {

                    $query->whereBetween(
                        'fecha',
                        [
                            $this->desde,
                            $this->hasta
                        ]
                    );
                },

                'asistencias.estatus',

                'horasExtras.horaExtra.estatus',

                'user.roles',

            ]);
        if ($this->departamento_id) {

            $empleados->where(
                'departamento_id',
                $this->departamento_id
            );
        }

        if ($this->turno_id) {

            $empleados->where(
                'turno_id',
                $this->turno_id
            );
        }
        if ($this->estatusEmpleado != 'TODOS') {

            $empleados->where(
                'estatus',
                $this->estatusEmpleado
            );
        }

        if ($this->search) {

            $empleados->where(function ($query) {

                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $this->search . '%')
                    ->orWhere('codigo_empleado', 'like', '%' . $this->search . '%');
            });
        }

        $empleados
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre');

        $listaEmpleados = $empleados->get();

        /*
        |--------------------------------------------------------------------------
        | Faltas del mes completo (independiente del periodo mostrado en la tabla)
        |--------------------------------------------------------------------------
        */

        $inicioMes = Carbon::parse($this->desde)->startOfMonth();
        $finMes = Carbon::parse($this->desde)->endOfMonth();

        $faltasMes = Asistencia::whereIn(
            'empleado_id',
            $listaEmpleados->pluck('id')
        )
            ->whereBetween('fecha', [
                $inicioMes->format('Y-m-d'),
                $finMes->format('Y-m-d'),
            ])
            ->whereHas('estatus', function ($q) {
                $q->where('codigo', 'F');
            })
            ->selectRaw('empleado_id, count(*) as total')
            ->groupBy('empleado_id')
            ->pluck('total', 'empleado_id');

        return view(
            'components.tabla-asistencias',
            [
                'empleados' => $listaEmpleados,
                'faltasMes' => $faltasMes,
                'limiteFaltas' => self::LIMITE_FALTAS_MES,
            ]
        );
    }
    public function getDiasProperty()
    {
        $dias = [];

        $fecha = Carbon::parse($this->desde);

        while ($fecha->lte($this->hasta)) {

            $dias[] = $fecha->copy();

            $fecha->addDay();
        }

        return $dias;
    }
    private function sincronizarMesAnio()
{
    $fecha = Carbon::parse($this->desde);

    $this->mes = $fecha->month;

    $this->anio = $fecha->year;
}



    public function semanaActual()
    {
        $this->modo = 'SEMANA';

        $this->desde = now()
            ->startOfWeek()
            ->format('Y-m-d');

        $this->hasta = now()
            ->endOfWeek()
            ->format('Y-m-d');
    }

    public function mesActual()
    {
        $this->modo = 'MES';

        $this->desde = now()
            ->startOfMonth()
            ->format('Y-m-d');

        $this->hasta = now()
            ->endOfMonth()
            ->format('Y-m-d');
    }

    public function quincenaActual()
{
    $this->modo = 'QUINCENA';

    $fecha = now();

    if ($fecha->day <= 15) {

        $this->desde = $fecha->copy()
            ->startOfMonth()
            ->format('Y-m-d');

        $this->hasta = $fecha->copy()
            ->startOfMonth()
            ->addDays(14)
            ->format('Y-m-d');

    } else {

        $this->desde = $fecha->copy()
            ->startOfMonth()
            ->addDays(15)
            ->format('Y-m-d');

        $this->hasta = $fecha->copy()
            ->endOfMonth()
            ->format('Y-m-d');
    }

    $this->sincronizarMesAnio();
}
    public function siguientePeriodo()
    {
        switch ($this->modo) {

            case 'MES':

                $fecha = Carbon::parse($this->desde)->addMonth();

                $this->desde = $fecha->copy()
                    ->startOfMonth()
                    ->format('Y-m-d');

                $this->hasta = $fecha->copy()
                    ->endOfMonth()
                    ->format('Y-m-d');

                break;

            case 'SEMANA':

                $fecha = Carbon::parse($this->desde)->addWeek();

                $this->desde = $fecha->copy()
                    ->startOfWeek()
                    ->format('Y-m-d');

                $this->hasta = $fecha->copy()
                    ->endOfWeek()
                    ->format('Y-m-d');

                break;

            case 'QUINCENA':

                $this->desde = Carbon::parse($this->desde)
                    ->addDays(15)
                    ->format('Y-m-d');

                $this->hasta = Carbon::parse($this->hasta)
                    ->addDays(15)
                    ->format('Y-m-d');

                break;
        }

        $this->sincronizarMesAnio();
    }

    public function periodoAnterior()
    {
        switch ($this->modo) {

            case 'MES':

                $fecha = Carbon::parse($this->desde)->subMonth();

                $this->desde = $fecha->copy()
                    ->startOfMonth()
                    ->format('Y-m-d');

                $this->hasta = $fecha->copy()
                    ->endOfMonth()
                    ->format('Y-m-d');

                break;

            case 'SEMANA':

                $fecha = Carbon::parse($this->desde)->subWeek();

                $this->desde = $fecha->copy()
                    ->startOfWeek()
                    ->format('Y-m-d');

                $this->hasta = $fecha->copy()
                    ->endOfWeek()
                    ->format('Y-m-d');

                break;

            case 'QUINCENA':

                $this->desde = Carbon::parse($this->desde)
                    ->subDays(15)
                    ->format('Y-m-d');

                $this->hasta = Carbon::parse($this->hasta)
                    ->subDays(15)
                    ->format('Y-m-d');

                break;
        }

        $this->sincronizarMesAnio();
    }
    public function actualizarRango()
    {
        if (
            $this->desde &&
            $this->hasta &&
            $this->desde > $this->hasta
        ) {

            $this->hasta = $this->desde;
        }
    }
}

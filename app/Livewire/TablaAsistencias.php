<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\EstatusAsistencia;
use App\Models\Turno;
use App\Models\Departamento;

class TablaAsistencias extends Component
{
    public $mes;
    public $anio;
    public $desde;

    public $hasta;

    public $modo = 'MES';

    public $estatus;
    public $estatusEmpleado = 'ACTIVO';
    public $departamento_id;
    public $turno_id;

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

        Asistencia::updateOrCreate(

            [
                'empleado_id' => $empleadoId,
                'fecha' => $fecha,
            ],

            [
                'estatus_asistencia_id' => $estatusId,
            ]

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

        return view(
            'components.tabla-asistencias',
            [
                'empleados' => $empleados->get(),
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

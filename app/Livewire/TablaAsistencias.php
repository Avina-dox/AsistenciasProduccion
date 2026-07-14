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
    public $diasMes;

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

        $this->diasMes = Carbon::create(
            $this->anio,
            $this->mes
        )->daysInMonth;

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

        $query->whereMonth('fecha', $this->mes)
              ->whereYear('fecha', $this->anio);

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

    public function mesAnterior()
    {
        $this->mes--;

        if ($this->mes < 1) {

            $this->mes = 12;

            $this->anio--;
        }

        $this->actualizarDiasMes();
    }

    public function mesSiguiente()
    {
        $this->mes++;

        if ($this->mes > 12) {

            $this->mes = 1;

            $this->anio++;
        }

        $this->actualizarDiasMes();
    }

    public function actualizarDiasMes()
    {
        $this->diasMes = Carbon::create(
            $this->anio,
            $this->mes
        )->daysInMonth;
    }
}

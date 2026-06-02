<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Empleado;
use App\Models\Asistencia;
use App\Models\EstatusAsistencia;

class TablaAsistencias extends Component
{
    public $mes;
    public $anio;
    public $diasMes;
    public $estatus;

    public function mount()
    {
        $this->mes = now()->month;
        $this->anio = now()->year;

        $this->diasMes = Carbon::create(
            $this->anio,
            $this->mes
        )->daysInMonth;

        $this->estatus =
            EstatusAsistencia::all();
    }

    public function actualizarAsistencia(
        $empleadoId,
        $fecha,
        $estatusId) 
        {

        Asistencia::updateOrCreate(

            [
                'empleado_id' => $empleadoId,
                'fecha' => $fecha,
            ],

            [
                'estatus_asistencia_id'
                    => $estatusId
            ]

        );
    }
public function render()
{
    $empleados = Empleado::with([

        'asistencias' => function ($query) {

            $query->whereMonth(
                'fecha',
                $this->mes
            )

            ->whereYear(
                'fecha',
                $this->anio
            );

        },

        'asistencias.estatus'

    ])->get();

    return view(
        'components.tabla-asistencias',
        [
            'empleados' => $empleados
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
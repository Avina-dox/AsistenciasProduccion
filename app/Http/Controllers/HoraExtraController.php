<?php

namespace App\Http\Controllers;

use App\Models\HoraExtra;
use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\EstatusHoraExtra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\HoraExtraDetalle;

class HoraExtraController extends Controller
{
   public function index()
{
    $solicitudes = HoraExtra::with([
        'departamento',
        'estatus',
        'supervisor',
        'coordinador'
    ])
    ->latest()
    ->paginate(15);

    return view(
        'hora-extras.index',
        compact('solicitudes')
    );
}

    public function create()
    {
        return view(
            'hora-extras.create',
            [
                'departamentos' => Departamento::orderBy('nombre')->get(),

                'empleados' => Empleado::where(
                    'estatus',
                    'ACTIVO'
                )->orderBy('nombre')->get()
            ]
        );
    }



 public function store(Request $request)
{
    $request->validate([

        'tipo' => 'required|in:AREA,EMPLEADO',

        'departamento_id' => 'required|exists:departamentos,id',

        'fecha' => 'required|date',

        'hora_inicio' => 'required',

        'hora_fin' => 'required|after:hora_inicio',

        'motivo' => 'required',

        'empleados' => 'nullable|array',

        'empleados.*' => 'exists:empleados,id',

    ]);

    DB::transaction(function () use ($request) {

        $horaExtra = HoraExtra::create([

            'folio' => $this->generarFolio(),

            'tipo' => $request->tipo,

            'departamento_id' => $request->departamento_id,

            'fecha' => $request->fecha,

            'hora_inicio' => $request->hora_inicio,

            'hora_fin' => $request->hora_fin,

            'motivo' => $request->motivo,

            'estatus_hora_extra_id' => 1,

            'registrado_por' => Auth::id(),

        ]);

        $horas = $this->calcularHoras(
            $request->hora_inicio,
            $request->hora_fin
        );

        if ($request->tipo == 'AREA') {

            $empleados = Empleado::where(
                'departamento_id',
                $request->departamento_id
            )
            ->where('estatus', 'ACTIVO')
            ->get();

        } else {

            $empleados = Empleado::whereIn(
                'id',
                $request->empleados ?? []
            )->get();

        }

        foreach ($empleados as $empleado) {

            HoraExtraDetalle::create([

                'hora_extra_id' => $horaExtra->id,

                'empleado_id' => $empleado->id,

                'horas' => $horas,

            ]);

        }

    });

    return redirect()
        ->route('hora-extras.index')
        ->with(
            'success',
            'Solicitud registrada correctamente.'
        );
}

   public function show(HoraExtra $horaExtra)
{
    $horaExtra->load([
        'departamento',
        'estatus',
        'supervisor',
        'coordinador',
        'detalles.empleado'
    ]);

    return view(
        'hora-extras.show',
        compact('horaExtra')
    );
}

    public function edit(HoraExtra $horaExtra) {}

    public function update(
        Request $request,
        HoraExtra $horaExtra
    ) {}

    private function generarFolio()
    {
        $ultimo = HoraExtra::max('id') + 1;

        return 'HE-'
            . now()->year
            . '-'
            . str_pad(
                $ultimo,
                5,
                '0',
                STR_PAD_LEFT
            );
    }
    private function calcularHoras(
        $inicio,
        $fin
    ) {
        $inicio = strtotime($inicio);

        $fin = strtotime($fin);

        return ($fin - $inicio) / 3600;
    }
    public function aprobar(HoraExtra $horaExtra)
{
    $horaExtra->update([

        'estatus_hora_extra_id' => 2,

        'autorizado_por' => auth()->id(),

        'fecha_autorizacion' => now(),

    ]);

    return back()->with(
        'success',
        'Solicitud aprobada correctamente.'
    );
}
public function rechazar(
    Request $request,
    HoraExtra $horaExtra
)
{
    $request->validate([
        'observaciones_coordinacion' => 'required'
    ]);

    $horaExtra->update([

        'estatus_hora_extra_id' => 3,

        'autorizado_por' => auth()->id(),

        'fecha_autorizacion' => now(),

        'observaciones_coordinacion'
            => $request->observaciones_coordinacion,

    ]);

    return back()->with(
        'success',
        'Solicitud rechazada.'
    );
}

    public function destroy(HoraExtra $horaExtra) {}
}

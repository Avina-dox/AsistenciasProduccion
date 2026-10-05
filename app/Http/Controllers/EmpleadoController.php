<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Departamento;
use App\Models\Turno;
use App\Models\Horario;


class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $empleados = Empleado::with([
            'departamento',
            'turno',
            'user.roles'
        ])
            ->when($search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%")
                        ->orWhere('codigo_empleado', 'like', "%{$search}%");
                });
            })
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $horarios = Horario::all()
            ->keyBy(function ($horario) {

                return $horario->turno_id
                    . '-' .
                    $horario->departamento_id;
            });

        return view(
            'empleados.index',
            compact(
                'empleados',
                'horarios'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departamentos = Departamento::all();
        $turnos = Turno::all();
        $usuarios = \App\Models\User::orderBy('name')->get();
        return view('empleados.create', compact('departamentos', 'turnos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
        \App\Services\MicrosoftGraphMailService $mail
    ) {
        $request->validate([
            'codigo_empleado' => 'required|unique:empleados',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'turno_id' => 'required|exists:turnos,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $empleado = Empleado::create($request->all());

        $remitente = auth()->user()?->email;

        $html = view('emails.empleados.alta', [
            'empleado' => $empleado,
            'remitente' => $remitente,
        ])->render();

        $destinatariosRH = \App\Models\User::role('RH')
            ->pluck('email')
            ->filter()
            ->all();

        if (!empty($destinatariosRH)) {

            $mail->sendHtml(
                $destinatariosRH,
                'Alta de nuevo empleado - ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno . ' ' . $empleado->nombre,
                $html
            );
        }

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Resumen de asistencias de un empleado para un periodo (usado en el modal de la lista).
     * Por defecto muestra el mes actual; acepta ?desde= y ?hasta= para ajustar el rango.
     */
    public function resumenAsistencias(
        Empleado $empleado,
        Request $request,
        \App\Services\AsistenciaService $service
    ) {
        $request->validate([
            'desde' => 'nullable|date',
            'hasta' => 'nullable|date',
        ]);

        $desde = $request->filled('desde')
            ? \Carbon\Carbon::parse($request->desde)->startOfDay()
            : now()->startOfMonth();

        $hasta = $request->filled('hasta')
            ? \Carbon\Carbon::parse($request->hasta)->endOfDay()
            : now()->endOfMonth();

        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()->endOfDay()];
        }

        $empleado->load([
            'departamento',
            'turno',

            'asistencias' => function ($query) use ($desde, $hasta) {

                $query->whereBetween('fecha', [
                    $desde->format('Y-m-d'),
                    $hasta->format('Y-m-d'),
                ]);
            },

            'asistencias.estatus',

            'horasExtras.horaExtra.estatus',
        ]);

        $totales = $service->obtenerTotalesEmpleado(
            $empleado,
            $desde,
            $hasta
        );

        return view('empleados.partials.resumen-asistencias', [
            'empleado' => $empleado,
            'totales' => $totales,
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }

    public function actualizarEstatus(
    Request $request,
    Empleado $empleado,
    \App\Services\MicrosoftGraphMailService $mail
) {
    $request->validate([
        'estatus' => 'required|in:ACTIVO,INACTIVO,VACACIONES,BAJA',
    ]);

    $estatusAnterior = $empleado->estatus;
    $nuevoEstatus = $request->estatus;

    if (
        $nuevoEstatus === 'BAJA' &&
        $estatusAnterior !== 'BAJA' &&
        !auth()->user()->hasAnyRole(['RH', 'Admin', 'Coordinacion'])
    ) {
        return back()->withErrors([
            'estatus' => 'No tienes permiso para dar de baja a un empleado.',
        ]);
    }

    $empleado->update([
        'estatus' => $nuevoEstatus,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Notificación de baja
    |--------------------------------------------------------------------------
    */

    if (
        $nuevoEstatus === 'BAJA' &&
        $estatusAnterior !== 'BAJA'
    ) {

        $empleado->load([
            'departamento',
            'turno',
        ]);

        $remitente = auth()->user()?->email;

        $html = view('emails.empleados.baja', [
            'empleado' => $empleado,
            'estatusAnterior' => $estatusAnterior,
            'remitente' => $remitente,
        ])->render();

        $destinatariosBaja = \App\Models\User::role(['RH', 'Admin', 'Supervisor', 'Coordinacion'])
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($destinatariosBaja)) {

            $mail->sendHtml(
                $destinatariosBaja,
                'Baja de empleado - ' .
                    $empleado->apellido_paterno . ' ' .
                    $empleado->apellido_materno . ' ' .
                    $empleado->nombre,
                $html
            );
        }
    }

    return back()->with(
        'success',
        'Estado actualizado correctamente.'
    );
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empleado $empleado)
    {
        $departamentos = Departamento::all();
        $turnos = Turno::all();
        $usuarios = \App\Models\User::orderBy('name')->get();

        return view(
            'empleados.edit',
            compact('empleado', 'departamentos', 'turnos', 'usuarios')
        );
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(
    Request $request,
    Empleado $empleado,
    \App\Services\MicrosoftGraphMailService $mail
) {
    $request->validate([
        'codigo_empleado' => 'required|unique:empleados,codigo_empleado,' . $empleado->id,
        'nombre' => 'required',
        'apellido_paterno' => 'required',
        'turno_id' => 'required|exists:turnos,id',
        'user_id' => 'nullable|exists:users,id',
    ]);

    // Guardamos el estatus anterior antes de actualizar
    $estatusAnterior = $empleado->estatus;

    // Si el formulario no manda estatus, conservamos el actual
    $nuevoEstatus = $request->input(
        'estatus',
        $empleado->estatus
    );

    if (
        $nuevoEstatus === 'BAJA' &&
        $estatusAnterior !== 'BAJA' &&
        !auth()->user()->hasAnyRole(['RH', 'Admin', 'Coordinacion'])
    ) {
        return back()->withErrors([
            'estatus' => 'No tienes permiso para dar de baja a un empleado.',
        ])->withInput();
    }

    $empleado->update([
        'codigo_empleado' => $request->codigo_empleado,
        'nombre' => $request->nombre,
        'apellido_paterno' => $request->apellido_paterno,
        'departamento_id' => $request->departamento_id,
        'turno_id' => $request->turno_id,
        'estatus' => $nuevoEstatus,
        'user_id' => $request->user_id,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Notificación de BAJA
    |--------------------------------------------------------------------------
    */

    if (
        $nuevoEstatus === 'BAJA' &&
        $estatusAnterior !== 'BAJA'
    ) {

        $empleado->load([
            'departamento',
            'turno',
        ]);

        $remitente = auth()->user()?->email
            ?? config('services.microsoft.mail_from');

        $html = view('emails.empleados.baja', [
            'empleado' => $empleado,
            'estatusAnterior' => $estatusAnterior,
            'remitente' => $remitente,
        ])->render();

        $destinatariosBaja = \App\Models\User::role(['RH', 'Admin', 'Supervisor', 'Coordinacion'])
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($destinatariosBaja)) {

            $mail->sendHtml(
                $destinatariosBaja,
                'Baja de empleado - ' .
                    $empleado->apellido_paterno . ' ' .
                    $empleado->apellido_materno . ' ' .
                    $empleado->nombre,
                $html
            );
        }
    }

    return redirect()
        ->route('empleados.index')
        ->with('success', 'Empleado actualizado correctamente');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empleado $empleado)
    {
        $empleado->delete();

        return redirect()
            ->route('empleados.index')
            ->with('success', 'Empleado eliminado correctamente');
    }
}

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
    public function index()
    {
        $empleados = Empleado::with([
            'departamento',
            'turno'
        ])
            ->latest()
            ->paginate(10);

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
        return view('empleados.create', compact('departamentos', 'turnos'));
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
                'Alta de nuevo empleado - ' . $empleado->nombre . ' ' . $empleado->apellido_paterno,
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

        $mail->sendHtml(
            'aux.sistemas@dasavena.com',
            'Baja de empleado - ' .
                $empleado->nombre . ' ' .
                $empleado->apellido_paterno,
            $html
        );
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

        return view(
            'empleados.edit',
            compact('empleado', 'departamentos', 'turnos')
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

        $mail->sendHtml(
            'aux.sistemas@dasavena.com',
            'Baja de empleado - ' .
                $empleado->nombre . ' ' .
                $empleado->apellido_paterno,
            $html
        );
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

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
    public function store(Request $request)
    {
        $request->validate([
            'codigo_empleado' => 'required|unique:empleados',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'turno_id' => 'required|exists:turnos,id',
        ]);

        Empleado::create($request->all());

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
    
    public function actualizarEstatus(Request $request, Empleado $empleado)
    {
        $request->validate([
            'estatus' => 'required|in:ACTIVO,INACTIVO,VACACIONES,BAJA',
        ]);

        $empleado->update([
            'estatus' => $request->estatus,
        ]);

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
    public function update(Request $request, Empleado $empleado)
    {
        $request->validate([
            'codigo_empleado' => 'required|unique:empleados,codigo_empleado,' . $empleado->id,
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'turno_id' => 'required|exists:turnos,id',
        ]);

        $empleado->update($request->all());

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

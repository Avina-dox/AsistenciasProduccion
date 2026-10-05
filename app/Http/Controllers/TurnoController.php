<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::withCount(['empleados', 'horarios'])
            ->orderBy('nombre')
            ->paginate(10);

        return view('turnos.index', compact('turnos'));
    }

    public function create()
    {
        return view('turnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:turnos,nombre',
        ]);

        Turno::create($request->only('nombre'));

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno creado correctamente');
    }

    public function edit(Turno $turno)
    {
        return view('turnos.edit', compact('turno'));
    }

    public function update(Request $request, Turno $turno)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:turnos,nombre,' . $turno->id,
        ]);

        $turno->update($request->only('nombre'));

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno actualizado correctamente');
    }

    public function destroy(Turno $turno)
    {
        if ($turno->empleados()->exists()) {

            return back()->with(
                'error',
                'No se puede eliminar este turno porque tiene empleados asignados.'
            );
        }

        $turno->delete();

        return redirect()
            ->route('turnos.index')
            ->with('success', 'Turno eliminado correctamente');
    }
}

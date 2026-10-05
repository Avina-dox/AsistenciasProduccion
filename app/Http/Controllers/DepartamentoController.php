<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::withCount(['empleados', 'horarios'])
            ->orderBy('nombre')
            ->paginate(10);

        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre',
            'descripcion' => 'nullable|string',
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('departamentos.index')
            ->with('success', 'Departamento creado correctamente');
    }

    public function edit(Departamento $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:departamentos,nombre,' . $departamento->id,
            'descripcion' => 'nullable|string',
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'activo' => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('departamentos.index')
            ->with('success', 'Departamento actualizado correctamente');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();

        return redirect()
            ->route('departamentos.index')
            ->with('success', 'Departamento eliminado correctamente');
    }
}

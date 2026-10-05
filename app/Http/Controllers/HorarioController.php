<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Turno;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::with(['turno', 'departamento'])
            ->get()
            ->sortBy([
                fn($horario) => $horario->turno?->nombre,
                fn($horario) => $horario->departamento?->nombre,
            ]);

        return view('horarios.index', compact('horarios'));
    }

    public function create()
    {
        $turnos = Turno::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('horarios.create', compact('turnos', 'departamentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'turno_id' => [
                'required',
                'exists:turnos,id',
                Rule::unique('horarios')->where(
                    fn($query) => $query->where('departamento_id', $request->departamento_id)
                ),
            ],
            'departamento_id' => 'required|exists:departamentos,id',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i',
        ], [
            'turno_id.unique' => 'Ya existe un horario para esta combinación de turno y departamento.',
        ]);

        Horario::create($request->only(
            'turno_id',
            'departamento_id',
            'hora_entrada',
            'hora_salida'
        ));

        return redirect()
            ->route('horarios.index')
            ->with('success', 'Horario creado correctamente');
    }

    public function edit(Horario $horario)
    {
        $turnos = Turno::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('horarios.edit', compact('horario', 'turnos', 'departamentos'));
    }

    public function update(Request $request, Horario $horario)
    {
        $request->validate([
            'turno_id' => [
                'required',
                'exists:turnos,id',
                Rule::unique('horarios')
                    ->where(fn($query) => $query->where('departamento_id', $request->departamento_id))
                    ->ignore($horario->id),
            ],
            'departamento_id' => 'required|exists:departamentos,id',
            'hora_entrada' => 'required|date_format:H:i',
            'hora_salida' => 'required|date_format:H:i',
        ], [
            'turno_id.unique' => 'Ya existe un horario para esta combinación de turno y departamento.',
        ]);

        $horario->update($request->only(
            'turno_id',
            'departamento_id',
            'hora_entrada',
            'hora_salida'
        ));

        return redirect()
            ->route('horarios.index')
            ->with('success', 'Horario actualizado correctamente');
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();

        return redirect()
            ->route('horarios.index')
            ->with('success', 'Horario eliminado correctamente');
    }
}

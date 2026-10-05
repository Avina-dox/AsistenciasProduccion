@extends('emails.layout')

@section('titulo', 'Exceso de faltas')
@section('icono', '⚠️')

@section('content')

    <p style="margin:0 0 18px; font-size:14px; color:#6E6274; line-height:1.6;">
        Un empleado ha acumulado más de {{ $limiteFaltas }} faltas en el mes dentro del Sistema de Asistencias.
    </p>

    @include('emails.partials.info-table', ['filas' => [
        'Empleado' => trim($empleado->apellido_paterno . ' ' . $empleado->apellido_materno . ' ' . $empleado->nombre),
        'Código' => $empleado->codigo_empleado,
        'Departamento' => $empleado->departamento?->nombre,
        'Turno' => $empleado->turno?->nombre,
        'Mes' => $mes->translatedFormat('F Y'),
        'Total de faltas en el mes' => $totalFaltas,
    ]])

    <p style="margin:20px 0 0; font-size:13px; color:#6E6274; line-height:1.6;">
        Se recomienda dar seguimiento con el empleado para conocer la causa de las faltas acumuladas.
    </p>

@endsection

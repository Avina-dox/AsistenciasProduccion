@extends('emails.layout')

@section('titulo', 'Baja de empleado')
@section('icono', '📤')

@section('content')

    <p style="margin:0 0 18px; font-size:14px; color:#6E6274; line-height:1.6;">
        Se ha registrado la baja de un empleado en el Sistema de Asistencias.
    </p>

    @include('emails.partials.info-table', ['filas' => [
        'Nombre' => trim($empleado->apellido_paterno . ' ' . $empleado->apellido_materno . ' ' . $empleado->nombre),
        'Código' => $empleado->codigo_empleado,
        'Departamento' => $empleado->departamento?->nombre,
        'Turno' => $empleado->turno?->nombre,
        'Estatus anterior' => $estatusAnterior,
        'Nuevo estatus' => 'BAJA',
    ]])

    <p style="margin:20px 0 0; font-size:13px; color:#6E6274;">
        Baja registrada por <strong style="color:#2B2030;">{{ $remitente }}</strong>
    </p>

@endsection

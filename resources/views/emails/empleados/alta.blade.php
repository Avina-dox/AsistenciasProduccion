@extends('emails.layout')

@section('titulo', 'Alta de nuevo empleado')
@section('icono', '🧑‍🍳')

@section('content')

    <p style="margin:0 0 18px; font-size:14px; color:#6E6274; line-height:1.6;">
        Se ha registrado un nuevo empleado en el Sistema de Asistencias.
    </p>

    @include('emails.partials.info-table', ['filas' => [
        'Nombre' => trim($empleado->nombre . ' ' . $empleado->apellido_paterno),
        'Código' => $empleado->codigo_empleado,
        'Departamento' => $empleado->departamento?->nombre,
        'Turno' => $empleado->turno?->nombre,
        'Fecha de ingreso' => $empleado->fecha_ingreso,
        'Estatus' => $empleado->estatus,
    ]])

    <p style="margin:20px 0 0; font-size:13px; color:#6E6274;">
        Registro realizado por <strong style="color:#2B2030;">{{ $remitente }}</strong>
    </p>

@endsection

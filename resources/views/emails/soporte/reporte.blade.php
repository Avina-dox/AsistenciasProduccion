@extends('emails.layout')

@section('titulo', 'Reporte de problema - IT')
@section('icono', '🛠️')

@section('content')

    <p style="margin:0 0 18px; font-size:14px; color:#6E6274; line-height:1.6;">
        Un usuario reportó un problema desde el Sistema de Asistencias a través del chat de soporte.
    </p>

    @include('emails.partials.info-table', ['filas' => [
        'Reportado por' => $usuario->name,
        'Correo' => $usuario->email,
        'Fecha' => now()->format('d/m/Y H:i'),
        'Página' => $pagina,
    ]])

    <p style="margin:20px 0 0; font-size:13px; color:#6E6274; font-weight:600;">
        Descripción del problema
    </p>

    <p style="margin:8px 0 0; padding:14px 16px; background:#FBF8F3; border:1px solid rgba(43,32,48,0.1); border-radius:10px; font-size:14px; color:#2B2030; white-space:pre-wrap; line-height:1.5;">{{ $mensaje }}</p>

@endsection

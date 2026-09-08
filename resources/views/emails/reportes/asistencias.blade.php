@extends('emails.layout')

@section('titulo', 'Reporte de asistencias')
@section('icono', '📊')

@section('content')

    <p style="margin:0 0 18px; font-size:14px; color:#6E6274; line-height:1.6;">
        Se ha generado un reporte de asistencias. Lo encontrarás adjunto en formato PDF.
    </p>

    @include('emails.partials.info-table', ['filas' => [
        'Desde' => \Carbon\Carbon::parse($desde)->format('d/m/Y'),
        'Hasta' => \Carbon\Carbon::parse($hasta)->format('d/m/Y'),
        'Generado por' => $remitente,
    ]])

@endsection

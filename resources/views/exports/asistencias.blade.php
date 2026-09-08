@php
    $colorasEstatus = [
        'A' => 'BBF7D0',
        'F' => 'FECACA',
        'R' => 'FEF3C7',
        'V' => 'DBEAFE',
        'I' => 'E9D5FF',
        'PCG' => 'CFFAFE',
        'PSG' => 'E5E7EB',
        'O' => 'FBCFE8',
    ];

    $totalColumnas = count($dias) + 15;
@endphp
<table>

    <tr>
        <td colspan="{{ $totalColumnas }}"
            style="font-size:18px;
                   font-weight:bold;
                   text-align:center;
                   background:#5B21B6;
                   color:white;">

            DASAVENA GOURMET

        </td>
    </tr>

    <tr>
        <td colspan="{{ $totalColumnas }}"
            style="font-size:14px;
                   font-weight:bold;
                   text-align:center;">

            REPORTE DE ASISTENCIAS

        </td>
    </tr>

    <tr></tr>

    <tr>

        <td colspan="2">
            <strong>Desde:</strong>
        </td>

        <td colspan="4">
            {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
        </td>

        <td colspan="2">
            <strong>Hasta:</strong>
        </td>

        <td colspan="4">
            {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        </td>

    </tr>

    <tr></tr>

    <thead>

        <tr>

            <th style="background:#EDE9FE;font-weight:bold;">
                Código
            </th>

            <th style="background:#EDE9FE;font-weight:bold;">
                Empleado
            </th>

            <th style="background:#EDE9FE;font-weight:bold;">
                Departamento
            </th>

            <th style="background:#EDE9FE;font-weight:bold;">
                Puesto
            </th>

            <th style="background:#EDE9FE;font-weight:bold;">
                Turno
            </th>

            @foreach($dias as $fecha)

            <th
                style="
        background:#DDD6FE;
        text-align:center;
        font-weight:bold;
    ">

                {{ $fecha->format('d') }}

            </th>

            @endforeach
            <th style="background:#BBF7D0">A</th>

            <th style="background:#FECACA">F</th>

            <th style="background:#FEF3C7">R</th>

            <th style="background:#DBEAFE">V</th>

            <th style="background:#E9D5FF">INC</th>

            <th style="background:#CFFAFE">PCG</th>

            <th style="background:#E5E7EB">PSG</th>

            <th style="background:#FBCFE8">O</th>

            <th style="background:#DCFCE7">HE</th>

            <th style="background:#FDE68A">Retardo (min)</th>

        </tr>

    </thead>

    <tbody>
        @foreach($empleados as $empleado)

        @php

        $totales = app(App\Services\AsistenciaService::class)
        ->obtenerTotalesEmpleado(
        $empleado,
        $desde,
        $hasta
        );

        $nombreCompleto = trim(
            $empleado->apellido_paterno . ' ' .
            $empleado->apellido_materno . ' ' .
            $empleado->nombre
        );

        @endphp

        <tr>

            <td>

                {{ $empleado->codigo_empleado }}

            </td>

            <td style="text-align:left;">

                {{ $nombreCompleto }}

            </td>

            <td style="text-align:left;">

                {{ $empleado->departamento?->nombre ?? '' }}

            </td>

            <td style="text-align:left;">

                {{ $empleado->puesto ?? '' }}

            </td>

            <td style="text-align:left;">

                {{ $empleado->turno?->nombre ?? '' }}

            </td>

            @foreach($dias as $fecha)

            @php

            $asistencia = $empleado->asistencias

            ->where(
            'fecha',
            $fecha->format('Y-m-d')
            )

            ->first();

            $codigo = $asistencia?->estatus?->codigo;

            $fondo = $colorasEstatus[$codigo] ?? null;

            @endphp

            <td style="text-align:center;{{ $fondo ? 'background:#' . $fondo . ';' : '' }}">
                {{ $codigo === 'I' ? 'INC' : ($codigo ?? '') }}
            </td>

            @endforeach

            <td>

                {{ $totales['A'] }}

            </td>

            <td>

                {{ $totales['F'] }}

            </td>

            <td>

                {{ $totales['R'] }}

            </td>

            <td>

                {{ $totales['V'] }}

            </td>

            <td>

                {{ $totales['I'] }}

            </td>

            <td>

                {{ $totales['PCG'] }}

            </td>

            <td>

                {{ $totales['PSG'] }}

            </td>

            <td>

                {{ $totales['O'] }}

            </td>

            <td>

                {{ number_format(
                $totales['HE'],
                2
            ) }}

            </td>

            <td>

                {{ $totales['RETARDO_MIN'] }}

            </td>

        </tr>

        @endforeach
    </tbody>

</table>

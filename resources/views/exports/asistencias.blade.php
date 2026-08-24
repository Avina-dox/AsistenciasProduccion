<table>

    <tr>
        <td colspan="{{ count($dias)+11 }}"
            style="font-size:18px;
                   font-weight:bold;
                   text-align:center;
                   background:#5B21B6;
                   color:white;">

            DASAVENA GOURMET

        </td>
    </tr>

    <tr>
        <td colspan="{{ count($dias)+11 }}"
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

            <th style="background:#E9D5FF">I</th>

            <th style="background:#CFFAFE">PCG</th>

            <th style="background:#E5E7EB">PSG</th>

            <th style="background:#FBCFE8">O</th>

            <th style="background:#DCFCE7">HE</th>

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

        @endphp

        <tr>

            <td>

                {{ $empleado->codigo_empleado }}

            </td>

            <td>

                {{ $empleado->nombre }}

            </td>

            @foreach($dias as $fecha)

            @php

            $asistencia = $empleado->asistencias

            ->where(
            'fecha',
            $fecha->format('Y-m-d')
            )

            ->first();

            @endphp

            <td style="text-align:center;">
                {{ ($asistencia?->estatus?->codigo ?? '') === 'I'
        ? 'INC'
        : ($asistencia?->estatus?->codigo ?? '') }}
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

        </tr>

        @endforeach
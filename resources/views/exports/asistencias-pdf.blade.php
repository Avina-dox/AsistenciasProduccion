<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Reporte de Asistencias</title>

    <style>
        @page {
            size: letter landscape;
            margin: 25px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .empresa {
            background: #5B21B6;
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 8px;
        }

        .titulo {
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
        }

        .periodo {
            margin-top: 8px;
            font-size: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background: #EDE9FE;
            font-weight: bold;
        }

        .empleado {
            text-align: left;
        }

        .codigo {
            width: 55px;
        }

        .nombre {
            width: 110px;
        }

        .dia {
            width: 20px;
        }

        .total {
            width: 25px;
            font-weight: bold;
        }

        .inc {
            background: #E9D5FF;
        }

        .a {
            background: #BBF7D0;
        }

        .f {
            background: #FECACA;
        }

        .r {
            background: #FEF3C7;
        }

        .v {
            background: #DBEAFE;
        }

        .pcg {
            background: #CFFAFE;
        }

        .psg {
            background: #E5E7EB;
        }

        .o {
            background: #FBCFE8;
        }

        .he {
            background: #DCFCE7;
        }

        .footer {
            margin-top: 10px;
            font-size: 7px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="header">

        <div class="empresa">
            DASAVENA GOURMET
        </div>

        <div class="titulo">
            REPORTE DE ASISTENCIAS
        </div>

        <div class="periodo">
            <strong>Desde:</strong>
            {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}

            &nbsp;&nbsp;&nbsp;

            <strong>Hasta:</strong>
            {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
        </div>

    </div>


    <table>

        <thead>

            <tr>

                <th class="codigo">
                    Código
                </th>

                <th class="nombre">
                    Empleado
                </th>

                @foreach($dias as $fecha)

                    <th class="dia">
                        {{ $fecha->format('d') }}
                    </th>

                @endforeach

                <th class="total a">
                    A
                </th>

                <th class="total f">
                    F
                </th>

                <th class="total r">
                    R
                </th>

                <th class="total v">
                    V
                </th>

                <th class="total inc">
                    INC
                </th>

                <th class="total pcg">
                    PCG
                </th>

                <th class="total psg">
                    PSG
                </th>

                <th class="total o">
                    O
                </th>

                <th class="total he">
                    HE
                </th>

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

                    <td class="codigo">
                        {{ $empleado->codigo_empleado }}
                    </td>

                    <td class="empleado">
                        {{ $empleado->nombre }}
                        {{ $empleado->apellido_paterno }}
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

                        @endphp

                        <td>

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
                        {{ number_format($totales['HE'], 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <div class="footer">

        Generado el
        {{ now()->format('d/m/Y H:i') }}

    </div>

</body>

</html>
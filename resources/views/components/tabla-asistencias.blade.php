<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

{{-- TODO DEBE IR DENTRO DE ESTE DIV PRINCIPAL PARA LIVEWIRE --}}
<div class="min-h-screen p-6 space-y-6" style="background-color: #f8fafc;">

    {{-- ESTILOS DEL DISEÑO QUE TE GUSTÓ --}}
    <style>
        .modern-font {
            font-family: 'Inter', system-ui, sans-serif;
        }

        .mono-font {
            font-family: 'Roboto Mono', 'Courier New', monospace;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .clean-panel {
            background: #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e5e7eb;
            border-top: 4px solid #6a2c75;
            /* Acento morado principal */
        }

        .row-animate {
            animation: fadeInUp 0.4s ease both;
        }

        .select-clean {
            appearance: none;
            -webkit-appearance: none;
            background-image: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .select-clean:hover {
            border-color: #6a2c75 !important;
            box-shadow: 0 0 0 2px rgba(106, 44, 117, 0.1);
        }

        .select-clean:focus {
            outline: none;
            border-color: #6a2c75 !important;
            box-shadow: 0 0 0 3px rgba(106, 44, 117, 0.2);
        }

        .tr-clean:hover td,
        .tr-clean:hover .cell-sticky {
            background: rgba(106, 44, 117, 0.03) !important;
            transition: background 0.2s ease;
        }

        /* Colores personalizados del diseño */
        .status-A {
            background: rgba(0, 255, 120, 0.15);
            border-color: rgba(0, 255, 120, 0.4);
            color: #00994a;
        }

        .status-F {
            background: rgba(255, 50, 80, 0.15);
            border-color: rgba(255, 50, 80, 0.4);
            color: #d11f36;
        }

        .status-V {
            background: rgba(0, 180, 255, 0.15);
            border-color: rgba(0, 180, 255, 0.4);
            color: #007bb5;
        }

        .status-R {
            background: rgba(255, 180, 0, 0.15);
            border-color: rgba(255, 180, 0, 0.4);
            color: #b37e00;
        }

        .status-I {
            background: rgba(180, 80, 255, 0.15);
            border-color: rgba(180, 80, 255, 0.4);
            color: #7a25cc;
        }

        .status-PCG {
            background: rgba(80, 120, 255, 0.15);
            border-color: rgba(80, 120, 255, 0.4);
            color: #3b59bf;
        }

        .status-PSG {
            background: rgba(140, 160, 200, 0.15);
            border-color: rgba(140, 160, 200, 0.4);
            color: #52678c;
        }

        .stat-badge {
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .clean-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .clean-scroll::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }

        .clean-scroll::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .clean-scroll::-webkit-scrollbar-thumb:hover {
            background: #6a2c75;
        }
    </style>

    {{-- ENCABEZADO --}}
    <div class="flex items-center gap-4 mb-2">
        <div class="w-1.5 h-8 rounded-full" style="background-color: #6a2c75;"></div>
        <h1 class="modern-font text-2xl font-bold tracking-wide uppercase" style="color: #1e293b;">
            Control de Asistencia
        </h1>
        <div class="flex-1 h-px bg-gray-200"></div>
        <span class="mono-font text-xs font-semibold px-2 py-1 rounded-md" style="background-color: rgba(106, 44, 117, 0.1); color: #6a2c75;">
            ACTIVO
        </span>
    </div>
    {{-- NAVEGACIÓN MES/AÑO --}}

    <div class="flex items-center justify-between mb-6">

        <div class="flex items-center gap-4">

            {{-- BOTÓN ATRÁS --}}

            <button
                wire:click="mesAnterior"
                class="
                px-4
                py-2
                rounded-lg
                bg-white
                border
                shadow-sm
                hover:bg-gray-100
                transition
            ">

                ←

            </button>

            {{-- MES ACTUAL --}}

            <div class="text-center">

                <h2 class="text-2xl font-bold modern-font text-gray-800">

                    {{ \Carbon\Carbon::create($anio, $mes)->translatedFormat('F Y') }}

                </h2>

            </div>

            {{-- BOTÓN SIGUIENTE --}}

            <button
                wire:click="mesSiguiente"
                class="
                px-4
                py-2
                rounded-lg
                bg-white
                border
                shadow-sm
                hover:bg-gray-100
                transition
            ">

                →

            </button>

        </div>

    </div>
    {{-- TABLA PRINCIPAL --}}
    <div class="clean-panel relative overflow-hidden">

        <div class="flex items-center gap-3 px-5 py-3 border-b bg-gray-50 border-gray-100">
            <div class="flex gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
            </div>
            <span class="modern-font text-sm font-medium text-gray-500">Registro Diario de Empleados</span>
        </div>

        <div class="overflow-x-auto clean-scroll">
            <table class="min-w-full border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="sticky left-0 z-10 px-5 py-3.5 text-left whitespace-nowrap bg-gray-50 border-b border-r border-gray-200 shadow-[1px_0_0_0_#e5e7eb]">
                            <span class="modern-font text-xs font-bold tracking-wider uppercase text-gray-500">
                                Empleado
                            </span>
                        </th>
                        @for($dia = 1; $dia <= $diasMes; $dia++)
                            <th class="px-2 py-3.5 text-center min-w-[52px] border-b border-gray-200">
                            <span class="mono-font text-xs font-bold" style="color: #6a2c75;">
                                {{ str_pad($dia, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            </th>
                            @endfor
                    </tr>
                </thead>

                <tbody>
                    @foreach($empleados as $i => $empleado)
                    <tr wire:key="emp-{{ $empleado->id }}" class="tr-clean row-animate border-b border-gray-100" style="animation-delay: {{ $i * 0.04 }}s;">

                        <td class="cell-sticky sticky left-0 z-10 px-5 py-3 whitespace-nowrap bg-white border-r border-gray-200 shadow-[1px_0_0_0_#e5e7eb]">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-5 rounded-full" style="background-color: #6a2c75;"></div>
                                <span class="modern-font font-semibold text-sm text-gray-800">
                                    {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
                                </span>
                            </div>
                        </td>

                        @for($dia = 1; $dia <= $diasMes; $dia++)
                            @php
                            $fecha=Carbon\Carbon::create($anio, $mes, $dia)->format('Y-m-d');
                            $asistencia = $empleado->asistencias->where('fecha', $fecha)->first();

                            // TU LÓGICA DE IF/ELSE, PERO CON LAS CLASES DEL DISEÑO HERMOSO
                            $statusClass = '';

                            if ($asistencia?->estatus?->codigo == 'A') {
                            $statusClass = 'status-A';
                            } elseif ($asistencia?->estatus?->codigo == 'F') {
                            $statusClass = 'status-F';
                            } elseif ($asistencia?->estatus?->codigo == 'V') {
                            $statusClass = 'status-V';
                            } elseif ($asistencia?->estatus?->codigo == 'R') {
                            $statusClass = 'status-R';
                            } elseif ($asistencia?->estatus?->codigo == 'I') {
                            $statusClass = 'status-I';
                            } elseif ($asistencia?->estatus?->codigo == 'PCG') {
                            $statusClass = 'status-PCG';
                            } elseif ($asistencia?->estatus?->codigo == 'PSG') {
                            $statusClass = 'status-PSG';
                            }
                            @endphp

                            <td class="px-1.5 py-2 text-center">
                                {{-- TU SELECT ORIGINAL QUE SÍ FUNCIONA CON LA APARIENCIA NUEVA --}}
                                <select

                                    wire:key="sel-{{ $empleado->id }}-{{ $anio }}-{{ $mes }}-{{ $dia }}"

                                    wire:change="
        actualizarAsistencia(
            {{ $empleado->id }},
            '{{ $fecha }}',
            $event.target.value
        )
    "

                                    class="
        select-clean
        mono-font
        w-full
        rounded-md
        px-1
        py-1.5
        text-xs
        font-bold
        text-center
        border
        {{ $statusClass }}
    "

                                    style="
        min-width: 44px;
        {{ !$statusClass
            ? 'background: #f8fafc; border-color: #e2e8f0; color: #94a3b8;'
            : ''
        }}
    ">

                                    <option value="">
                                        —
                                    </option>

                                    @foreach($estatus as $item)

                                    <option
                                        value="{{ $item->id }}"
                                        @selected(
                                        $asistencia?->estatus_asistencia_id
                                        == $item->id
                                        )>

                                        {{ $item->codigo }}

                                    </option>

                                    @endforeach

                                </select>
                            </td>
                            @endfor

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- LEYENDA (COLORES) --}}
    <div class="flex flex-wrap gap-3 px-1 mt-4 mb-2">
        @php
        $leyenda = [
        ['label' => 'Asistencia', 'class' => 'status-A'],
        ['label' => 'Falta', 'class' => 'status-F'],
        ['label' => 'Vacaciones', 'class' => 'status-V'],
        ['label' => 'Retardo', 'class' => 'status-R'],
        ['label' => 'Incapacidad', 'class' => 'status-I'],
        ['label' => 'PCG', 'class' => 'status-PCG'],
        ['label' => 'PSG', 'class' => 'status-PSG'],
        ];
        @endphp

        @foreach($leyenda as $item)
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border {{ $item['class'] }} bg-white stat-badge">
            <div class="w-2 h-2 rounded-full" style="background: currentColor;"></div>
            <span class="modern-font text-xs font-bold">{{ $item['label'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- RESUMEN MENSUAL --}}
    <div class="clean-panel relative overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b bg-gray-50 border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-5 rounded-full" style="background-color: #6a2c75;"></div>
                <span class="modern-font text-lg font-bold text-gray-800">
                    Resumen Mensual
                </span>
            </div>
        </div>

        <div class="overflow-x-auto clean-scroll">
            <table class="w-full text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3.5 text-left"><span class="modern-font text-xs font-bold tracking-wider uppercase text-gray-500">Empleado</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-F px-2 py-1 rounded-md border bg-white">Faltas</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-R px-2 py-1 rounded-md border bg-white">Retardos</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-V px-2 py-1 rounded-md border bg-white">Vacaciones</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-A px-2 py-1 rounded-md border bg-white">Asistencias</span></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($empleados as $i => $empleado)
                    @php

                    $faltas = $empleado->asistencias
                    ->filter(function ($a) {

                    return $a->estatus?->codigo == 'F'
                    && \Carbon\Carbon::parse($a->fecha)->month == $this->mes
                    && \Carbon\Carbon::parse($a->fecha)->year == $this->anio;

                    })
                    ->count();

                    $retardos = $empleado->asistencias
                    ->filter(function ($a) {

                    return $a->estatus?->codigo == 'R'
                    && \Carbon\Carbon::parse($a->fecha)->month == $this->mes
                    && \Carbon\Carbon::parse($a->fecha)->year == $this->anio;

                    })
                    ->count();

                    $vacaciones = $empleado->asistencias
                    ->filter(function ($a) {

                    return $a->estatus?->codigo == 'V'
                    && \Carbon\Carbon::parse($a->fecha)->month == $this->mes
                    && \Carbon\Carbon::parse($a->fecha)->year == $this->anio;

                    })
                    ->count();

                    $asistenciasTotal = $empleado->asistencias
                    ->filter(function ($a) {

                    return $a->estatus?->codigo == 'A'
                    && \Carbon\Carbon::parse($a->fecha)->month == $this->mes
                    && \Carbon\Carbon::parse($a->fecha)->year == $this->anio;

                    })
                    ->count();

                    @endphp

                    <tr wire:key="sum-{{ $empleado->id }}" class="tr-clean row-animate border-b border-gray-100 bg-white" style="animation-delay: {{ $i * 0.05 }}s;">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="mono-font text-xs font-semibold text-gray-400">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="modern-font font-semibold text-gray-800">
                                    {{ $empleado->nombre }} {{ $empleado->apellido_paterno }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-F modern-font text-base font-bold bg-white stat-badge">{{ $faltas }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-R modern-font text-base font-bold bg-white stat-badge">{{ $retardos }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-V modern-font text-base font-bold bg-white stat-badge">{{ $vacaciones }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-A modern-font text-base font-bold bg-white stat-badge">{{ $asistenciasTotal }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
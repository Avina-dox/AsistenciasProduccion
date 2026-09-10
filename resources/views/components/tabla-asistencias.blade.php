<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>


<div class="min-h-screen p-4 sm:p-6 space-y-6 bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3]">


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

        .modern-font {
            font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean', Questrial, 'Avenir Next', sans-serif;
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
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(69, 25, 63, 0.05), 0 2px 4px -1px rgba(69, 25, 63, 0.03);
            border: 1px solid rgba(43, 32, 48, 0.08);
            border-top: 3px solid transparent;
            border-image: linear-gradient(90deg, #B6A644, #6A2C75, #B6A644) 1;
            transition: box-shadow .35s ease;
        }

        .clean-panel:hover {
            box-shadow: 0 12px 28px -10px rgba(69, 25, 63, 0.18), 0 4px 10px -4px rgba(69, 25, 63, 0.08);
        }

        .row-animate {
            animation: fadeInUp 0.4s ease both;
        }

        .section-animate {
            animation: fadeInUp 0.55s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes swipeHint {

            0%,
            100% {
                transform: translateX(0);
                opacity: .55;
            }

            50% {
                transform: translateX(5px);
                opacity: 1;
            }
        }

        .swipe-hint {
            animation: swipeHint 1.3s ease-in-out infinite;
        }

        @keyframes pulseSoft {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, .5);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        .pulse-soft {
            animation: pulseSoft 2s infinite;
        }

        .btn-modern {
            position: relative;
            overflow: hidden;
        }

        .btn-modern::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .25), transparent);
            transform: translateX(-100%);
            transition: transform .6s ease;
        }

        .btn-modern:hover::after {
            transform: translateX(100%);
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

        /* Colores de estatus — paleta refinada, coherente con el resto del sistema */
        .status-A {
            background: #ECFDF5;
            border-color: rgba(5, 150, 105, 0.35);
            color: #059669;
        }

        .status-F {
            background: #FEF2F2;
            border-color: rgba(220, 38, 38, 0.35);
            color: #DC2626;
        }

        .status-V {
            background: #EFF8FF;
            border-color: rgba(2, 132, 199, 0.35);
            color: #0284C7;
        }

        .status-R {
            background: #FBF6E4;
            border-color: rgba(182, 166, 68, 0.5);
            color: #92752F;
        }

        .status-O {
            background: #FFF4ED;
            border-color: rgba(194, 65, 12, 0.35);
            color: #C2410C;
        }

        .status-I {
            background: #F5F0FA;
            border-color: rgba(106, 44, 117, 0.35);
            color: #6A2C75;
        }

        .status-PCG {
            background: #EEF2FF;
            border-color: rgba(67, 56, 202, 0.35);
            color: #4338CA;
        }

        .status-PSG {
            background: #F1F5F9;
            border-color: rgba(71, 85, 105, 0.35);
            color: #475569;
        }

        .status-S {
            background: #FFF1F2;
            border-color: rgba(159, 18, 57, 0.35);
            color: #9F1239;
        }

        .stat-badge {
            box-shadow: 0 1px 2px rgba(43, 32, 48, 0.06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-badge:hover {
            transform: scale(1.12);
            box-shadow: 0 4px 10px rgba(43, 32, 48, 0.12);
        }

        .clean-scroll::-webkit-scrollbar {
            height: 6px;
        }

        .clean-scroll::-webkit-scrollbar-track {
            background: #F3EDE3;
            border-radius: 4px;
        }

        .clean-scroll::-webkit-scrollbar-thumb {
            background: #D9D0C6;
            border-radius: 4px;
        }

        .clean-scroll::-webkit-scrollbar-thumb:hover {
            background: #6a2c75;
        }
    </style>

    {{-- ENCABEZADO --}}
    <div class="section-animate flex flex-wrap items-center gap-3 sm:gap-4 mb-2" style="animation-delay: .05s;">
        <div class="w-1.5 h-7 sm:h-8 rounded-full shrink-0" style="background-color: #6a2c75;"></div>
        <h1 class="modern-font text-lg sm:text-2xl font-bold tracking-wide uppercase" style="color: #2B2030;">
            Control de Asistencia
        </h1>
        <div class="hidden sm:block flex-1 h-px bg-[#2B2030]/10"></div>
        <span class="mono-font inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-md" style="background-color: rgba(106, 44, 117, 0.1); color: #6a2c75;">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 pulse-soft"></span>
            ACTIVO
        </span>
    </div>
    {{-- NAVEGACIÓN MES/AÑO --}}

    <div class="clean-panel section-animate p-5 mb-6" style="animation-delay: .1s;">

    <div class="flex flex-col lg:flex-row lg:items-end gap-5">

        {{-- TÍTULO --}}

        <div class="flex-1">

            <div class="flex items-center gap-3 mb-2">

                <div
                    class="w-1.5 h-6 rounded-full"
                    style="background-color:#6a2c75;"
                ></div>

                <h2 class="modern-font text-lg font-bold text-[#2B2030]">
                    Periodo de asistencia
                </h2>

            </div>

            <p class="text-sm text-[#6E6274]">
                Selecciona el periodo que deseas consultar.
            </p>

        </div>


        {{-- DESDE --}}

        <div class="w-full lg:w-56">

            <label
                for="desde"
                class="block text-sm font-semibold text-[#2B2030] mb-2"
            >
                Desde
            </label>

            <input
                id="desde"
                type="date"
                wire:model.live="desde"
                class="modern-font w-full border border-[#2B2030]/15 rounded-xl px-3 py-2.5 bg-white shadow-sm focus:border-[#6A2C75] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/15"
            >

        </div>


        {{-- HASTA --}}

        <div class="w-full lg:w-56">

            <label
                for="hasta"
                class="block text-sm font-semibold text-[#2B2030] mb-2"
            >
                Hasta
            </label>

            <input
                id="hasta"
                type="date"
                wire:model.live="hasta"
                min="{{ $desde }}"
                class="modern-font w-full border border-[#2B2030]/15 rounded-xl px-3 py-2.5 bg-white shadow-sm focus:border-[#6A2C75] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/15"
            >

        </div>

    </div>


    {{-- PERIODO SELECCIONADO --}}

    <div class="mt-4 flex items-center gap-2 text-sm text-[#6A2C75]">

        <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3.5" y="5" width="17" height="15" rx="2.5" />
            <path d="M3.5 9.5h17" />
            <path d="M8 3v3.2M16 3v3.2" />
        </svg>

        <span class="font-semibold">
            Periodo:
        </span>

        <span>
            {{ \Carbon\Carbon::parse($desde)->translatedFormat('d M Y') }}
            →
            {{ \Carbon\Carbon::parse($hasta)->translatedFormat('d M Y') }}
        </span>

    </div>

</div>

        
        <div class="section-animate grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4" style="animation-delay: .15s;">

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-[#2B2030] tracking-wide">

                    Departamento

                </label>

                <select
                    wire:model.live="departamento_id"
                    class="modern-font w-full border border-[#2B2030]/15 rounded-xl px-3 py-2 bg-white shadow-sm hover:shadow-md focus:border-[#6A2C75] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/15 transition-all duration-300">

                    <option value="">Todos</option>

                    @foreach($departamentos as $departamento)

                    <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>

                    @endforeach

                </select>

            </div>

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-[#2B2030] tracking-wide">

                    Turno

                </label>

                <select
                    wire:model.live="turno_id"
                    class="modern-font w-full border border-[#2B2030]/15 rounded-xl px-3 py-2 bg-white shadow-sm hover:shadow-md focus:border-[#6A2C75] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/15 transition-all duration-300">

                    <option value="">Todos</option>

                    @foreach($turnos as $turno)

                    <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>

                    @endforeach

                </select>

            </div>

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-[#2B2030] tracking-wide">

                    Estado

                </label>

                <select
                    wire:model.live="estatusEmpleado"
                    class="modern-font w-full border border-[#2B2030]/15 rounded-xl px-3 py-2 bg-white shadow-sm hover:shadow-md focus:border-[#6A2C75] focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/15 transition-all duration-300">

                    <option value="ACTIVO">Activos</option>
                    <option value="INACTIVO">Inactivos</option>
                    <option value="BAJA">Baja</option>
                    <option value="TODOS">Todos</option>

                </select>

            </div>

        </div>

        <div class="section-animate flex flex-col sm:flex-row flex-wrap gap-3 mb-2" style="animation-delay: .2s;">

            <a
                href="{{ route('asistencias.exportar',[
                    'desde'=>$desde,
                    'hasta'=>$hasta,
                    'departamento'=>$departamento_id,
                    'turno'=>$turno_id,
                    'estatus'=>$estatusEmpleado
                ]) }}"
                class="btn-modern group modern-font w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:shadow-lg hover:shadow-emerald-500/25 hover:-translate-y-0.5 active:scale-95 active:translate-y-0 transition-all duration-300">

                <svg class="h-4 w-4 shrink-0 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3.5" y="4" width="17" height="16" rx="2" />
                    <path d="M3.5 9.5h17M9.5 4v16M15 9.5v10" />
                </svg>

                Exportar Excel

            </a>

            <a
                href="{{ route('asistencias.pdf', [
                    'desde' => $desde,
                    'hasta' => $hasta,
                    'departamento' => $departamento_id,
                    'turno' => $turno_id,
                    'estatus' => $estatusEmpleado
                ]) }}"
                class="btn-modern group modern-font w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-red-600 to-rose-500 hover:from-red-500 hover:to-rose-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:shadow-lg hover:shadow-red-500/25 hover:-translate-y-0.5 active:scale-95 active:translate-y-0 transition-all duration-300"
            >
                <svg class="h-4 w-4 shrink-0 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 3.5h7l4 4v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-16a1 1 0 0 1 1-1Z" />
                    <path d="M14 3.5v4h4" />
                    <path d="M9 13h6M9 16.5h6" />
                </svg>

                Generar PDF
            </a>

            <form
                action="{{ route('asistencias.enviarPdf') }}"
                method="POST"
                class="w-full sm:w-auto"
            >
                @csrf

                <input
                    type="hidden"
                    name="desde"
                    value="{{ $desde }}"
                >

                <input
                    type="hidden"
                    name="hasta"
                    value="{{ $hasta }}"
                >

                <input
                    type="hidden"
                    name="departamento"
                    value="{{ $departamento_id }}"
                >

                <input
                    type="hidden"
                    name="turno"
                    value="{{ $turno_id }}"
                >

                <input
                    type="hidden"
                    name="estatus"
                    value="{{ $estatusEmpleado }}"
                >

                <button
                    type="submit"
                    class="btn-modern group modern-font w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#6A2C75] to-[#45193F] hover:from-[#7d3489] hover:to-[#54204b] text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-sm hover:shadow-lg hover:shadow-[#6A2C75]/25 hover:-translate-y-0.5 active:scale-95 active:translate-y-0 transition-all duration-300"
                >
                    <svg class="h-4 w-4 shrink-0 transition-transform duration-300 group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3.5" y="5.5" width="17" height="13" rx="2" />
                        <path d="m4 6.5 8 6.5 8-6.5" />
                    </svg>

                    Enviar PDF a RH
                </button>
            </form>

        </div>
    {{-- TABLA PRINCIPAL --}}
    <div class="clean-panel section-animate relative overflow-hidden" style="animation-delay: .25s;">

        <div class="flex items-center gap-3 px-5 py-3 border-b border-[#2B2030]/8" style="background: #FBF8F3;">
            <div class="flex gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400 pulse-soft"></div>
            </div>
            <span class="modern-font text-sm font-medium text-[#6E6274]">Registro Diario de Empleados</span>
        </div>

        <p class="sm:hidden flex items-center gap-1.5 px-5 py-2 text-xs text-[#6E6274] border-b border-[#2B2030]/8">
            <svg class="h-3.5 w-3.5 swipe-hint" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 6 6 6-6 6" />
            </svg>
            Desliza horizontalmente para ver todos los días
        </p>

        <div class="overflow-x-auto clean-scroll">
            <table class="min-w-full border-collapse text-sm text-[#2B2030]">
                <thead>
                    <tr style="background: #FBF8F3;">
                        <th class="sticky left-0 z-10 px-5 py-3.5 text-left whitespace-nowrap border-b border-r border-[#2B2030]/10 shadow-[1px_0_0_0_rgba(43,32,48,0.08)]" style="background: #FBF8F3;">
                            <span class="modern-font text-xs font-bold tracking-wider uppercase text-[#6E6274]">
                                Empleado
                            </span>
                        </th>
                        @foreach($this->dias as $fecha)

                        <th class="px-2 py-3.5 text-center min-w-[52px] border-b border-[#2B2030]/10">

                            <span class="mono-font text-xs font-bold" style="color:#6a2c75;">

                                {{ $fecha->format('d') }}

                            </span>

                        </th>

                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($empleados as $i => $empleado)
                    <tr wire:key="emp-{{ $empleado->id }}" class="tr-clean row-animate border-b border-[#2B2030]/6" style="animation-delay: {{ $i * 0.04 }}s;">

                        <td class="cell-sticky sticky left-0 z-10 px-5 py-3 whitespace-nowrap bg-white border-r border-[#2B2030]/10 shadow-[1px_0_0_0_rgba(43,32,48,0.08)]">
                            <div class="flex items-center gap-3">
                                <div class="w-1 h-5 rounded-full" style="background-color: #6a2c75;"></div>
                                <span class="modern-font font-semibold text-sm text-[#2B2030]">
                                    {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }} {{ $empleado->nombre }}
                                </span>
                            </div>
                        </td>

                        @foreach($this->dias as $fecha)
                        @php

                        $fechaCompleta = $fecha->format('Y-m-d');

                        $asistencia = $empleado->asistencias
                        ->where('fecha', $fechaCompleta)
                        ->first();

                        $statusClass = '';

                        if ($asistencia?->estatus?->codigo == 'A') {
                        $statusClass = 'status-A';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'F') {
                        $statusClass = 'status-F';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'V') {
                        $statusClass = 'status-V';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'R') {
                        $statusClass = 'status-R';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'I') {
                        $statusClass = 'status-I';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'PCG') {
                        $statusClass = 'status-PCG';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'PSG') {
                        $statusClass = 'status-PSG';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'O') {
                        $statusClass = 'status-O';
                        }
                        elseif ($asistencia?->estatus?->codigo == 'S') {
                        $statusClass = 'status-S';
                        }

                        @endphp

                        <td class="px-1.5 py-2 text-center relative">
                            {{-- TU SELECT ORIGINAL QUE SÍ FUNCIONA CON LA APARIENCIA NUEVA --}}


                            <select

                                wire:key="sel-{{ $empleado->id }}-{{ $fechaCompleta }}"

                                wire:change="
        actualizarAsistencia(
            {{ $empleado->id }},
            '{{ $fechaCompleta }}',
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
            ? 'background: #F7F3EE; border-color: #E4DED6; color: #A8A0AC;'
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

                                    {{ $item->codigo === 'I' ? 'INC' : $item->codigo }}

                                </option>

                                @endforeach

                            </select>
                        </td>
                        @endforeach

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- LEYENDA (COLORES) --}}
    <div class="section-animate flex flex-wrap gap-3 px-1 mt-4 mb-2" style="animation-delay: .3s;">
        @php
        $leyenda = [
        ['label' => 'Asistencia', 'class' => 'status-A'],
        ['label' => 'Falta', 'class' => 'status-F'],
        ['label' => 'Vacaciones', 'class' => 'status-V'],
        ['label' => 'Retardo', 'class' => 'status-R'],
        ['label' => 'Incapacidad', 'class' => 'status-I'],
        ['label' => 'PCG', 'class' => 'status-PCG'],
        ['label' => 'PSG', 'class' => 'status-PSG'],
        ['label' => 'Onomástico', 'class' => 'status-O'],
        ['label' => 'Suspensión', 'class' => 'status-S'],
        ];
        @endphp

        @foreach($leyenda as $item)
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border {{ $item['class'] }} bg-white stat-badge cursor-default transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
            <div class="w-2 h-2 rounded-full" style="background: currentColor;"></div>
            <span class="modern-font text-xs font-bold">{{ $item['label'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- RESUMEN MENSUAL --}}
    <div class="clean-panel section-animate relative overflow-hidden" style="animation-delay: .35s;">
        <div class="flex items-center justify-between px-5 py-3 border-b border-[#2B2030]/8" style="background: #FBF8F3;">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-5 rounded-full" style="background-color: #6a2c75;"></div>
                <span class="modern-font text-lg font-bold text-[#2B2030]">
                    Resumen Mensual
                </span>
            </div>
        </div>

        <p class="sm:hidden flex items-center gap-1.5 px-5 py-2 text-xs text-[#6E6274] border-b border-[#2B2030]/8">
            <svg class="h-3.5 w-3.5 swipe-hint" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 6 6 6-6 6" />
            </svg>
            Desliza horizontalmente para ver todas las columnas
        </p>

        <div class="overflow-x-auto clean-scroll">
            <table class="w-full text-sm text-[#2B2030]">
                <thead>
                    <tr class="border-b border-[#2B2030]/10" style="background: #FBF8F3;">
                        <th class="cell-sticky sticky left-0 z-10 px-5 py-3.5 text-left whitespace-nowrap border-r border-[#2B2030]/10 shadow-[1px_0_0_0_rgba(43,32,48,0.08)]" style="background: #FBF8F3;"><span class="modern-font text-xs font-bold tracking-wider uppercase text-[#6E6274]">Empleado</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-A px-2 py-1 rounded-md border bg-white">Asistencias</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-F px-2 py-1 rounded-md border bg-white">Faltas</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-V px-2 py-1 rounded-md border bg-white">Vacaciones</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-R px-2 py-1 rounded-md border bg-white">Retardos</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-I px-2 py-1 rounded-md border bg-white">Incapacidades </span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-PCG px-2 py-1 rounded-md border bg-white">PCG</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-PSG px-2 py-1 rounded-md border bg-white">PSG</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-O px-2 py-1 rounded-md border bg-white">Onomásticos</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-S px-2 py-1 rounded-md border bg-white">Suspensiones</span></th>
                        <th class="px-5 py-3.5 text-center">
                            <span class="modern-font text-xs font-bold tracking-wider uppercase rounded-md border border-emerald-200 bg-emerald-50 text-emerald-700 px-2 py-1">
                                Horas Extra
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($empleados as $i => $empleado)

                    @php

                    $desde = \Carbon\Carbon::parse($this->desde);

                    $hasta = \Carbon\Carbon::parse($this->hasta);

                    $faltas = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'F'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $retardos = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'R'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $vacaciones = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'V'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $incapacidades = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'I'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $pcg = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'PCG'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $psg = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'PSG'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $onomasticos = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'O'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $asistenciasTotal = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'A'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $suspensiones = $empleado->asistencias
                    ->filter(function ($a) use ($desde, $hasta) {

                    return $a->estatus?->codigo == 'S'
                    && \Carbon\Carbon::parse($a->fecha)
                    ->between($desde, $hasta);

                    })
                    ->count();

                    $horasExtra = $empleado->horasExtras
                    ->filter(function ($detalle) use ($desde, $hasta) {

                    return $detalle->horaExtra
                    && $detalle->horaExtra->estatus
                    && $detalle->horaExtra->estatus->nombre === 'AUTORIZADA'
                    && \Carbon\Carbon::parse($detalle->horaExtra->fecha)
                    ->between($desde, $hasta);

                    })
                    ->sum('horas');

                    @endphp

                    <tr wire:key="sum-{{ $empleado->id }}" class="tr-clean row-animate border-b border-[#2B2030]/6 bg-white" style="animation-delay: {{ $i * 0.05 }}s;">
                        <td class="cell-sticky sticky left-0 z-10 px-5 py-4 whitespace-nowrap bg-white border-r border-[#2B2030]/10 shadow-[1px_0_0_0_rgba(43,32,48,0.08)]">
                            <div class="flex items-center gap-3">
                                <span class="mono-font text-xs font-semibold text-[#A8A0AC]">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="modern-font font-semibold text-[#2B2030]">
                                    {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }} {{ $empleado->nombre }}
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-A modern-font text-base font-bold bg-white stat-badge">
                                {{ $asistenciasTotal }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-F modern-font text-base font-bold bg-white stat-badge">
                                {{ $faltas }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-V modern-font text-base font-bold bg-white stat-badge">
                                {{ $vacaciones }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-R modern-font text-base font-bold bg-white stat-badge">
                                {{ $retardos }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-I modern-font text-base font-bold bg-white stat-badge">
                                {{ $incapacidades }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-PCG modern-font text-base font-bold bg-white stat-badge">
                                {{ $pcg }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-PSG modern-font text-base font-bold bg-white stat-badge">
                                {{ $psg }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-O modern-font text-base font-bold bg-white stat-badge">
                                {{ $onomasticos }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-S modern-font text-base font-bold bg-white stat-badge">
                                {{ $suspensiones }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">

                            @if($horasExtra > 0)

                            <div class="inline-flex items-center justify-center min-w-[60px] h-10 rounded-xl bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">

                                {{ number_format($horasExtra,2) }} h

                            </div>

                            @else

                            <span class="text-[#A8A0AC]">—</span>

                            @endif

                        </td>



                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
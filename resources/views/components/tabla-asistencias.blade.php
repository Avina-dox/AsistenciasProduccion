<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>


<div class="min-h-screen p-6 space-y-6" style="background-color: #f8fafc;">


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

        .status-O {
            background: rgba(236, 110, 72, 0.15);
            border-color: rgba(236, 146, 72, 0.4);
            color: #be5518;
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
                wire:click="periodoAnterior"
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
                    Del {{ \Carbon\Carbon::parse($desde)->translatedFormat('d M Y') }}
                    al {{ \Carbon\Carbon::parse($hasta)->translatedFormat('d M Y') }}
                    <div class="flex justify-center gap-2 mt-3">

                        <button
                            wire:click="mesActual"
                            class="px-4 py-2 rounded-lg transition
            {{ $modo == 'MES'
                ? 'bg-purple-600 text-white'
                : 'bg-white border hover:bg-gray-100' }}">

                            Mes

                        </button>

                        <button
                            wire:click="semanaActual"
                            class="px-4 py-2 rounded-lg transition
            {{ $modo == 'SEMANA'
                ? 'bg-purple-600 text-white'
                : 'bg-white border hover:bg-gray-100' }}">

                            Semana

                        </button>

                        <button
                            wire:click="quincenaActual"
                            class="px-4 py-2 rounded-lg transition
            {{ $modo == 'QUINCENA'
                ? 'bg-purple-600 text-white'
                : 'bg-white border hover:bg-gray-100' }}">

                            Quincena

                        </button>

                    </div>

                </h2>

            </div>

            {{-- BOTÓN SIGUIENTE --}}

            <button
                wire:click="siguientePeriodo"
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-slate-900 tracking-wide">

                    Departamento

                </label>

                <select
                    wire:model.live="departamento_id"
                    class="w-full border border-slate-300 rounded-2xl px-3 py-2 bg-slate-50 shadow-xl focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100 transition">

                    <option value="">Todos</option>

                    @foreach($departamentos as $departamento)

                    <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>

                    @endforeach

                </select>

            </div>

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-slate-900 tracking-wide">

                    Turno

                </label>

                <select
                    wire:model.live="turno_id"
                    class="w-full border border-slate-300 rounded-2xl px-3 py-2 bg-slate-50 shadow-xl focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100 transition">

                    <option value="">Todos</option>

                    @foreach($turnos as $turno)

                    <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>

                    @endforeach

                </select>

            </div>

            <div class="space-y-2">

                <label class="block text-sm font-semibold text-slate-900 tracking-wide">

                    Estado

                </label>

                <select
                    wire:model.live="estatusEmpleado"
                    class="w-full border border-slate-300 rounded-2xl px-3 py-2 bg-slate-50 shadow-xl focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100 transition">

                    <option value="ACTIVO">Activos</option>
                    <option value="INACTIVO">Inactivos</option>
                    <option value="BAJA">Baja</option>
                    <option value="TODOS">Todos</option>

                </select>
                

            </div>
            <a
    href="{{ route('asistencias.exportar',[
        'desde'=>$desde,
        'hasta'=>$hasta,
        'departamento'=>$departamento_id,
        'turno'=>$turno_id,
        'estatus'=>$estatusEmpleado
    ]) }}"
    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">

    Exportar Excel

</a>

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
                        @foreach($this->dias as $fecha)

                        <th class="px-2 py-3.5 text-center min-w-[52px] border-b border-gray-200">

                            <span class="mono-font text-xs font-bold" style="color:#6a2c75;">

                                {{ $fecha->format('d') }}

                            </span>

                        </th>

                        @endforeach
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
                        @endforeach

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
        ['label' => 'Onomástico', 'class' => 'status-O'],
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
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-A px-2 py-1 rounded-md border bg-white">Asistencias</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-F px-2 py-1 rounded-md border bg-white">Faltas</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-V px-2 py-1 rounded-md border bg-white">Vacaciones</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-R px-2 py-1 rounded-md border bg-white">Retardos</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-I px-2 py-1 rounded-md border bg-white">Incapacidades </span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-PCG px-2 py-1 rounded-md border bg-white">PCG</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-PSG px-2 py-1 rounded-md border bg-white">PSG</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="modern-font text-xs font-bold tracking-wider uppercase status-O px-2 py-1 rounded-md border bg-white">Onomásticos</span></th>
                        <th class="px-5 py-3.5 text-center">
                            <span class="modern-font text-xs font-bold tracking-wider uppercase rounded-md border bg-green-100 text-green-700 px-2 py-1">
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
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-A modern-font text-base font-bold bg-white stat-badge">
                                {{ $asistenciasTotal }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-F modern-font text-base font-bold bg-white stat-badge">
                                {{ $faltas }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-V modern-font text-base font-bold bg-white stat-badge">
                                {{ $vacaciones }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-R modern-font text-base font-bold bg-white stat-badge">
                                {{ $retardos }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-I modern-font text-base font-bold bg-white stat-badge">
                                {{ $incapacidades }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-PCG modern-font text-base font-bold bg-white stat-badge">
                                {{ $pcg }}
                            </div>
                        </td>

                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-PSG modern-font text-base font-bold bg-white stat-badge">
                                {{ $psg }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg border status-O modern-font text-base font-bold bg-white stat-badge">
                                {{ $onomasticos }}
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">

                            @if($horasExtra > 0)

                            <div class="inline-flex items-center justify-center min-w-[60px] h-10 rounded-lg bg-green-100 text-green-700 font-bold border border-green-300">

                                {{ number_format($horasExtra,2) }} h

                            </div>

                            @else

                            <span class="text-gray-400">—</span>

                            @endif

                        </td>



                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div class="relative bg-background min-h-screen">

    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[550px] h-[480px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(12px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .row-animate { animation: fadeInUp 0.4s ease both; }
            .section-animate { animation: fadeInUp 0.55s cubic-bezier(.4, 0, .2, 1) both; }

            @keyframes swipeHint {
                0%, 100% { transform: translateX(0); opacity: .55; }
                50% { transform: translateX(5px); opacity: 1; }
            }
            .swipe-hint { animation: swipeHint 1.3s ease-in-out infinite; }

            @keyframes pulseSoft {
                0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, .5); }
                70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            }
            .pulse-soft { animation: pulseSoft 2s infinite; }

            .select-clean {
                appearance: none;
                -webkit-appearance: none;
                background-image: none;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .select-clean:hover {
                border-color: rgb(var(--color-primary)) !important;
                box-shadow: 0 0 0 2px rgb(var(--color-primary) / .12);
            }
            .select-clean:focus {
                outline: none;
                border-color: rgb(var(--color-primary)) !important;
                box-shadow: 0 0 0 3px rgb(var(--color-primary) / .2);
            }

            .tr-clean:hover td,
            .tr-clean:hover .cell-sticky {
                background: rgb(var(--color-primary) / .04) !important;
                transition: background 0.2s ease;
            }

            .select-empty {
                background: rgb(var(--color-surface-container));
                border-color: rgb(var(--color-outline-variant));
                color: rgb(var(--color-outline));
            }

            /* Paleta de estatus — distintiva y legible en ambos modos */
            .status-A { background: #ECFDF5; border-color: rgba(5, 150, 105, 0.35); color: #059669; }
            .status-F { background: #FEF2F2; border-color: rgba(220, 38, 38, 0.35); color: #DC2626; }
            .status-V { background: #EFF8FF; border-color: rgba(2, 132, 199, 0.35); color: #0284C7; }
            .status-R { background: #FBF6E4; border-color: rgba(182, 166, 68, 0.5); color: #92752F; }
            .status-O { background: #FFF4ED; border-color: rgba(194, 65, 12, 0.35); color: #C2410C; }
            .status-I { background: #F5F0FA; border-color: rgba(106, 44, 117, 0.35); color: #6A2C75; }
            .status-PCG { background: #EEF2FF; border-color: rgba(67, 56, 202, 0.35); color: #4338CA; }
            .status-PSG { background: #F1F5F9; border-color: rgba(71, 85, 105, 0.35); color: #475569; }
            .status-S { background: #FFF1F2; border-color: rgba(159, 18, 57, 0.35); color: #9F1239; }

            .dark .status-A { background: rgba(5, 150, 105, 0.15); border-color: rgba(52, 211, 153, 0.4); color: #34D399; }
            .dark .status-F { background: rgba(220, 38, 38, 0.15); border-color: rgba(248, 113, 113, 0.4); color: #F87171; }
            .dark .status-V { background: rgba(2, 132, 199, 0.15); border-color: rgba(56, 189, 248, 0.4); color: #38BDF8; }
            .dark .status-R { background: rgba(182, 166, 68, 0.15); border-color: rgba(212, 196, 103, 0.45); color: #D4C467; }
            .dark .status-O { background: rgba(194, 65, 12, 0.15); border-color: rgba(251, 146, 60, 0.4); color: #FB923C; }
            .dark .status-I { background: rgba(106, 44, 117, 0.2); border-color: rgba(237, 179, 242, 0.4); color: #EDB3F2; }
            .dark .status-PCG { background: rgba(67, 56, 202, 0.2); border-color: rgba(129, 140, 248, 0.4); color: #818CF8; }
            .dark .status-PSG { background: rgba(71, 85, 105, 0.2); border-color: rgba(148, 163, 184, 0.4); color: #94A3B8; }
            .dark .status-S { background: rgba(159, 18, 57, 0.18); border-color: rgba(251, 113, 133, 0.4); color: #FB7185; }

            .stat-badge { box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06); transition: transform .2s ease, box-shadow .2s ease; }
            .stat-badge:hover { transform: scale(1.12); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12); }

            .clean-scroll::-webkit-scrollbar { height: 6px; }
            .clean-scroll::-webkit-scrollbar-track { background: rgb(var(--color-surface-container)); border-radius: 4px; }
            .clean-scroll::-webkit-scrollbar-thumb { background: rgb(var(--color-outline-variant)); border-radius: 4px; }
            .clean-scroll::-webkit-scrollbar-thumb:hover { background: rgb(var(--color-primary)); }
        </style>

        {{-- ENCABEZADO --}}
        <div class="section-animate flex flex-wrap items-center justify-between gap-4" style="animation-delay: .05s;">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary-container text-[28px]">fact_check</span>
                <div>
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">Gestión de Asistencia</p>
                    <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight">Control de Asistencia</h1>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/10 text-on-tertiary-container font-label-md text-label-md font-semibold">
                <span class="h-1.5 w-1.5 rounded-full bg-on-tertiary-container pulse-soft"></span>
                ACTIVO
            </span>
        </div>

        {{-- NAVEGACIÓN MES/AÑO --}}
        <div class="section-animate relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-5 sm:p-6" style="animation-delay: .1s;">

            <div class="flex flex-col lg:flex-row lg:items-end gap-5">

                <div class="flex-1">
                    <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">calendar_month</span>
                        Periodo de asistencia
                    </h2>
                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Selecciona el periodo que deseas consultar.</p>
                </div>

                <div class="w-full lg:w-56">
                    <label for="desde" class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Desde</label>
                    <input
                        id="desde"
                        type="date"
                        wire:model.live="desde"
                        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="w-full lg:w-56">
                    <label for="hasta" class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Hasta</label>
                    <input
                        id="hasta"
                        type="date"
                        wire:model.live="hasta"
                        min="{{ $desde }}"
                        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

            </div>

            <div class="mt-4 flex items-center gap-2 font-label-md text-label-md text-primary">
                <span class="material-symbols-outlined text-[18px]">date_range</span>
                <span class="font-semibold">Periodo:</span>
                <span>{{ \Carbon\Carbon::parse($desde)->translatedFormat('d M Y') }} → {{ \Carbon\Carbon::parse($hasta)->translatedFormat('d M Y') }}</span>
            </div>

        </div>

        {{-- BUSCADOR --}}
        <div class="section-animate" style="animation-delay: .12s;">
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Buscar empleado</label>
            <div class="relative">
                <span class="material-symbols-outlined pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre, apellido o código…"
                    class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface py-2.5 pl-11 pr-4 font-body-md text-body-md shadow-sm transition-all duration-300 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="section-animate grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" style="animation-delay: .15s;">

            <div class="space-y-1.5">
                <label class="block font-label-md text-label-md font-semibold text-on-surface">Departamento</label>
                <select
                    wire:model.live="departamento_id"
                    class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm hover:shadow-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-300">
                    <option value="">Todos</option>
                    @foreach($departamentos as $departamento)
                        <option value="{{ $departamento->id }}">{{ $departamento->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block font-label-md text-label-md font-semibold text-on-surface">Turno</label>
                <select
                    wire:model.live="turno_id"
                    class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm hover:shadow-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-300">
                    <option value="">Todos</option>
                    @foreach($turnos as $turno)
                        <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block font-label-md text-label-md font-semibold text-on-surface">Estado</label>
                <select
                    wire:model.live="estatusEmpleado"
                    class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm hover:shadow-md focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all duration-300">
                    <option value="ACTIVO">Activos</option>
                    <option value="INACTIVO">Inactivos</option>
                    <option value="BAJA">Baja</option>
                    <option value="TODOS">Todos</option>
                </select>
            </div>

        </div>

        {{-- ACCIONES DE EXPORTACIÓN --}}
        <div class="section-animate flex flex-col sm:flex-row flex-wrap gap-3" style="animation-delay: .2s;">

            <a
                href="{{ route('asistencias.exportar',[
                    'desde'=>$desde,
                    'hasta'=>$hasta,
                    'departamento'=>$departamento_id,
                    'turno'=>$turno_id,
                    'estatus'=>$estatusEmpleado
                ]) }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-tertiary-container/20 hover:bg-tertiary-container/35 text-on-tertiary-container px-5 py-2.5 font-label-lg text-label-lg font-semibold shadow-sm transition-all duration-300 hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[18px]">download</span>
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
                class="inline-flex items-center justify-center gap-2 rounded-full bg-error-container/30 hover:bg-error-container/50 text-error px-5 py-2.5 font-label-lg text-label-lg font-semibold shadow-sm transition-all duration-300 hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                Generar PDF
            </a>

            <form
                action="{{ route('asistencias.enviarPdf') }}"
                method="POST"
                class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="desde" value="{{ $desde }}">
                <input type="hidden" name="hasta" value="{{ $hasta }}">
                <input type="hidden" name="departamento" value="{{ $departamento_id }}">
                <input type="hidden" name="turno" value="{{ $turno_id }}">
                <input type="hidden" name="estatus" value="{{ $estatusEmpleado }}">

                <button
                    type="submit"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container text-white px-5 py-2.5 font-label-lg text-label-lg font-semibold shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all duration-300">
                    <span class="material-symbols-outlined text-[18px]">forward_to_inbox</span>
                    Enviar PDF a RH
                </button>
            </form>

        </div>

        {{-- TABLA PRINCIPAL --}}
        <div class="section-animate relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]" style="animation-delay: .25s;">

            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-black/[0.06] dark:border-white/[0.06] bg-surface-container-high/30">
                <span class="material-symbols-outlined text-secondary text-[20px]">event_note</span>
                <span class="font-label-lg text-label-lg font-semibold text-on-surface">Registro Diario de Empleados</span>
            </div>

            <p class="sm:hidden flex items-center gap-1.5 px-5 py-2 font-body-sm text-body-sm text-on-surface-variant border-b border-black/[0.06] dark:border-white/[0.06]">
                <span class="material-symbols-outlined swipe-hint text-[16px]">chevron_right</span>
                Desliza horizontalmente para ver todos los días
            </p>

            <div class="overflow-x-auto clean-scroll">
                <table class="min-w-full border-collapse font-body-md text-body-md text-on-surface">
                    <thead>
                        <tr class="bg-surface-container-high/40">
                            <th class="sticky left-0 z-10 px-5 py-3.5 text-left whitespace-nowrap border-b border-r border-black/[0.06] dark:border-white/[0.06] bg-surface-container-lowest">
                                <span class="font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">
                                    Empleado
                                </span>
                            </th>
                            @foreach($this->dias as $fecha)

                            <th class="px-2 py-3.5 text-center min-w-[52px] border-b border-black/[0.06] dark:border-white/[0.06]">
                                <span class="font-mono text-xs font-bold text-primary">
                                    {{ $fecha->format('d') }}
                                </span>
                            </th>

                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($empleados as $i => $empleado)
                        <tr wire:key="emp-{{ $empleado->id }}" class="tr-clean row-animate border-b border-black/[0.04] dark:border-white/[0.04]" style="animation-delay: {{ $i * 0.04 }}s;">

                            <td class="cell-sticky sticky left-0 z-10 px-5 py-3 whitespace-nowrap bg-surface-container-lowest border-r border-black/[0.06] dark:border-white/[0.06]">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs font-semibold text-outline">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="w-1 h-5 rounded-full" style="background-color: {{ $empleado->user?->hasRole('Supervisor') ? 'rgb(var(--color-secondary))' : 'rgb(var(--color-primary))' }};"></div>
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface">
                                        {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }} {{ $empleado->nombre }}
                                    </span>
                                    @if($empleado->user?->hasRole('Supervisor'))
                                        <span class="inline-flex items-center gap-1 shrink-0 rounded-full bg-primary-container/15 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-on-primary-container" title="Este empleado tiene rol de Supervisor">
                                            <span class="h-1.5 w-1.5 rounded-full bg-on-primary-container"></span>
                                            Supervisor
                                        </span>
                                    @endif
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
        font-mono
        w-full
        rounded-md
        px-1
        py-1.5
        text-xs
        font-bold
        text-center
        border
        {{ $statusClass ?: 'select-empty' }}
    "

                                    style="min-width: 44px;">

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
                        @empty
                        <tr>
                            <td colspan="{{ count($this->dias) + 1 }}" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[32px] text-outline">inbox</span>
                                    @if($search)
                                        <p class="font-body-md text-body-md font-medium">No se encontraron empleados para "{{ $search }}".</p>
                                        <button type="button" wire:click="$set('search', '')" class="font-label-md text-label-md font-semibold text-primary hover:underline">
                                            Limpiar búsqueda
                                        </button>
                                    @else
                                        <p class="font-body-md text-body-md font-medium">No hay empleados para mostrar con estos filtros.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- LEYENDA (COLORES) --}}
        <div class="section-animate flex flex-wrap gap-3 px-1" style="animation-delay: .3s;">
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
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border {{ $item['class'] }} stat-badge cursor-default transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="w-2 h-2 rounded-full" style="background: currentColor;"></div>
                <span class="font-label-md text-label-md font-bold">{{ $item['label'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- RESUMEN MENSUAL --}}
        <div class="section-animate relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]" style="animation-delay: .35s;">
            <div class="flex items-center gap-3 px-5 py-3.5 border-b border-black/[0.06] dark:border-white/[0.06] bg-surface-container-high/30">
                <span class="material-symbols-outlined text-secondary text-[20px]">summarize</span>
                <span class="font-headline-sm text-headline-sm font-bold text-on-surface">
                    Resumen Mensual
                </span>
            </div>

            <p class="sm:hidden flex items-center gap-1.5 px-5 py-2 font-body-sm text-body-sm text-on-surface-variant border-b border-black/[0.06] dark:border-white/[0.06]">
                <span class="material-symbols-outlined swipe-hint text-[16px]">chevron_right</span>
                Desliza horizontalmente para ver todas las columnas
            </p>

            <div class="overflow-x-auto clean-scroll">
                <table class="w-full font-body-md text-body-md text-on-surface">
                    <thead>
                        <tr class="bg-surface-container-high/40">
                            <th class="cell-sticky sticky left-0 z-10 px-5 py-3.5 text-left whitespace-nowrap border-r border-black/[0.06] dark:border-white/[0.06] bg-surface-container-lowest"><span class="font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Empleado</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-A px-2 py-1 rounded-md border">Asistencias</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-F px-2 py-1 rounded-md border">Faltas</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-F px-2 py-1 rounded-md border">Faltas del Mes</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-V px-2 py-1 rounded-md border">Vacaciones</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-R px-2 py-1 rounded-md border">Retardos</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-I px-2 py-1 rounded-md border">Incapacidades</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-PCG px-2 py-1 rounded-md border">PCG</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-PSG px-2 py-1 rounded-md border">PSG</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-O px-2 py-1 rounded-md border">Onomásticos</span></th>
                            <th class="px-5 py-3.5 text-center"><span class="font-label-caps text-label-caps uppercase status-S px-2 py-1 rounded-md border">Suspensiones</span></th>
                            <th class="px-5 py-3.5 text-center">
                                <span class="font-label-caps text-label-caps uppercase rounded-md border border-secondary/30 bg-secondary-container/20 text-on-secondary-container px-2 py-1">
                                    Horas Extra
                                </span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($empleados as $i => $empleado)

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

                        $faltasDelMes = $faltasMes[$empleado->id] ?? 0;

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

                        <tr wire:key="sum-{{ $empleado->id }}" class="tr-clean row-animate border-b border-black/[0.04] dark:border-white/[0.04] bg-surface-container-lowest" style="animation-delay: {{ $i * 0.05 }}s;">
                            <td class="cell-sticky sticky left-0 z-10 px-5 py-4 whitespace-nowrap bg-surface-container-lowest border-r border-black/[0.06] dark:border-white/[0.06]">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-xs font-semibold text-outline">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="font-label-lg text-label-lg font-semibold text-on-surface">
                                        {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }} {{ $empleado->nombre }}
                                    </span>
                                    @if($empleado->user?->hasRole('Supervisor'))
                                        <span class="inline-flex items-center gap-1 shrink-0 rounded-full bg-primary-container/15 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-on-primary-container" title="Este empleado tiene rol de Supervisor">
                                            <span class="h-1.5 w-1.5 rounded-full bg-on-primary-container"></span>
                                            Supervisor
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-A font-bold text-base stat-badge">
                                    {{ $asistenciasTotal }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-F font-bold text-base stat-badge">
                                    {{ $faltas }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if($faltasDelMes > $limiteFaltas)
                                    <div class="inline-flex items-center justify-center gap-1 w-auto min-w-[2.5rem] h-10 px-2 rounded-xl border border-error bg-error text-on-error font-bold text-base stat-badge" title="Supera el límite de {{ $limiteFaltas }} faltas en el mes">
                                        <span class="material-symbols-outlined text-[14px]">warning</span>{{ $faltasDelMes }}
                                    </div>
                                @else
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-F font-bold text-base stat-badge">
                                        {{ $faltasDelMes }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-V font-bold text-base stat-badge">
                                    {{ $vacaciones }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-R font-bold text-base stat-badge">
                                    {{ $retardos }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-I font-bold text-base stat-badge">
                                    {{ $incapacidades }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-PCG font-bold text-base stat-badge">
                                    {{ $pcg }}
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-PSG font-bold text-base stat-badge">
                                    {{ $psg }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-O font-bold text-base stat-badge">
                                    {{ $onomasticos }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl border status-S font-bold text-base stat-badge">
                                    {{ $suspensiones }}
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">

                                @if($horasExtra > 0)

                                <div class="inline-flex items-center justify-center min-w-[60px] h-10 rounded-xl bg-secondary-container/20 text-on-secondary-container font-bold border border-secondary/30">

                                    {{ number_format($horasExtra,2) }} h

                                </div>

                                @else

                                <span class="text-outline">—</span>

                                @endif

                            </td>



                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[32px] text-outline">inbox</span>
                                    @if($search)
                                        <p class="font-body-md text-body-md font-medium">No se encontraron empleados para "{{ $search }}".</p>
                                        <button type="button" wire:click="$set('search', '')" class="font-label-md text-label-md font-semibold text-primary hover:underline">
                                            Limpiar búsqueda
                                        </button>
                                    @else
                                        <p class="font-body-md text-body-md font-medium">No hay empleados para mostrar con estos filtros.</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

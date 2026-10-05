@extends('layouts.app')

@section('content')

@php
    $widgetsVisibles = auth()->user()->widgetsDashboardVisibles();

    $iniciales = collect(explode(' ', auth()->user()->name))
        ->map(fn($p) => strtoupper($p[0] ?? ''))
        ->take(2)
        ->implode('');

    // Iniciales de un departamento para su insignia (primeras letras de sus dos primeras palabras)
    $inicialesDepto = function (string $nombre) {
        $palabras = preg_split('/\s+/', trim($nombre));

        if (count($palabras) >= 2) {
            return strtoupper(mb_substr($palabras[0], 0, 1) . mb_substr($palabras[1], 0, 1));
        }

        return strtoupper(mb_substr($nombre, 0, 2));
    };

    // Estatus operativo de cobertura por área, según el mejor de los dos turnos
    $estatusCobertura = function (array $turnos) {
        $max = max(
            $turnos['MATUTINO']['porcentaje'] ?? 0,
            $turnos['NOCTURNO']['porcentaje'] ?? 0
        );

        return match (true) {
            $max <= 0 => ['label' => 'Crítico · Sin personal', 'clase' => 'bg-error-container/40 text-error', 'punto' => 'bg-error'],
            $max < 10 => ['label' => 'En arranque', 'clase' => 'bg-secondary-container/20 text-on-secondary-container', 'punto' => 'bg-secondary'],
            $max < 50 => ['label' => 'Parcial', 'clase' => 'bg-surface-container-high text-on-surface-variant', 'punto' => 'bg-outline'],
            $max < 90 => ['label' => 'Cobertura media', 'clase' => 'bg-surface-variant/50 text-on-surface-variant', 'punto' => 'bg-on-surface-variant'],
            default => ['label' => 'Óptimo', 'clase' => 'bg-tertiary-container/20 text-on-tertiary-container', 'punto' => 'bg-on-tertiary-container'],
        };
    };

    // Desglose "Distribución de Asistencia" del día
    $desglosePrincipal = [
        ['label' => 'Presentes', 'valor' => $data['presentes'], 'color' => 'bg-on-tertiary-container'],
        ['label' => 'Faltas Injustificadas', 'valor' => $data['faltas'], 'color' => 'bg-error'],
        ['label' => 'Horas Extra Autorizadas', 'valor' => $data['horas_extra_autorizadas'], 'color' => 'bg-secondary-container'],
    ];

    $totalDesglose = max(array_sum(array_column($desglosePrincipal, 'valor')), 1);

    $desgloseSecundario = [
        ['label' => 'Incapacidades', 'valor' => $data['incapacidades']],
        ['label' => 'Vacaciones', 'valor' => $data['vacaciones']],
        ['label' => 'Retardos', 'valor' => $data['retardos']],
    ];
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block');
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap');

    .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
</style>

<div class="relative bg-background min-h-screen" data-production-passthrough>

    {{-- Gemelo digital de la planta (Three.js) como fondo. Ver components/production-background --}}
    <x-production-background mode="dashboard" :data="$productionData ?? null" />

    {{-- Luces ambientales decorativas --}}
    <div class="fixed top-[-10%] left-[-5%] w-[600px] h-[600px] rounded-full bg-gradient-to-br from-primary-fixed-dim/25 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed top-[30%] right-[-10%] w-[550px] h-[550px] rounded-full bg-gradient-to-bl from-tertiary-fixed/20 via-secondary-fixed/15 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] left-[20%] w-[700px] h-[500px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/40 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative z-10 w-full max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" data-production-passthrough>

        {{-- HERO --}}
        <section class="relative overflow-hidden rounded-3xl glass-panel p-6 sm:p-8">

            <div class="absolute -top-24 -right-16 w-80 h-80 rounded-full bg-gradient-to-br from-primary-fixed-dim/30 to-secondary-container/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 left-1/3 w-96 h-96 rounded-full bg-gradient-to-tr from-surface-variant/40 to-tertiary-fixed/20 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">

                    <div class="relative group shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shadow-[0_8px_20px_rgba(74,30,82,0.15)] ring-4 ring-white/90 bg-gradient-to-br from-primary to-primary-container flex items-center justify-center transition-transform duration-300 group-hover:scale-105">
                            <span class="text-white text-2xl font-bold font-headline-md">{{ $iniciales }}</span>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-tertiary-container text-tertiary-fixed flex items-center justify-center ring-2 ring-white text-[12px] shadow-sm">
                            <span class="material-symbols-outlined text-[12px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <img src="{{ asset('images/logo.png') }}" alt="Dasavena" class="h-6 w-auto object-contain mr-2 opacity-95">

                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-container/10 text-primary-container shadow-[inset_0_1px_1px_rgba(255,255,255,0.8)]">
                                <span class="material-symbols-outlined text-[14px]">shield_person</span>
                                <span class="font-label-md text-label-md uppercase tracking-wider font-semibold">SA · Sistema de Control de Asistencias</span>
                            </div>

                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-tertiary-container/10 text-on-tertiary-container">
                                <span class="w-2 h-2 rounded-full bg-on-tertiary-container animate-pulse"></span>
                                <span class="font-label-md text-label-md font-medium">En vivo · {{ now()->format('H:i') }}</span>
                            </div>
                        </div>

                        <h1 class="font-headline-xl-mobile text-headline-xl-mobile sm:font-headline-xl sm:text-headline-xl text-primary font-bold tracking-tight">
                            ¡Bienvenido, {{ auth()->user()->name }}!
                        </h1>

                        <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                            Administra asistencias, permisos, horas extra y personal desde un solo lugar. Cobertura operativa en tiempo real para planta Dasavena.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap sm:flex-nowrap items-center gap-4">

                    <div class="flex items-center gap-3 px-4 py-3 rounded-2xl glass-inset flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-b from-primary-container to-primary text-white flex flex-col items-center justify-center shadow-md">
                            <span class="font-label-caps text-label-caps text-secondary-container tracking-wider uppercase">HOY</span>
                            <span class="font-headline-sm text-headline-sm font-bold leading-none mt-0.5">{{ now()->format('d') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-label-lg text-label-lg font-bold text-on-surface">{{ ucfirst(now()->translatedFormat('l')) }}</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ ucfirst(now()->translatedFormat('F Y')) }}</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('asistencias.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full bg-gradient-to-r from-primary to-primary-container text-white font-label-lg text-label-lg font-semibold shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                            <span>Registrar Asistencia</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('hora-extras.create') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full glass-inset hover:bg-white/80 dark:hover:bg-white/10 text-primary font-label-md text-label-md font-semibold transition-all">
                                <span class="material-symbols-outlined text-[16px] text-secondary">alarm_add</span>
                                <span>Hora Extra</span>
                            </a>

                            @hasanyrole('Admin|RH|Supervisor|Coordinacion')
                            <a href="{{ route('empleados.create') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full glass-inset hover:bg-white/80 dark:hover:bg-white/10 text-primary font-label-md text-label-md font-semibold transition-all">
                                <span class="material-symbols-outlined text-[16px] text-primary-container">person_add</span>
                                <span>Empleado</span>
                            </a>
                            @endhasanyrole

                            @role('Admin')
                            <a href="{{ route('usuarios.create') }}"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-full glass-inset hover:bg-white/80 dark:hover:bg-white/10 text-primary font-label-md text-label-md font-semibold transition-all">
                                <span class="material-symbols-outlined text-[16px] text-outline">badge</span>
                                <span>Usuario</span>
                            </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- KPIs PRINCIPALES --}}
        @if(in_array('kpis_principales', $widgetsVisibles))
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" data-production-passthrough>

            {{-- Plantilla --}}
            <div class="relative overflow-hidden rounded-3xl glass-card p-5 hover:-translate-y-1 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-2xl bg-primary-container/10 text-primary-container flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-[22px]">badge</span>
                    </div>
                    <span class="font-label-caps text-label-caps uppercase text-outline tracking-wider font-semibold">Total Plantilla</span>
                </div>
                <div class="mt-4">
                    <span class="font-stat-display text-stat-display text-primary font-bold tracking-tight">{{ $data['empleados_activos'] }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant ml-1 font-medium">empleados activos</span>
                </div>
                <div class="mt-4 pt-3 border-t border-black/[0.04] flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-secondary"></div>
                        <span class="font-label-md text-label-md text-on-surface-variant font-medium">{{ $data['asistencia_porcentaje'] }}% Asistencia</span>
                    </div>
                    <div class="w-20 h-1.5 rounded-full bg-surface-container overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full" style="width: {{ min($data['asistencia_porcentaje'], 100) }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Presentes --}}
            <div class="relative overflow-hidden rounded-3xl glass-card p-5 hover:-translate-y-1 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-2xl bg-tertiary-container/10 text-on-tertiary-container flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </div>
                    <span class="font-label-caps text-label-caps uppercase text-outline tracking-wider font-semibold">Tiempo Real</span>
                </div>
                <div class="mt-4">
                    <span class="font-stat-display text-stat-display text-on-surface font-bold tracking-tight">{{ $data['presentes'] }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant ml-1 font-medium">presentes hoy</span>
                </div>
                <div class="mt-4 pt-3 border-t border-black/[0.04] flex items-center justify-between">
                    <div class="flex items-center gap-1.5 text-secondary">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        <span class="font-label-md text-label-md font-semibold">{{ $data['ausentismo_porcentaje'] }}% Ausentismo</span>
                    </div>
                    <span class="font-body-sm text-body-sm text-outline">Meta: &gt;95%</span>
                </div>
            </div>

            {{-- Faltas --}}
            <div class="relative overflow-hidden rounded-3xl glass-card p-5 hover:-translate-y-1 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-2xl bg-error-container/30 text-error flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-[22px]">person_off</span>
                    </div>
                    @if($data['faltas'] > 0)
                        <span class="px-2.5 py-0.5 rounded-full bg-error-container/50 text-error font-label-caps text-label-caps uppercase font-bold tracking-wide">Alerta</span>
                    @else
                        <span class="font-label-caps text-label-caps uppercase text-outline tracking-wider font-semibold">Hoy</span>
                    @endif
                </div>
                <div class="mt-4">
                    <span class="font-stat-display text-stat-display {{ $data['faltas'] > 0 ? 'text-error' : 'text-on-surface' }} font-bold tracking-tight">{{ $data['faltas'] }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant ml-1 font-medium">{{ $data['faltas'] == 1 ? 'falta no justificada' : 'faltas no justificadas' }}</span>
                </div>
                <div class="mt-4 pt-3 border-t border-black/[0.04] flex items-center justify-between">
                    @if($data['faltas'] > 0)
                        <span class="font-label-md text-label-md text-error font-medium">Requiere seguimiento</span>
                    @else
                        <span class="font-label-md text-label-md text-on-tertiary-container font-medium">Sin faltas registradas</span>
                    @endif
                    <span class="material-symbols-outlined text-outline text-[16px]">arrow_forward</span>
                </div>
            </div>

            {{-- Horas Extra Pendientes --}}
            <div class="relative overflow-hidden rounded-3xl glass-card p-5 hover:-translate-y-1 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-2xl bg-secondary-container/20 text-on-secondary-container flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-[22px]">pending_actions</span>
                    </div>
                    <span class="font-label-caps text-label-caps uppercase text-outline tracking-wider font-semibold">Validación</span>
                </div>
                <div class="mt-4">
                    <span class="font-stat-display text-stat-display text-primary font-bold tracking-tight">{{ $data['horas_extra_pendientes'] }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant ml-1 font-medium">pendientes de firma</span>
                </div>
                <div class="mt-4 pt-3 border-t border-black/[0.04] flex items-center justify-between">
                    @if($data['horas_extra_pendientes'] > 0)
                        <div class="flex items-center gap-1.5 text-secondary">
                            <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                            <span class="font-label-md text-label-md font-semibold">Por revisar</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-on-tertiary-container">
                            <span class="material-symbols-outlined text-[14px]">task_alt</span>
                            <span class="font-label-md text-label-md font-semibold">Operación al día</span>
                        </div>
                    @endif
                </div>
            </div>

        </section>
        @endif

        {{-- INDICADORES OPERATIVOS --}}
        @if(in_array('indicadores_operativos', $widgetsVisibles))
        <section class="rounded-3xl glass-panel p-6 sm:p-7 space-y-6">

            <div>
                <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary">monitoring</span>
                    Indicadores Operativos
                </h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Estado general de asistencia del día y métricas críticas de turno.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="rounded-2xl glass-inset p-4 flex items-center justify-between">
                    <div>
                        <span class="font-label-caps text-label-caps uppercase text-outline font-semibold">Retardos</span>
                        <div class="font-headline-lg text-headline-lg text-on-surface font-bold mt-0.5">{{ $data['retardos'] }}</div>
                        <span class="font-body-sm text-body-sm text-on-tertiary-container font-medium">{{ $data['retardos'] == 0 ? 'Tolerancia cumplida' : 'Registrados hoy' }}</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-surface-container flex items-center justify-center text-outline">
                        <span class="material-symbols-outlined text-[22px]">schedule</span>
                    </div>
                </div>

                <div class="rounded-2xl glass-inset p-4 flex items-center justify-between">
                    <div>
                        <span class="font-label-caps text-label-caps uppercase text-outline font-semibold">Horas Extra</span>
                        <div class="font-headline-lg text-headline-lg text-secondary font-bold mt-0.5">{{ $data['horas_extra_autorizadas'] }}</div>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Autorizadas</span>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-secondary-container/20 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">timer</span>
                    </div>
                </div>

                <div class="rounded-2xl glass-inset p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps uppercase text-outline font-semibold">Cobertura Matutina</span>
                        <span class="font-label-lg text-label-lg font-bold text-primary">{{ $data['cobertura']['matutino']['porcentaje'] }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface-container overflow-hidden">
                        <div class="h-full bg-primary-container rounded-full transition-all duration-500" style="width: {{ min($data['cobertura']['matutino']['porcentaje'], 100) }}%;"></div>
                    </div>
                    <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                        <span>Activos: {{ $data['cobertura']['matutino']['personal'] }}</span>
                        <span>Plantilla: {{ $data['cobertura']['matutino']['objetivo'] }} personas</span>
                    </div>
                </div>

                <div class="rounded-2xl glass-inset p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-label-caps text-label-caps uppercase text-outline font-semibold">Cobertura Nocturna</span>
                        <span class="font-label-lg text-label-lg font-bold text-outline">{{ $data['cobertura']['nocturno']['porcentaje'] }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface-container overflow-hidden">
                        <div class="h-full bg-outline rounded-full transition-all duration-500" style="width: {{ min($data['cobertura']['nocturno']['porcentaje'], 100) }}%;"></div>
                    </div>
                    <div class="flex items-center justify-between font-body-sm text-body-sm text-on-surface-variant">
                        <span>Activos: {{ $data['cobertura']['nocturno']['personal'] }}</span>
                        <span>Plantilla: {{ $data['cobertura']['nocturno']['objetivo'] }} personas</span>
                    </div>
                </div>

            </div>
        </section>
        @endif

        {{-- BENTO: COBERTURA POR ÁREA + DISTRIBUCIÓN DE ASISTENCIA --}}
        @if(in_array('cobertura_area', $widgetsVisibles) || in_array('distribucion_asistencia', $widgetsVisibles))
        <div class="grid grid-cols-1  gap-8 items-start" data-production-passthrough>

            @if(in_array('cobertura_area', $widgetsVisibles))
            <section class="xl:col-span-{{ in_array('distribucion_asistencia', $widgetsVisibles) ? '8' : '12' }} rounded-3xl glass-panel p-6 sm:p-7 space-y-5">

                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">domain</span>
                        <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight">Cobertura por Área</h2>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                        Personal activo frente a la plantilla autorizada, por departamento y turno.
                    </p>
                </div>

                <div class="overflow-x-auto rounded-2xl glass-inset">
                    <table class="w-full table-fixed text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-high/40 text-on-surface-variant font-label-caps text-label-caps uppercase tracking-wider">
                                <th class="py-3.5 px-5 w-[30%]">Área / Departamento</th>
                                <th class="py-3.5 px-5 w-[24%]">Turno Matutino</th>
                                <th class="py-3.5 px-5 w-[24%]">Turno Nocturno</th>
                                <th class="py-3.5 px-5 w-[22%] text-right">Estatus Operativo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.03] font-body-md text-body-md">
                            @foreach($data['cobertura_por_area'] as $area => $turnos)
                                @php $estatus = $estatusCobertura($turnos); @endphp
                                <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary font-bold text-xs">
                                                {{ $inicialesDepto($area) }}
                                            </div>
                                            <span class="font-label-lg text-label-lg text-on-surface font-semibold">{{ $area }}</span>
                                        </div>
                                    </td>

                                    @foreach(['MATUTINO', 'NOCTURNO'] as $turno)
                                        @php $info = $turnos[$turno] ?? ['personal' => 0, 'objetivo' => 0, 'porcentaje' => 0]; @endphp
                                        <td class="py-4 px-5">
                                            <div class="space-y-1.5 w-full">
                                                <div class="flex justify-between font-label-md text-label-md">
                                                    <span class="text-on-surface font-semibold">{{ $info['personal'] }} / {{ $info['objetivo'] }}</span>
                                                    <span class="text-outline">{{ $info['porcentaje'] }}%</span>
                                                </div>
                                                <div class="w-full h-1.5 rounded-full bg-surface-container overflow-hidden">
                                                    <div class="h-full {{ $info['porcentaje'] >= 50 ? 'bg-primary-container' : ($info['porcentaje'] > 0 ? 'bg-secondary-container' : 'bg-error') }} rounded-full" style="width: {{ min($info['porcentaje'], 100) }}%;"></div>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach

                                    <td class="py-4 px-5 text-right">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-md text-label-md font-bold {{ $estatus['clase'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $estatus['punto'] }}"></span>
                                            {{ $estatus['label'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif

            @if(in_array('distribucion_asistencia', $widgetsVisibles))
            <section class="xl:col-span-{{ in_array('cobertura_area', $widgetsVisibles) ? '4' : '12' }} rounded-3xl glass-panel p-6 sm:p-7 space-y-6">

                <div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary">pie_chart</span>
                        <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight">Distribución de Asistencia</h2>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                        Comportamiento de los diferentes estatus de asistencia del día.
                    </p>
                </div>

                <div class="space-y-4">

                    <div class="w-full h-3 rounded-full bg-surface-container overflow-hidden flex shadow-inner">
                        @foreach($desglosePrincipal as $item)
                            <div class="h-full {{ $item['color'] }}" style="width: {{ round(($item['valor'] / $totalDesglose) * 100, 1) }}%;" title="{{ $item['label'] }}: {{ $item['valor'] }}"></div>
                        @endforeach
                    </div>

                    <div class="space-y-2.5">

                        @foreach($desglosePrincipal as $item)
                            <div class="flex items-center justify-between p-2.5 rounded-xl glass-inset">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full {{ $item['color'] }}"></span>
                                    <span class="font-label-lg text-label-lg text-on-surface font-semibold">{{ $item['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-label-caps text-label-caps text-outline">{{ round(($item['valor'] / $totalDesglose) * 100, 1) }}%</span>
                                    <span class="font-headline-sm text-headline-sm font-bold text-on-surface">{{ $item['valor'] }}</span>
                                </div>
                            </div>
                        @endforeach

                        @foreach($desgloseSecundario as $item)
                            <div class="flex items-center justify-between p-2.5 rounded-xl glass-inset opacity-75">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-outline"></span>
                                    <span class="font-label-lg text-label-lg text-on-surface-variant font-medium">{{ $item['label'] }}</span>
                                </div>
                                <span class="font-label-lg text-label-lg font-bold text-outline">{{ $item['valor'] }}</span>
                            </div>
                        @endforeach

                    </div>
                </div>

            </section>
            @endif

        </div>
        @endif

    </div>
</div>

<x-soporte-chat />

@endsection

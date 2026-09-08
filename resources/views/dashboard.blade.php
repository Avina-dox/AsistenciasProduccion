@extends('layouts.app')

@section('content')

@php
// Círculo de progreso para las tarjetas de porcentaje (Asistencia / Ausentismo)
$radius = 42;
$circumference = 2 * M_PI * $radius;

$asistenciaVal = min(max($data['asistencia_porcentaje'], 0), 100);
$asistenciaOffset = $circumference - ($asistenciaVal / 100) * $circumference;

$ausentismoVal = min(max($data['ausentismo_porcentaje'], 0), 100);
$ausentismoOffset = $circumference - ($ausentismoVal / 100) * $circumference;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Roboto+Mono:wght@500;700&display=swap');

    /* Century Gothic si está instalada localmente; si no, cae a Questrial
       (geométrica libre, la más parecida) y luego a un sans-serif del sistema. */
    .font-century {
        font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
            Questrial, 'Avenir Next', sans-serif;
    }

    .mono-font {
        font-family: 'Roboto Mono', 'SFMono-Regular', Consolas, 'Courier New', monospace;
        font-variant-numeric: tabular-nums;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        to {
            background-position: -220% center;
        }
    }

    @keyframes pulseDot {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(16, 185, 129, .55);
        }

        70% {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
    }

    .pulse-dot {
        animation: pulseDot 2s infinite;
    }

    @keyframes spinSlow {
        to {
            transform: rotate(360deg);
        }
    }

    .ring-orbit {
        animation: spinSlow 16s linear infinite;
    }

    .ring-progress {
        transition: stroke-dashoffset 1.2s cubic-bezier(.4, 0, .2, 1);
    }

    /* Marcas de esquina estilo HUD, heredan el color de acento vía currentColor */
    .hud-corners {
        position: relative;
    }

    .hud-corners::before,
    .hud-corners::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        opacity: .28;
        transition: opacity .3s ease;
        pointer-events: none;
    }

    .hud-corners::before {
        top: 10px;
        left: 10px;
        border-top: 2px solid currentColor;
        border-left: 2px solid currentColor;
        border-top-left-radius: 4px;
    }

    .hud-corners::after {
        bottom: 10px;
        right: 10px;
        border-bottom: 2px solid currentColor;
        border-right: 2px solid currentColor;
        border-bottom-right-radius: 4px;
    }

    .hud-corners:hover::before,
    .hud-corners:hover::after {
        opacity: .75;
    }

    /* Textura de puntos sutil de fondo, look "panel de control" */
    .grid-dots {
        background-image: radial-gradient(rgba(106, 44, 117, 0.10) 1px, transparent 1px);
        background-size: 24px 24px;
    }
</style>
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const elemento = document.getElementById('graficaAsistencia');

        if (!elemento) {
            return;
        }

        const datos = @json($data['grafica_asistencia'] ?? []);

        new Chart(elemento, {

            type: 'bar',

            data: {
                labels: datos.map(item => item.dia),

                datasets: [{
                        label: 'Asistencia',
                        data: datos.map(item => item.asistencia),
                        backgroundColor: '#10B981',
                    },
                    {
                        label: 'Faltas',
                        data: datos.map(item => item.faltas),
                        backgroundColor: '#EF4444',
                    },
                    {
                        label: 'Retardos',
                        data: datos.map(item => item.retardos),
                        backgroundColor: '#F59E0B',
                    },
                    {
                        label: 'PCG',
                        data: datos.map(item => item.pcg),
                        backgroundColor: '#3B82F6',
                    },
                    {
                        label: 'PSG',
                        data: datos.map(item => item.psg),
                        backgroundColor: '#8B5CF6',
                    },
                    {
                        label: 'Onomástico',
                        data: datos.map(item => item.onomastico),
                        backgroundColor: '#EC4899',
                    },
                    {
                        label: 'Vacaciones',
                        data: datos.map(item => item.vacaciones),
                        backgroundColor: '#06B6D4',
                    },
                    {
                        label: 'Incapacidad',
                        data: datos.map(item => item.incapacidad),
                        backgroundColor: '#64748B',
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },

                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },

                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }

        });

    });
</script>

@endpush

<div class="font-century grid-dots min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3]">

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- HEADER --}}

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#6A2C75] via-[#5A2465] to-[#45193F] shadow-2xl shadow-[#45193F]/30 border border-[#B6A644]/30">

            {{-- decorative blob --}}
            <div class="absolute right-0 top-0 opacity-[0.08]">
                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 200 200">
                    <path class="text-[#E4D9A0]" d="M46,-73.5C59.2,-66.7,69.5,-53.8,76.5,-39.4C83.5,-25,87.3,-9.2,84.7,5.8C82.1,20.8,73.2,35.1,62.3,47.3C51.5,59.5,38.7,69.5,24.2,75.3C9.8,81,-6.3,82.5,-21.4,78.6C-36.5,74.7,-50.5,65.5,-60.6,53.4C-70.8,41.2,-77,26.1,-79.1,10.3C-81.3,-5.5,-79.4,-21.9,-72.7,-35.7C-66,-49.5,-54.5,-60.8,-41.1,-67.6C-27.8,-74.4,-13.9,-76.7,1.3,-78.8C16.4,-80.8,32.8,-82.4,46,-73.5Z" />
                </svg>
            </div>

            {{-- gold hairline top --}}
            <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-[#E4D9A0] to-transparent"></div>

            <div class="relative z-10 p-10">

                <div class="flex items-center justify-between flex-wrap gap-8">

                    <div class="opacity-0" style="animation: fadeUp 0.8s ease forwards;">

                        <div class="flex items-center gap-3 mb-3 flex-wrap">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#E4D9A0]/60 text-sm font-bold text-[#E4D9A0]">SA</span>
                            <p class="uppercase tracking-[0.30em] text-[#D9BFE0] text-xs font-semibold">
                                Sistema de Control de Asistencias
                            </p>

                            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-emerald-200">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 pulse-dot"></span>
                                En vivo &middot; {{ now()->format('H:i') }}
                            </span>
                        </div>

                        <h1 class="text-4xl md:text-5xl font-bold text-white">
                            ¡Bienvenido, <span class="bg-gradient-to-r from-[#E4D9A0] via-white to-[#E4D9A0] bg-[length:220%_auto] bg-clip-text text-transparent" style="animation: shimmer 6s linear infinite;">{{ auth()->user()->name }}</span>!
                        </h1>

                        <p class="mt-4 text-[#E9DCEC] text-lg font-light">
                            Administra asistencias, permisos, horas extra y personal desde un solo lugar.
                        </p>

                    </div>

                    <div class="hidden lg:flex flex-col items-end opacity-0" style="animation: fadeUp 0.8s 0.15s ease forwards;">

                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl px-8 py-6 border border-[#E4D9A0]/30">

                            <p class="text-[#D9BFE0] uppercase text-xs tracking-widest">
                                Hoy
                            </p>

                            <h2 class="mono-font text-5xl font-bold text-white">
                                {{ now()->format('d') }}
                            </h2>

                            <p class="text-[#E9DCEC] text-lg">
                                {{ now()->translatedFormat('F Y') }}
                            </p>

                            <p class="text-[#E4D9A0] mt-3">
                                {{ now()->translatedFormat('l') }}
                            </p>

                        </div>

                    </div>

                </div>

                <div class="mt-10 flex flex-wrap gap-4 opacity-0" style="animation: fadeUp 0.8s 0.3s ease forwards;">

                    <a
                        href="{{ route('asistencias.index') }}"
                        class="inline-flex items-center gap-2.5 rounded-xl bg-white px-6 py-3 font-semibold text-[#6A2C75] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3.5" y="5" width="17" height="15" rx="2.5" />
                            <path d="M3.5 9.5h17" />
                            <path d="M8 3v3.2M16 3v3.2" />
                            <path d="M12 13v4M10 15h4" />
                        </svg>
                        Registrar Asistencia
                    </a>

                    <a
                        href="{{ route('hora-extras.create') }}"
                        class="inline-flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-[#E4D9A0] to-[#B6A644] px-6 py-3 font-semibold text-[#45193F] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5.2l3.6 2.1" />
                        </svg>
                        Nueva Hora Extra
                    </a>

                    <a
                        href="{{ route('empleados.create') }}"
                        class="inline-flex items-center gap-2.5 rounded-xl bg-white/10 backdrop-blur border border-white/25 px-6 py-3 font-semibold text-white hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="8" r="3.2" />
                            <path d="M3 20a6 6 0 0 1 12 0" />
                            <path d="M18 8v5M15.5 10.5h5" />
                        </svg>
                        Nuevo Empleado
                    </a>

                    <a
                        href="{{ route('usuarios.create') }}"
                        class="inline-flex items-center gap-2.5 rounded-xl bg-white/10 backdrop-blur border border-white/25 px-6 py-3 font-semibold text-white hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3.5 5 6v5.5c0 4.3 2.9 7.4 7 9 4.1-1.6 7-4.7 7-9V6l-7-2.5Z" />
                            <path d="m9.2 12 2 2 3.6-4" />
                        </svg>
                        Nuevo Usuario
                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- KPIs --}}

    <div class="max-w-7xl mx-auto px-6 mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 pb-10">

        {{-- Empleados Activos --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #6A2C7566, rgba(182,166,68,.35), #6A2C7566); animation: fadeUp 0.7s 0.10s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#6A2C75;">
                <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-30" style="background:#6A2C75;"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#6E6274]">Empleados Activos</p>
                        <h2 class="mono-font mt-2 text-4xl font-bold tabular-nums text-[#6A2C75]">
                            {{ $data['empleados_activos'] }}
                        </h2>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110" style="background:#F3EAF5; color:#6A2C75;">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="8" r="3" />
                            <path d="M3.5 20a5.5 5.5 0 0 1 11 0" />
                            <circle cx="17" cy="9" r="2.4" />
                            <path d="M15 20a4.2 4.2 0 0 1 6.8-3.3" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Asistencia (anillo de progreso) --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #05966966, rgba(182,166,68,.35), #05966966); animation: fadeUp 0.7s 0.17s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#059669;">
                <div class="relative flex items-center gap-5">
                    <div class="relative w-24 h-24 shrink-0">
                        <div class="ring-orbit absolute inset-0 -m-1 rounded-full border border-dashed opacity-25" style="border-color:#059669;"></div>
                        <svg class="w-24 h-24 -rotate-90" viewBox="0 0 96 96">
                            <circle cx="48" cy="48" r="{{ $radius }}" fill="none" stroke="#F3EDE3" stroke-width="8" />
                            <circle
                                class="ring-progress"
                                cx="48" cy="48" r="{{ $radius }}" fill="none"
                                stroke="#059669" stroke-width="8" stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $asistenciaOffset }}"
                                style="filter: drop-shadow(0 0 5px rgba(5,150,105,.6));" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="mono-font text-xl font-bold tabular-nums text-emerald-600">{{ $data['asistencia_porcentaje'] }}%</span>
                        </div>
                    </div>
                    <div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2 bg-emerald-50 text-emerald-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="m8.5 12.5 2.4 2.4 4.6-5.3" />
                            </svg>
                        </div>
                        <p class="text-[#6E6274] text-xs uppercase tracking-wider leading-relaxed">Asistencia</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Presentes Hoy --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #0284C766, rgba(182,166,68,.35), #0284C766); animation: fadeUp 0.7s 0.24s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#0284C7;">
                <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-30" style="background:#0284C7;"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#6E6274]">Presentes Hoy</p>
                        <h2 class="mono-font mt-2 text-4xl font-bold tabular-nums text-sky-600">
                            {{ $data['presentes'] }}
                        </h2>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110" style="background:#E6F4FB; color:#0284C7;">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3.5" y="5" width="17" height="15" rx="2.5" />
                            <path d="M3.5 9.5h17" />
                            <path d="M8 3v3.2M16 3v3.2" />
                            <path d="m8.5 14 2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ausentismo (anillo de progreso) --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #DC262666, rgba(182,166,68,.35), #DC262666); animation: fadeUp 0.7s 0.31s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#DC2626;">
                <div class="relative flex items-center gap-5">
                    <div class="relative w-24 h-24 shrink-0">
                        <div class="ring-orbit absolute inset-0 -m-1 rounded-full border border-dashed opacity-25" style="border-color:#DC2626;"></div>
                        <svg class="w-24 h-24 -rotate-90" viewBox="0 0 96 96">
                            <circle cx="48" cy="48" r="{{ $radius }}" fill="none" stroke="#F3EDE3" stroke-width="8" />
                            <circle
                                class="ring-progress"
                                cx="48" cy="48" r="{{ $radius }}" fill="none"
                                stroke="#DC2626" stroke-width="8" stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $ausentismoOffset }}"
                                style="filter: drop-shadow(0 0 5px rgba(220,38,38,.6));" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="mono-font text-xl font-bold tabular-nums text-red-600">{{ $data['ausentismo_porcentaje'] }}%</span>
                        </div>
                    </div>
                    <div>
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2 bg-red-50 text-red-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9" />
                                <path d="m9.5 9.5 5 5m0-5-5 5" />
                            </svg>
                        </div>
                        <p class="text-[#6E6274] text-xs uppercase tracking-wider leading-relaxed">Ausentismo</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Faltas Hoy --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #EA580C66, rgba(182,166,68,.35), #EA580C66); animation: fadeUp 0.7s 0.38s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#EA580C;">
                <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-30" style="background:#EA580C;"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#6E6274]">Faltas Hoy</p>
                        <h2 class="mono-font mt-2 text-4xl font-bold tabular-nums text-orange-600">
                            {{ $data['faltas'] }}
                        </h2>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110" style="background:#FDECE2; color:#EA580C;">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 4 2.5 20h19L12 4Z" />
                            <path d="M12 10.2v4.3" />
                            <circle cx="12" cy="17.3" r="0.9" fill="currentColor" stroke="none" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Horas Extra Pendientes --}}
        <div class="group relative rounded-2xl transition-transform duration-500 hover:-translate-y-1 opacity-0" style="padding:1px; background: linear-gradient(135deg, #B6A64466, rgba(182,166,68,.35), #B6A64466); animation: fadeUp 0.7s 0.45s ease forwards;">
            <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6" style="color:#B6A644;">
                <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-30" style="background:#B6A644;"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#6E6274]">Horas Extra Pendientes</p>
                        <h2 class="mono-font mt-2 text-4xl font-bold tabular-nums text-amber-500">
                            {{ $data['horas_extra_pendientes'] }}
                        </h2>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110" style="background:#F7F2DE; color:#92752F;">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5.2l3.6 2.1" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- KPIs Operativos --}}

    <div class="mt-10 flex justify-center px-6 pb-4">

        <section class="hud-corners relative w-full max-w-6xl rounded-[2rem] border border-slate-100 bg-white/90 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm" style="color:#6A2C75;">

            <div class="mb-8 flex flex-col items-center text-center">

                <span class="mb-3 inline-flex items-center gap-2 rounded-full bg-violet-50 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.22em] text-violet-700">
                    <span class="h-2 w-2 rounded-full bg-violet-500"></span>
                    Operación
                </span>

                <h2 class="text-2xl font-bold text-slate-800">
                    Indicadores Operativos
                </h2>

                <p class="mt-2 text-slate-500">
                    Estado general de asistencia del día.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 justify-items-center">

                {{-- Retardos --}}
                <x-dashboard.kpi
                    titulo="Retardos"
                    valor="{{ $data['retardos'] }}"
                    color="amber">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 3.5h10M7 20.5h10" />
                        <path d="M7.5 3.5v3.2c0 2 1.6 3.4 3.3 4.3.6.3.6 1 0 1.3-1.7.9-3.3 2.3-3.3 4.3v3.4" />
                        <path d="M16.5 3.5v3.2c0 2-1.6 3.4-3.3 4.3-.6.3-.6 1 0 1.3 1.7.9 3.3 2.3 3.3 4.3v3.4" />
                    </svg>
                </x-dashboard.kpi>

                {{-- Horas Extra --}}
                <x-dashboard.kpi
                    titulo="Horas Extra"
                    valor="{{ $data['horas_extra_autorizadas'] }}"
                    color="violet">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 3 4.5 14h5.5l-1 7L18 10h-5.5L13 3Z" />
                    </svg>
                </x-dashboard.kpi>

                {{-- Turno Matutino --}}
                <x-dashboard.kpi
                    titulo="Cobertura Matutina"
                    :valor="$data['cobertura']['matutino']['porcentaje'] . '%'"
                    color="sky"
                    :descripcion="$data['cobertura']['matutino']['personal'] . ' / ' . $data['cobertura']['matutino']['objetivo'] . ' personas'">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4.2" />
                        <path d="M12 2.5v2.4M12 19.1v2.4M4.6 4.6l1.7 1.7M17.7 17.7l1.7 1.7M2.5 12h2.4M19.1 12h2.4M4.6 19.4l1.7-1.7M17.7 6.3l1.7-1.7" />
                    </svg>
                </x-dashboard.kpi>

                {{-- Turno Nocturno --}}
                <x-dashboard.kpi
                    titulo="Cobertura Nocturna"
                    :valor="$data['cobertura']['nocturno']['porcentaje'] . '%'"
                    color="indigo"
                    :descripcion="$data['cobertura']['nocturno']['personal'] . ' / ' . $data['cobertura']['nocturno']['objetivo'] . ' personas'">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 14.2A8 8 0 1 1 9.8 4a6.3 6.3 0 0 0 10.2 10.2Z" />
                        <path d="M17.5 3.5v3M16 5h3" />
                    </svg>
                </x-dashboard.kpi>
            </div>
            {{-- ============================================================
     GRÁFICO DE ASISTENCIA
     ============================================================ --}}

            <div class="hud-corners mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6" style="color:#6A2C75;">

                <div class="mb-6">

                    <h2 class="text-xl font-bold text-slate-800">
                        Distribución de Asistencia
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Comportamiento diario de los diferentes estatus de asistencia.
                    </p>

                </div>

                <div class="relative h-[420px]">

                    <canvas id="graficaAsistencia"></canvas>

                </div>

            </div>



        </section>

    </div>

</div>

<x-soporte-chat />

@endsection

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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

    /* Century Gothic si está instalada localmente; si no, cae a Questrial
       (geométrica libre, la más parecida) y luego a un sans-serif del sistema. */
    .font-century {
        font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
            Questrial, 'Avenir Next', sans-serif;
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

    .kpi-card {
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08), 0 8px 20px rgba(0, 0, 0, 0.04);
        transition: box-shadow .3s ease;
    }

    .kpi-card:hover {
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.12), 0 12px 30px rgba(0, 0, 0, 0.06);
    }

    .kpi-card .accent-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .5s cubic-bezier(.4, 0, .2, 1);
    }

    .kpi-card:hover .accent-bar {
        transform: scaleX(1);
    }

    .kpi-card .glow {
        position: absolute;
        right: -1.5rem;
        bottom: -1.5rem;
        width: 7rem;
        height: 7rem;
        border-radius: 9999px;
        opacity: 0;
        filter: blur(28px);
        transition: opacity .5s ease;
        pointer-events: none;
    }

    .kpi-card:hover .glow {
        opacity: .35;
    }

    .kpi-card:hover .icon-box {
        transform: scale(1.1) rotate(-6deg);
    }

    .icon-box {
        transition: transform .3s ease;
    }

    .ring-progress {
        transition: stroke-dashoffset 1.2s cubic-bezier(.4, 0, .2, 1);
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

<div class="font-century min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3]">

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

                        <div class="flex items-center gap-3 mb-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-[#E4D9A0]/60 text-sm font-bold text-[#E4D9A0]">SA</span>
                            <p class="uppercase tracking-[0.30em] text-[#D9BFE0] text-xs font-semibold">
                                Sistema de Control de Asistencias
                            </p>
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

                            <h2 class="text-5xl font-bold text-white">
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
                        class="inline-flex items-center gap-3 rounded-xl bg-white px-6 py-3 font-semibold text-[#6A2C75] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                        📅 Registrar Asistencia
                    </a>

                    <a
                        href="{{ route('hora-extras.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-gradient-to-r from-[#E4D9A0] to-[#B6A644] px-6 py-3 font-semibold text-[#45193F] shadow-lg shadow-black/10 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                        ⏰ Nueva Hora Extra
                    </a>

                    <a
                        href="{{ route('empleados.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-white/10 backdrop-blur border border-white/25 px-6 py-3 font-semibold text-white hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300">
                        👥 Nuevo Empleado
                    </a>

                    <a
                        href="{{ route('usuarios.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-white/10 backdrop-blur border border-white/25 px-6 py-3 font-semibold text-white hover:bg-white/20 hover:-translate-y-0.5 transition-all duration-300">
                        👤 Nuevo Usuario
                    </a>

                </div>

            </div>

        </div>

    </div>

    {{-- KPIs --}}

    <div class="max-w-7xl mx-auto px-6 mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 pb-10">

        {{-- Empleados Activos --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.10s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #6A2C75);"></div>
            <div class="glow" style="background: #6A2C75;"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider">Empleados Activos</p>
                    <h2 class="mt-2 text-4xl font-bold tabular-nums text-[#6A2C75]">
                        {{ $data['empleados_activos'] }}
                    </h2>
                </div>
                <div class="icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: #F3EAF5;">
                    👥
                </div>
            </div>
        </div>

        {{-- Asistencia (anillo de progreso) --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.17s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #059669);"></div>
            <div class="relative flex items-center gap-5">
                <div class="relative w-24 h-24 shrink-0">
                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 96 96">
                        <circle cx="48" cy="48" r="{{ $radius }}" fill="none" stroke="#F3EDE3" stroke-width="8" />
                        <circle
                            class="ring-progress"
                            cx="48" cy="48" r="{{ $radius }}" fill="none"
                            stroke="#059669" stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $asistenciaOffset }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold tabular-nums text-emerald-600">{{ $data['asistencia_porcentaje'] }}%</span>
                    </div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-2 bg-emerald-50">
                        ✅
                    </div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider leading-relaxed">Asistencia</p>
                </div>
            </div>
        </div>

        {{-- Presentes Hoy --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.24s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #0284C7);"></div>
            <div class="glow" style="background: #0284C7;"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider">Presentes Hoy</p>
                    <h2 class="mt-2 text-4xl font-bold tabular-nums text-sky-600">
                        {{ $data['presentes'] }}
                    </h2>
                </div>
                <div class="icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: #E6F4FB;">
                    📅
                </div>
            </div>
        </div>

        {{-- Ausentismo (anillo de progreso) --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.31s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #DC2626);"></div>
            <div class="relative flex items-center gap-5">
                <div class="relative w-24 h-24 shrink-0">
                    <svg class="w-24 h-24 -rotate-90" viewBox="0 0 96 96">
                        <circle cx="48" cy="48" r="{{ $radius }}" fill="none" stroke="#F3EDE3" stroke-width="8" />
                        <circle
                            class="ring-progress"
                            cx="48" cy="48" r="{{ $radius }}" fill="none"
                            stroke="#DC2626" stroke-width="8" stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $ausentismoOffset }}" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold tabular-nums text-red-600">{{ $data['ausentismo_porcentaje'] }}%</span>
                    </div>
                </div>
                <div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl mb-2 bg-red-50">
                        ❌
                    </div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider leading-relaxed">Ausentismo</p>
                </div>
            </div>
        </div>

        {{-- Faltas Hoy --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.38s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #EA580C);"></div>
            <div class="glow" style="background: #EA580C;"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider">Faltas Hoy</p>
                    <h2 class="mt-2 text-4xl font-bold tabular-nums text-orange-600">
                        {{ $data['faltas'] }}
                    </h2>
                </div>
                <div class="icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: #FDECE2;">
                    ⚠️
                </div>
            </div>
        </div>

        {{-- Horas Extra Pendientes --}}
        <div class="kpi-card bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 p-6 hover:shadow-xl hover:shadow-[#45193F]/10 hover:-translate-y-1 transition-all duration-500 opacity-0" style="animation: fadeUp 0.7s 0.45s ease forwards;">
            <div class="accent-bar" style="background: linear-gradient(90deg, #B6A644, #B6A644);"></div>
            <div class="glow" style="background: #B6A644;"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[#6E6274] text-xs uppercase tracking-wider">Horas Extra Pendientes</p>
                    <h2 class="mt-2 text-4xl font-bold tabular-nums text-amber-500">
                        {{ $data['horas_extra_pendientes'] }}
                    </h2>
                </div>
                <div class="icon-box w-14 h-14 rounded-2xl flex items-center justify-center text-2xl" style="background: #F7F2DE;">
                    ⏰
                </div>
            </div>
        </div>

    </div>
    {{-- KPIs Operativos --}}

    <div class="mt-10 flex justify-center">

        <section class="w-full max-w-6xl rounded-[2rem] border border-slate-100 bg-white/90 p-8 shadow-xl shadow-slate-200/50 backdrop-blur-sm">

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
                    icono="⌛"
                    color="amber" />

                {{-- Horas Extra --}}
                <x-dashboard.kpi
                    titulo="Horas Extra"
                    valor="{{ $data['horas_extra_autorizadas'] }}"
                    icono="⏰"
                    color="violet" />

                {{-- Turno Matutino --}}
                <x-dashboard.kpi
                    titulo="Cobertura Matutina"
                    :valor="$data['cobertura']['matutino']['porcentaje'] . '%'"
                    icono="🌞"
                    color="sky"
                    :descripcion="$data['cobertura']['matutino']['personal'] . ' / ' . $data['cobertura']['matutino']['objetivo'] . ' personas'" />

                {{-- Turno Nocturno --}}
                <x-dashboard.kpi
                    titulo="Cobertura Nocturna"
                    :valor="$data['cobertura']['nocturno']['porcentaje'] . '%'"
                    icono="🌙"
                    color="indigo"
                    :descripcion="$data['cobertura']['nocturno']['personal'] . ' / ' . $data['cobertura']['nocturno']['objetivo'] . ' personas'" />
            </div>
            {{-- ============================================================
     GRÁFICO DE ASISTENCIA
     ============================================================ --}}

            <div class="mt-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

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

@endsection
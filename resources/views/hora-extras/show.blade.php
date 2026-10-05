@extends('layouts.app')

@section('content')

@php
    // Colorea el estatus según palabras clave comunes, sin depender de un catálogo fijo.
    $n = mb_strtolower($horaExtra->estatus->nombre ?? '');
    if (str_contains($n, 'aprob') || str_contains($n, 'autoriz')) {
        $estatusClasses = ['wrap' => 'bg-tertiary-container/20 text-on-tertiary-container', 'dot' => 'bg-on-tertiary-container'];
    } elseif (str_contains($n, 'rechaz') || str_contains($n, 'denegad')) {
        $estatusClasses = ['wrap' => 'bg-error-container/40 text-error', 'dot' => 'bg-error'];
    } elseif (str_contains($n, 'pendient') || str_contains($n, 'revis')) {
        $estatusClasses = ['wrap' => 'bg-secondary-container/50 text-on-secondary-container', 'dot' => 'bg-on-secondary-container'];
    } else {
        $estatusClasses = ['wrap' => 'bg-surface-container-high text-on-surface-variant', 'dot' => 'bg-on-surface-variant'];
    }
@endphp

<div class="relative bg-background min-h-screen">
    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        @if(session('success'))
            <div class="rounded-2xl bg-tertiary-container/20 border border-tertiary-container/40 text-on-tertiary-container p-4 font-body-md text-body-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-error-container/40 border border-error-container text-error p-4 font-body-md text-body-md flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">cancel</span>
                {{ session('error') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Solicitud de Horas Extra</p>
                <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">schedule</span>
                    {{ $horaExtra->folio }}
                </h1>
            </div>

            <a href="{{ route('hora-extras.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-5 py-2.5 font-label-md text-label-md font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Regresar
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-6 sm:p-8 space-y-6">

            {{-- DETAIL GRID --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Departamento</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->departamento->nombre }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Supervisor</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->supervisor->name }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Fecha</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ \Carbon\Carbon::parse($horaExtra->fecha)->format('d/m/Y') }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Estado</p>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-md text-label-md font-bold {{ $estatusClasses['wrap'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $estatusClasses['dot'] }}"></span>
                        {{ $horaExtra->estatus->nombre }}
                    </span>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Hora Inicio</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->hora_inicio }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Hora Fin</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->hora_fin }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Tipo</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->tipo }}
                    </p>
                </div>

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Registró</p>
                    <p class="font-label-lg text-label-lg font-semibold text-on-surface">
                        {{ $horaExtra->supervisor->name }}
                    </p>
                </div>

            </div>

            {{-- MOTIVO --}}
            <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                <h2 class="font-label-md text-label-md font-bold text-on-surface flex items-center gap-1.5 mb-2">
                    <span class="material-symbols-outlined text-[18px] text-primary-container">description</span>
                    Motivo
                </h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    {{ $horaExtra->motivo }}
                </p>
            </div>

            {{-- EMPLEADOS --}}
            <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary-container">group</span>
                    Empleados
                </h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.04]">

                        <thead>
                            <tr class="bg-surface-container-high/40">
                                <th class="px-4 py-3 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Empleado</th>
                                <th class="px-4 py-3 text-center font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Horas</th>
                                <th class="px-4 py-3 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Observaciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-black/[0.04]">
                            @foreach($horaExtra->detalles as $detalle)
                                <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-4 py-3 font-body-md text-body-md text-on-surface">
                                        {{ $detalle->empleado->apellido_paterno }}
                                        {{ $detalle->empleado->apellido_materno }}
                                        {{ $detalle->empleado->nombre }}
                                    </td>

                                    <td class="px-4 py-3 text-center font-body-md text-body-md text-on-surface">
                                        {{ $detalle->horas }}
                                    </td>

                                    <td class="px-4 py-3 font-body-md text-body-md text-on-surface-variant">
                                        {{ $detalle->observaciones }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>

            @if($horaExtra->estatus->nombre == 'PENDIENTE')

                @can('aprobar horas extra')

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <form
                        action="{{ route('hora-extras.aprobar',$horaExtra) }}"
                        method="POST">

                        @csrf

                        @method('PATCH')

                        <button
                            class="inline-flex items-center gap-1.5 rounded-full bg-tertiary-container/30 hover:bg-tertiary-container/50 text-on-tertiary-container px-5 py-2.5 font-label-lg text-label-lg font-semibold transition-all">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span> Aprobar Solicitud
                        </button>

                    </form>
                </div>

                @endcan

                @can('rechazar horas extra')

                <div class="rounded-2xl bg-white/70 dark:bg-surface-container-high/30 p-4">
                    <form
                        action="{{ route('hora-extras.rechazar',$horaExtra) }}"
                        method="POST">

                        @csrf

                        @method('PATCH')

                        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                            Motivo del rechazo
                        </label>

                        <textarea
                            name="observaciones_coordinacion"
                            rows="4"
                            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"
                            placeholder="Motivo del rechazo..."></textarea>

                        <button
                            class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-error-container/40 hover:bg-error hover:text-on-error text-error px-5 py-2.5 font-label-lg text-label-lg font-semibold transition-all">
                            <span class="material-symbols-outlined text-[18px]">cancel</span> Rechazar Solicitud
                        </button>

                    </form>
                </div>

                @endcan

            @endif

        </div>

    </div>

</div>

@endsection

@php
    $tarjetas = [
        ['label' => 'Asistencias', 'valor' => $totales['A'], 'icon' => 'check_circle', 'classes' => 'bg-tertiary-container/20 text-on-tertiary-container'],
        ['label' => 'Faltas', 'valor' => $totales['F'], 'icon' => 'cancel', 'classes' => 'bg-error-container/40 text-error'],
        ['label' => 'Retardos', 'valor' => $totales['R'], 'icon' => 'schedule', 'classes' => 'bg-secondary-container/40 text-on-secondary-container'],
        ['label' => 'Vacaciones', 'valor' => $totales['V'], 'icon' => 'beach_access', 'classes' => 'bg-primary-container/25 text-on-primary-container'],
        ['label' => 'Incapacidades', 'valor' => $totales['I'], 'icon' => 'medical_services', 'classes' => 'bg-primary-container/25 text-on-primary-container'],
        ['label' => 'PCG', 'valor' => $totales['PCG'], 'icon' => 'event_available', 'classes' => 'bg-secondary-container/40 text-on-secondary-container'],
        ['label' => 'PSG', 'valor' => $totales['PSG'], 'icon' => 'event_busy', 'classes' => 'bg-surface-container-high/60 text-outline'],
        ['label' => 'Onomástico', 'valor' => $totales['O'], 'icon' => 'cake', 'classes' => 'bg-tertiary-container/20 text-on-tertiary-container'],
        ['label' => 'Suspensiones', 'valor' => $totales['S'], 'icon' => 'block', 'classes' => 'bg-error-container/40 text-error'],
    ];
@endphp

<div>

    {{-- Encabezado del empleado --}}
    <div class="border-b border-black/[0.06] dark:border-white/10 px-6 py-5 bg-gradient-to-br from-primary to-primary-container">

        <p class="font-label-caps text-label-caps uppercase tracking-wider text-white/70 font-semibold">
            Resumen de asistencias
        </p>
        <h3 class="mt-1 truncate font-headline-md text-headline-md font-bold text-white">
            {{ trim($empleado->apellido_paterno . ' ' . $empleado->apellido_materno . ' ' . $empleado->nombre) }}
        </h3>
        <p class="mt-1 font-body-sm text-body-sm text-white/85">
            {{ $empleado->codigo_empleado }}
            &middot;
            {{ $empleado->departamento?->nombre ?? 'Sin departamento' }}
            &middot;
            {{ $empleado->turno?->nombre ?? 'Sin turno' }}
        </p>

    </div>

    {{-- Tarjetas de estatus --}}
    <div class="grid grid-cols-2 gap-3 px-6 py-5 sm:grid-cols-3">

        @foreach($tarjetas as $tarjeta)
            <div class="rounded-2xl {{ $tarjeta['classes'] }} p-3 text-center transition-transform duration-200 hover:-translate-y-0.5">
                <span class="material-symbols-outlined text-[18px]">{{ $tarjeta['icon'] }}</span>
                <p class="text-2xl font-bold tabular-nums leading-tight">{{ $tarjeta['valor'] }}</p>
                <p class="mt-0.5 font-label-md text-label-md uppercase tracking-wide">{{ $tarjeta['label'] }}</p>
            </div>
        @endforeach

    </div>

    {{-- Datos adicionales --}}
    <div class="grid grid-cols-2 gap-3 border-t border-black/[0.06] dark:border-white/10 px-6 py-4">

        <div class="rounded-2xl bg-tertiary-container/20 p-3 text-center">
            <p class="text-xl font-bold tabular-nums text-on-tertiary-container">{{ number_format($totales['HE'], 2) }} h</p>
            <p class="mt-0.5 font-label-md text-label-md uppercase tracking-wide text-on-tertiary-container">Horas Extra</p>
        </div>

        <div class="rounded-2xl bg-secondary-container/40 p-3 text-center">
            <p class="text-xl font-bold tabular-nums text-on-secondary-container">{{ $totales['RETARDO_MIN'] }} min</p>
            <p class="mt-0.5 font-label-md text-label-md uppercase tracking-wide text-on-secondary-container">Retardo acumulado</p>
        </div>

    </div>

    <p class="border-t border-black/[0.06] dark:border-white/10 px-6 py-3 text-center font-body-sm text-body-sm text-outline">
        Periodo: {{ $desde->format('d/m/Y') }} &ndash; {{ $hasta->format('d/m/Y') }}
    </p>

</div>

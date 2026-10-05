@extends('layouts.app')

@section('content')

@php
    // Colorea el estatus según palabras clave comunes, sin depender de un catálogo fijo.
    $badgeClasses = function ($nombre) {
        $n = mb_strtolower($nombre ?? '');
        if (str_contains($n, 'aprob') || str_contains($n, 'autoriz')) {
            return ['wrap' => 'bg-tertiary-container/20 text-on-tertiary-container', 'dot' => 'bg-on-tertiary-container'];
        }
        if (str_contains($n, 'rechaz') || str_contains($n, 'denegad')) {
            return ['wrap' => 'bg-error-container/40 text-error', 'dot' => 'bg-error'];
        }
        if (str_contains($n, 'pendient') || str_contains($n, 'revis')) {
            return ['wrap' => 'bg-secondary-container/50 text-on-secondary-container', 'dot' => 'bg-on-secondary-container'];
        }
        return ['wrap' => 'bg-surface-container-high text-on-surface-variant', 'dot' => 'bg-on-surface-variant'];
    };
@endphp

<div class="relative bg-background min-h-screen">
    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Gestión de Personal</p>
                <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">timer</span>
                    Solicitudes de Horas Extra
                </h1>
                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Consulta, da seguimiento y solicita autorizaciones de horas extra.</p>
            </div>

            <a
                href="{{ route('hora-extras.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">alarm_add</span> Nueva Solicitud
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/[0.04]">

                    <thead>
                        <tr class="bg-surface-container-high/40">
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Folio</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Fecha</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Departamento</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Supervisor</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Estado</th>
                            <th class="px-5 py-4 text-center font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-black/[0.04]">
                        @forelse($solicitudes as $i => $solicitud)
                            @php $c = $badgeClasses($solicitud->estatus->nombre); @endphp
                            <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">
                                <td class="px-5 py-4 font-label-lg text-label-lg font-semibold text-on-surface">
                                    {{ $solicitud->folio }}
                                </td>

                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant">
                                    {{ $solicitud->fecha }}
                                </td>

                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface">
                                    {{ $solicitud->departamento->nombre }}
                                </td>

                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface">
                                    {{ $solicitud->supervisor->name }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-label-md text-label-md font-bold {{ $c['wrap'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                        {{ $solicitud->estatus->nombre }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a
                                        href="{{ route('hora-extras.show', $solicitud) }}"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-4 py-2 font-label-md text-label-md font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span> Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-12">
                                    <div class="flex flex-col items-center gap-2 text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[40px] text-outline">inbox</span>
                                        <p class="font-body-md text-body-md font-medium">No existen solicitudes.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        <div>
            {{ $solicitudes->links() }}
        </div>

    </div>

</div>

@endsection

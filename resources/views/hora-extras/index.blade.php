@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

    .font-century {
        font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
                     Questrial, 'Avenir Next', sans-serif;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .row-fade { opacity: 0; animation: fadeUp .5s ease forwards; }

    .badge {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .3rem .8rem; border-radius: 9999px;
        font-size: .72rem; font-weight: 600; letter-spacing: .02em;
        text-transform: capitalize;
    }
    .badge::before { content: ''; width: .4rem; height: .4rem; border-radius: 9999px; background: currentColor; }
</style>

@php
    // Colorea el estatus según palabras clave comunes, sin depender de un catálogo fijo.
    $badgeColor = function ($nombre) {
        $n = mb_strtolower($nombre ?? '');
        if (str_contains($n, 'aprob')) return ['bg' => '#ECFDF5', 'fg' => '#059669'];
        if (str_contains($n, 'rechaz') || str_contains($n, 'denegad')) return ['bg' => '#FEF2F2', 'fg' => '#DC2626'];
        if (str_contains($n, 'pendient') || str_contains($n, 'revis')) return ['bg' => '#F7F2DE', 'fg' => '#B6A644'];
        return ['bg' => '#F3EAF5', 'fg' => '#6A2C75'];
    };
@endphp

<div class="font-century min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3]">

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center flex-wrap gap-4 mb-8 opacity-0" style="animation: fadeUp .6s ease forwards;">

            <div>
                <p class="uppercase tracking-[0.30em] text-[#6A2C75]/60 text-xs font-semibold mb-1">
                    Gestión de Personal
                </p>
                <h1 class="text-3xl font-bold text-[#2B2030]">
                    Solicitudes de Horas Extra
                </h1>
            </div>

            <a
                href="{{ route('hora-extras.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#6A2C75] to-[#45193F] px-6 py-3 font-semibold text-white shadow-lg shadow-[#45193F]/20 hover:-translate-y-0.5 hover:shadow-xl transition-all duration-300">
                <span class="text-lg leading-none">+</span> Nueva Solicitud
            </a>

        </div>

        {{-- TABLE CARD --}}
        <div class="relative bg-white rounded-2xl shadow-sm border border-[#2B2030]/10 overflow-hidden opacity-0" style="animation: fadeUp .6s .1s ease forwards;">

            {{-- gold hairline --}}
            <div class="h-[3px] bg-gradient-to-r from-[#B6A644] via-[#6A2C75] to-[#B6A644]"></div>

            <div class="overflow-x-auto">
                <table class="min-w-full">

                    <thead>
                        <tr class="border-b border-[#2B2030]/10">
                            <th class="p-4 text-left text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Folio</th>
                            <th class="p-4 text-left text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Fecha</th>
                            <th class="p-4 text-left text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Departamento</th>
                            <th class="p-4 text-left text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Supervisor</th>
                            <th class="p-4 text-left text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Estado</th>
                            <th class="p-4 text-center text-xs uppercase tracking-wider text-[#6E6274] font-semibold">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($solicitudes as $i => $solicitud)
                            @php $c = $badgeColor($solicitud->estatus->nombre); @endphp
                            <tr
                                class="row-fade border-b border-[#2B2030]/5 last:border-0 hover:bg-[#6A2C75]/[0.03] transition-colors duration-200"
                                style="animation-delay: {{ $i * 0.05 }}s;"
                            >
                                <td class="p-4 font-semibold text-[#2B2030]">
                                    {{ $solicitud->folio }}
                                </td>

                                <td class="p-4 text-[#6E6274]">
                                    {{ $solicitud->fecha }}
                                </td>

                                <td class="p-4 text-[#2B2030]">
                                    {{ $solicitud->departamento->nombre }}
                                </td>

                                <td class="p-4 text-[#2B2030]">
                                    {{ $solicitud->supervisor->name }}
                                </td>

                                <td class="p-4">
                                    <span class="badge" style="background: {{ $c['bg'] }}; color: {{ $c['fg'] }};">
                                        {{ $solicitud->estatus->nombre }}
                                    </span>
                                </td>

                                <td class="p-4 text-center">
                                    <a
                                        href="{{ route('hora-extras.show', $solicitud) }}"
                                        class="inline-flex items-center gap-1 text-[#6A2C75] font-semibold text-sm hover:text-[#45193F] hover:gap-2 transition-all duration-200">
                                        Ver <span aria-hidden="true">→</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-12">
                                    <div class="flex flex-col items-center gap-2 text-[#6E6274]">
                                        <span class="text-3xl">📭</span>
                                        <p class="font-medium">No existen solicitudes.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        <div class="mt-6 opacity-0" style="animation: fadeUp .6s .2s ease forwards;">
            {{ $solicitudes->links() }}
        </div>

    </div>

</div>

@endsection
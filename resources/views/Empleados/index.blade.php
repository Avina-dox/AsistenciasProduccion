<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

        .font-century {
            font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
                Questrial, 'Avenir Next', sans-serif;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .row-fade {
            opacity: 0;
            animation: fadeUp .5s ease forwards;
        }

        .estatus-select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236E6274'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .6rem center;
            background-size: 1rem;
            padding-right: 2rem;
        }
    </style>

    @php
    $estatusColor = function ($estatus) {
    return match ($estatus) {
    'ACTIVO' => ['bg' => '#ECFDF5', 'fg' => '#059669'],
    'INACTIVO' => ['bg' => '#F1F0EF', 'fg' => '#6E6274'],
    'VACACIONES' => ['bg' => '#F7F2DE', 'fg' => '#B6A644'],
    'BAJA' => ['bg' => '#FEF2F2', 'fg' => '#DC2626'],
    default => ['bg' => '#F3EAF5', 'fg' => '#6A2C75'],
    };
    };
    @endphp

    <div class="font-century min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3] p-6">

        {{-- HEADER --}}
        <div class="max-w-7xl mx-auto flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-8 opacity-0" style="animation: fadeUp .6s ease forwards;">

            <div>
                <p class="uppercase tracking-[0.30em] text-[#6A2C75]/60 text-xs font-semibold mb-1">
                    Gestión de Personal
                </p>
                <h1 class="text-3xl font-bold text-[#2B2030]">Empleados</h1>
                <p class="mt-1 text-sm text-[#6E6274]">Gestiona empleados y turnos desde un solo lugar.</p>
            </div>

            <a href="{{ route('empleados.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#6A2C75] to-[#45193F] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-[#45193F]/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl">
                <span class="text-lg leading-none">+</span> Nuevo Empleado
            </a>

        </div>

        {{-- TABLE CARD --}}
        <div class="max-w-7xl mx-auto relative overflow-hidden rounded-2xl bg-white shadow-sm border border-[#2B2030]/10 opacity-0" style="animation: fadeUp .6s .1s ease forwards;">

            {{-- gold hairline --}}
            <div class="h-[3px] bg-gradient-to-r from-[#B6A644] via-[#6A2C75] to-[#B6A644]"></div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#2B2030]/5">

                    <thead>
                        <tr class="border-b border-[#2B2030]/10">
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Código</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Nombre</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Departamento</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Turno</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Horario</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Estatus</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-[#6E6274]">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#2B2030]/5">

                        @forelse($empleados as $i => $empleado)

                        @php

                        $c = $estatusColor($empleado->estatus);

                        $claveHorario =
                        $empleado->turno_id .
                        '-' .
                        $empleado->departamento_id;

                        $horario = $horarios->get($claveHorario);

                        @endphp

                        <tr
                            class="row-fade transition-colors duration-200 hover:bg-[#6A2C75]/[0.03]"
                            style="animation-delay: {{ $i * 0.04 }}s;">

                            {{-- Código --}}
                            <td class="px-5 py-4 text-sm text-[#6E6274]">

                                {{ $empleado->codigo_empleado }}

                            </td>


                            {{-- Nombre --}}
                            <td class="px-5 py-4 text-sm">

                                <div class="font-semibold text-[#2B2030]">

                                    {{ $empleado->nombre }}
                                    {{ $empleado->apellido_paterno }}

                                </div>

                            </td>


                            {{-- Departamento --}}
                            <td class="px-5 py-4 text-sm text-[#2B2030]">

                                {{ $empleado->departamento->nombre ?? 'Sin departamento' }}

                            </td>


                            {{-- Turno --}}
                            <td class="px-5 py-4 text-sm text-[#2B2030]">

                                {{ $empleado->turno->nombre ?? 'Sin turno' }}

                            </td>


                            {{-- Horario --}}
                            <td class="px-5 py-4 text-sm text-[#6E6274]">

                                @if($horario)

                                <span class="font-medium text-[#2B2030]">

                                    {{ \Carbon\Carbon::parse($horario->hora_entrada)->format('H:i') }}

                                    -

                                    {{ \Carbon\Carbon::parse($horario->hora_salida)->format('H:i') }}

                                </span>

                                @else

                                <span class="text-gray-400">

                                    Sin horario

                                </span>

                                @endif

                            </td>


                            {{-- Estatus --}}
                            <td class="px-5 py-4 text-sm">

                                <form
                                    action="{{ route('empleados.update', $empleado) }}"
                                    method="POST">

                                    @csrf

                                    @method('PUT')


                                    <input
                                        type="hidden"
                                        name="codigo_empleado"
                                        value="{{ $empleado->codigo_empleado }}">

                                    <input
                                        type="hidden"
                                        name="nombre"
                                        value="{{ $empleado->nombre }}">

                                    <input
                                        type="hidden"
                                        name="apellido_paterno"
                                        value="{{ $empleado->apellido_paterno }}">

                                    <input
                                        type="hidden"
                                        name="departamento_id"
                                        value="{{ $empleado->departamento_id }}">

                                    <input
                                        type="hidden"
                                        name="turno_id"
                                        value="{{ $empleado->turno_id }}">


                                    <select
                                        name="estatus"
                                        onchange="this.form.submit()"
                                        class="estatus-select rounded-full border-0 px-3 py-1.5 text-xs font-semibold cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/30"
                                        style="
                            background-color: {{ $c['bg'] }};
                            color: {{ $c['fg'] }};
                        ">

                                        <option
                                            value="ACTIVO"
                                            {{ $empleado->estatus == 'ACTIVO' ? 'selected' : '' }}>
                                            ACTIVO
                                        </option>

                                        <option
                                            value="INACTIVO"
                                            {{ $empleado->estatus == 'INACTIVO' ? 'selected' : '' }}>
                                            INACTIVO
                                        </option>

                                        <option
                                            value="VACACIONES"
                                            {{ $empleado->estatus == 'VACACIONES' ? 'selected' : '' }}>
                                            VACACIONES
                                        </option>

                                        <option
                                            value="BAJA"
                                            {{ $empleado->estatus == 'BAJA' ? 'selected' : '' }}>
                                            BAJA
                                        </option>

                                    </select>

                                </form>

                            </td>


                            {{-- Acciones --}}
                            <td class="px-5 py-4 text-sm">

                                <div class="flex flex-wrap gap-2">

                                    <a
                                        href="{{ route('empleados.edit', $empleado) }}"
                                        class="inline-flex items-center rounded-xl bg-gradient-to-r from-[#E4D9A0] to-[#B6A644] px-4 py-2 text-sm font-semibold text-[#45193F] shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-md">
                                        Editar
                                    </a>


                                    <form
                                        method="POST"
                                        action="{{ route('empleados.destroy', $empleado) }}"
                                        onsubmit="return confirm('¿Eliminar este empleado?');">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-xl bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition-all duration-300 hover:-translate-y-0.5 hover:bg-red-600 hover:text-white hover:shadow-md">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center p-12">

                                <div class="flex flex-col items-center gap-2 text-[#6E6274]">

                                    <span class="text-3xl">
                                        🗂️
                                    </span>

                                    <p class="font-medium">
                                        No hay empleados registrados.
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

        <div class="max-w-7xl mx-auto mt-6 opacity-0" style="animation: fadeUp .6s .2s ease forwards;">
            {{ $empleados->links() }}
        </div>

    </div>

</x-app-layout>
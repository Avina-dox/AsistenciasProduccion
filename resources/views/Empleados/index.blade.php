<x-app-layout>

    @php
    $estatusBadge = function ($estatus) {
    return match ($estatus) {
    'ACTIVO' => 'bg-tertiary-container/20 text-on-tertiary-container',
    'INACTIVO' => 'bg-surface-container-high/60 text-outline',
    'VACACIONES' => 'bg-secondary-container/40 text-on-secondary-container',
    'BAJA' => 'bg-error-container/40 text-error',
    default => 'bg-primary-container/25 text-on-primary-container',
    };
    };

    $rolClasses = function ($rol) {
    return match ($rol) {
    'Admin' => 'bg-primary-container/25 text-on-primary-container',
    'RH' => 'bg-tertiary-container/20 text-on-tertiary-container',
    'Supervisor' => 'bg-secondary-container/40 text-on-secondary-container',
    'Coordinacion' => 'bg-primary/10 text-primary',
    default => 'bg-surface-container-high/60 text-outline',
    };
    };
    @endphp

    <div x-data="resumenAsistenciasModal()" class="relative bg-background min-h-screen">

        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Gestión de Personal</p>
                    <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">badge</span>
                        Empleados
                    </h1>
                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Gestiona empleados y turnos desde un solo lugar.</p>
                </div>

                <a href="{{ route('empleados.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[18px]">person_add</span> Nuevo Empleado
                </a>
            </div>

            {{-- BUSCADOR --}}
            <div>
                <form method="GET" action="{{ route('empleados.index') }}" class="flex flex-col sm:flex-row gap-3">

                    <div class="relative flex-1">

                        <span class="material-symbols-outlined pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Buscar por nombre, apellido o código…"
                            class="w-full rounded-xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface py-2.5 pl-10 pr-4 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                    </div>

                    <div class="flex gap-2">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-5 py-2.5 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">search</span> Buscar
                        </button>

                        @if(request('search'))
                            <a
                                href="{{ route('empleados.index') }}"
                                class="inline-flex items-center justify-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-5 py-2.5 font-label-lg text-label-lg font-semibold text-on-surface-variant shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                                <span class="material-symbols-outlined text-[18px]">close</span> Limpiar
                            </a>
                        @endif

                    </div>

                </form>
            </div>

            {{-- TABLE CARD --}}
            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.04]">

                        <thead>
                            <tr class="bg-surface-container-high/40">
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant w-14">#</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Código</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Nombre</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Cuenta / Rol</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Departamento</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Turno</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Horario</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Estatus</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-black/[0.04]">

                            @forelse($empleados as $i => $empleado)

                            @php

                            $claveHorario =
                            $empleado->turno_id .
                            '-' .
                            $empleado->departamento_id;

                            $horario = $horarios->get($claveHorario);

                            @endphp

                            <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">

                                {{-- # --}}
                                <td class="px-5 py-4 font-label-md text-label-md text-outline">

                                    {{ $empleados->firstItem() + $i }}

                                </td>

                                {{-- Código --}}
                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant">

                                    {{ $empleado->codigo_empleado }}

                                </td>


                                {{-- Nombre --}}
                                <td class="px-5 py-4 font-label-lg text-label-lg">

                                    <div class="font-semibold text-on-surface">

                                        {{ $empleado->apellido_paterno }}
                                        {{ $empleado->apellido_materno }}
                                        {{ $empleado->nombre }}

                                    </div>

                                </td>


                                {{-- Cuenta / Rol --}}
                                <td class="px-5 py-4 font-body-md text-body-md">

                                    @if($empleado->user)

                                        <div class="flex flex-col gap-1">

                                            <span class="font-body-sm text-body-sm text-on-surface-variant">
                                                {{ $empleado->user->name }}
                                            </span>

                                            <div class="flex flex-wrap gap-1">

                                                @forelse($empleado->user->roles as $rol)

                                                    <span class="inline-block rounded-full px-2 py-0.5 font-label-md text-label-md font-semibold {{ $rolClasses($rol->name) }}">
                                                        {{ $rol->name }}
                                                    </span>

                                                @empty

                                                    <span class="inline-block rounded-full bg-surface-container-high/60 px-2 py-0.5 font-label-md text-label-md font-semibold text-outline">
                                                        Sin rol
                                                    </span>

                                                @endforelse

                                            </div>

                                        </div>

                                    @else

                                        <span class="font-label-md text-label-md font-medium text-outline">
                                            Sin cuenta vinculada
                                        </span>

                                    @endif

                                </td>


                                {{-- Departamento --}}
                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface">

                                    {{ $empleado->departamento->nombre ?? 'Sin departamento' }}

                                </td>


                                {{-- Turno --}}
                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface">

                                    {{ $empleado->turno->nombre ?? 'Sin turno' }}

                                </td>


                                {{-- Horario --}}
                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant">

                                    @if($horario)

                                    <span class="font-semibold text-on-surface">

                                        {{ \Carbon\Carbon::parse($horario->hora_entrada)->format('H:i') }}

                                        -

                                        {{ \Carbon\Carbon::parse($horario->hora_salida)->format('H:i') }}

                                    </span>

                                    @else

                                    <span class="text-outline">

                                        Sin horario

                                    </span>

                                    @endif

                                </td>


                                {{-- Estatus --}}
                                <td class="px-5 py-4 font-body-md text-body-md">

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
                                            class="appearance-none bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%27http://www.w3.org/2000/svg%27%20viewBox=%270%200%2020%2020%27%20fill=%27%239AA0A6%27%3E%3Cpath%20fill-rule=%27evenodd%27%20d=%27M5.23%207.21a.75.75%200%20011.06.02L10%2011.19l3.71-3.96a.75.75%200%20111.08%201.04l-4.25%204.5a.75.75%200%2001-1.08%200l-4.25-4.5a.75.75%200%2001.02-1.06z%27%20clip-rule=%27evenodd%27/%3E%3C/svg%3E')] bg-no-repeat bg-[right_0.6rem_center] bg-[length:1rem] rounded-full border-0 pl-3 pr-8 py-1.5 font-label-md text-label-md font-bold cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary/30 {{ $estatusBadge($empleado->estatus) }}">

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

                                            @hasanyrole('RH|Admin|Coordinacion')
                                            <option
                                                value="BAJA"
                                                {{ $empleado->estatus == 'BAJA' ? 'selected' : '' }}>
                                                BAJA
                                            </option>
                                            @endhasanyrole

                                        </select>

                                    </form>

                                </td>


                                {{-- Acciones --}}
                                <td class="px-5 py-4 font-body-md text-body-md">

                                    <div class="flex flex-wrap gap-2">

                                        <button
                                            type="button"
                                            @click="abrir({{ $empleado->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-4 py-2 font-label-md text-label-md font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            Ver
                                        </button>

                                        <a
                                            href="{{ route('empleados.edit', $empleado) }}"
                                            class="inline-flex items-center gap-1.5 rounded-full bg-secondary-container/50 hover:bg-secondary-container px-4 py-2 font-label-md text-label-md font-semibold text-on-secondary-container transition-all">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                            Editar
                                        </a>


                                        @hasanyrole('RH|Admin|Coordinacion')
                                        <form
                                            method="POST"
                                            action="{{ route('empleados.destroy', $empleado) }}"
                                            onsubmit="return confirm('¿Eliminar este empleado?');">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-full bg-error-container/50 hover:bg-error hover:text-on-error px-4 py-2 font-label-md text-label-md font-semibold text-error transition-all">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                                Eliminar
                                            </button>

                                        </form>
                                        @endhasanyrole

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center p-12">

                                    <div class="flex flex-col items-center gap-2 text-on-surface-variant">

                                        <span class="material-symbols-outlined text-[32px] text-outline">
                                            folder_off
                                        </span>

                                        @if(request('search'))

                                            <p class="font-label-lg text-label-lg font-medium">
                                                No se encontraron empleados para "{{ request('search') }}".
                                            </p>

                                            <a href="{{ route('empleados.index') }}" class="font-body-sm text-body-sm font-semibold text-primary hover:underline">
                                                Limpiar búsqueda
                                            </a>

                                        @else

                                            <p class="font-label-lg text-label-lg font-medium">
                                                No hay empleados registrados.
                                            </p>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>

            <div>
                {{ $empleados->links() }}
            </div>

        </div>

        {{-- MODAL: Resumen de asistencias --}}
        <div
            x-show="abierto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="cerrar()"
            style="display:none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4 backdrop-blur-sm">

            <div
                @click.outside="cerrar()"
                x-show="abierto"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-surface-container-lowest/95 backdrop-blur-2xl shadow-[0_20px_60px_rgba(74,30,82,0.25)]">

                {{-- Selector de periodo --}}
                <div class="flex items-end gap-3 border-b border-black/[0.06] dark:border-white/10 bg-surface-container-high/40 px-5 py-3">

                    <div class="flex-1">
                        <label class="block font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">
                            Desde
                        </label>
                        <input
                            type="date"
                            x-model="desde"
                            :max="hasta"
                            @change="cargar()"
                            class="mt-1 w-full rounded-xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-2 py-1.5 font-body-sm text-body-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="flex-1">
                        <label class="block font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">
                            Hasta
                        </label>
                        <input
                            type="date"
                            x-model="hasta"
                            :min="desde"
                            @change="cargar()"
                            class="mt-1 w-full rounded-xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-2 py-1.5 font-body-sm text-body-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <button
                        type="button"
                        @click="cerrar()"
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-on-surface-variant transition-colors duration-200 hover:bg-black/10 dark:hover:bg-white/10"
                        aria-label="Cerrar">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>

                </div>

                <div class="max-h-[65vh] overflow-y-auto">

                    <div x-show="cargando" style="display:none;" class="flex items-center justify-center gap-2 px-6 py-20 text-on-surface-variant">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-primary"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-primary" style="animation-delay:.15s;"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-primary" style="animation-delay:.3s;"></span>
                    </div>

                    <div x-show="!cargando" style="display:none;" x-html="contenido"></div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function resumenAsistenciasModal() {
            return {
                abierto: false,
                cargando: false,
                contenido: '',
                empleadoId: null,
                desde: '',
                hasta: '',

                formatoFecha(fecha) {
                    const anio = fecha.getFullYear();
                    const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                    const dia = String(fecha.getDate()).padStart(2, '0');
                    return `${anio}-${mes}-${dia}`;
                },

                abrir(empleadoId) {
                    const hoy = new Date();

                    this.empleadoId = empleadoId;
                    this.desde = this.formatoFecha(new Date(hoy.getFullYear(), hoy.getMonth(), 1));
                    this.hasta = this.formatoFecha(new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0));
                    this.abierto = true;

                    this.cargar();
                },

                async cargar() {

                    if (!this.empleadoId || !this.desde || !this.hasta) {
                        return;
                    }

                    this.cargando = true;

                    try {

                        const parametros = new URLSearchParams({
                            desde: this.desde,
                            hasta: this.hasta,
                        });

                        const respuesta = await fetch(`/empleados/${this.empleadoId}/resumen-asistencias?${parametros}`, {
                            headers: { 'Accept': 'text/html' },
                        });

                        if (!respuesta.ok) {
                            throw new Error('Respuesta no válida');
                        }

                        this.contenido = await respuesta.text();

                    } catch (error) {

                        this.contenido = '<p class="p-10 text-center font-body-sm text-body-sm text-error">No se pudo cargar el resumen de asistencias. Intenta de nuevo.</p>';

                    } finally {
                        this.cargando = false;
                    }
                },

                cerrar() {
                    this.abierto = false;
                },
            };
        }
    </script>

</x-app-layout>

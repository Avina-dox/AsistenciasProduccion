<x-app-layout>

    <div class="p-6">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">

            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Empleados</h1>
                <p class="mt-1 text-sm text-slate-500">Gestiona empleados y turnos desde un diseño moderno.</p>
            </div>

            <a href="{{ route('empleados.create') }}"
                class="inline-flex items-center justify-center rounded-full bg-sky-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-sky-700">
                Nuevo empleado
            </a>

        </div>

        <div class="overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-slate-200">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Código</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nombre</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Departamento</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Turno</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Horario</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Estatus</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($empleados as $empleado)
                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4 text-sm text-slate-700">{{ $empleado->codigo_empleado }}</td>

                            <td class="px-5 py-4 text-sm text-slate-700">
                                <div class="font-medium text-slate-900">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }}</div>
                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">{{ $empleado->departamento->nombre ?? 'Sin departamento' }}</td>

                            <td class="px-5 py-4 text-sm text-slate-700">{{ $empleado->turno->nombre ?? 'Sin turno' }}</td>

                            <td class="px-5 py-4 text-sm text-slate-700">{{ $empleado->turno->hora_entrada ?? 'Sin horario' }} - {{ $empleado->turno->hora_salida ?? 'Sin horario' }}</td>

                            <td class="px-5 py-4 text-sm text-slate-700">

                                <form action="{{ route('empleados.update', $empleado) }}" method="POST">

                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="codigo_empleado" value="{{ $empleado->codigo_empleado }}">
                                    <input type="hidden" name="nombre" value="{{ $empleado->nombre }}">
                                    <input type="hidden" name="apellido_paterno" value="{{ $empleado->apellido_paterno }}">
                                    <input type="hidden" name="departamento_id" value="{{ $empleado->departamento_id }}">
                                    <input type="hidden" name="turno_id" value="{{ $empleado->turno_id }}">

                                    <select
                                        name="estatus"
                                        onchange="this.form.submit()"
                                        class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold">

                                        <option value="ACTIVO"
                                            {{ $empleado->estatus == 'ACTIVO' ? 'selected' : '' }}>
                                            ACTIVO
                                        </option>

                                        <option value="INACTIVO"
                                            {{ $empleado->estatus == 'INACTIVO' ? 'selected' : '' }}>
                                            INACTIVO
                                        </option>

                                        <option value="VACACIONES"
                                            {{ $empleado->estatus == 'VACACIONES' ? 'selected' : '' }}>
                                            VACACIONES
                                        </option>

                                        <option value="BAJA"
                                            {{ $empleado->estatus == 'BAJA' ? 'selected' : '' }}>
                                            BAJA
                                        </option>

                                    </select>

                                </form>

                            </td>

                            <td class="px-5 py-4 text-sm text-slate-700">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('empleados.edit', $empleado) }}" class="inline-flex items-center rounded-full bg-amber-400 px-4 py-2 text-sm font-medium text-slate-900 transition hover:bg-amber-500">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('empleados.destroy', $empleado) }}" onsubmit="return confirm('¿Eliminar este empleado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-full bg-rose-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-rose-600">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>

        <div class="mt-6">
            {{ $empleados->links() }}
        </div>

    </div>

</x-app-layout>
<x-app-layout>

    <div class="p-6">

        <div class="flex justify-between mb-4">

            <h1 class="text-2xl font-bold">
                Empleados
            </h1>

            <a href="{{ route('empleados.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded">

                Nuevo empleado

            </a>

        </div>

        <div class="bg-white shadow rounded">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-3 text-left">Código</th>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3 text-left">Departamento</th>
                        <th class="p-3 text-left">Estatus</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($empleados as $empleado)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $empleado->codigo_empleado }}
                        </td>

                        <td class="p-3">

                            {{ $empleado->nombre }}
                            {{ $empleado->apellido_paterno }}

                        </td>

                        <td class="p-3">

                            {{ $empleado->departamento->nombre ?? 'Sin departamento' }}

                        </td>

                        <td class="p-3">

                            {{ $empleado->estatus }}

                        </td>

                        <td class="p-3 flex gap-2">

                            <a href="{{ route('empleados.edit', $empleado) }}"
                               class="bg-yellow-400 px-3 py-1 rounded">

                                Editar

                            </a>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-4">

            {{ $empleados->links() }}

        </div>

    </div>

</x-app-layout>
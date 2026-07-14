@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8">

    <div class="flex justify-between items-center mb-6">

        <h1 class="text-2xl font-bold">

            Solicitudes de Horas Extra

        </h1>

        <a
            href="{{ route('hora-extras.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

            Nueva Solicitud

        </a>

    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-slate-100">

                <tr>

                    <th class="p-3">Folio</th>

                    <th class="p-3">Fecha</th>

                    <th class="p-3">Departamento</th>

                    <th class="p-3">Supervisor</th>

                    <th class="p-3">Estado</th>

                    <th class="p-3 text-center">

                        Acciones

                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($solicitudes as $solicitud)

                    <tr class="border-t">

                        <td class="p-3">

                            {{ $solicitud->folio }}

                        </td>

                        <td class="p-3">

                            {{ $solicitud->fecha }}

                        </td>

                        <td class="p-3">

                            {{ $solicitud->departamento->nombre }}

                        </td>

                        <td class="p-3">

                            {{ $solicitud->supervisor->name }}

                        </td>

                        <td class="p-3">

                            {{ $solicitud->estatus->nombre }}

                        </td>

                        <td class="p-3 text-center">

                            <a
                                href="{{ route('hora-extras.show',$solicitud) }}"
                                class="text-blue-600">

                                Ver

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center p-6">

                            No existen solicitudes.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-4">

        {{ $solicitudes->links() }}

    </div>

</div>

@endsection
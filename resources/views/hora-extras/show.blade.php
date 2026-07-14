@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8">

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 p-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg bg-red-100 border border-red-300 text-red-700 p-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">

        <div>

            <h1 class="text-3xl font-bold">

                {{ $horaExtra->folio }}

            </h1>

            <p class="text-gray-500">

                Solicitud de Horas Extra

            </p>

        </div>

        <a href="{{ route('hora-extras.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">

            Regresar

        </a>

    </div>

    <div class="bg-white rounded-xl shadow">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-6 border-b">

            <div>

                <p class="text-gray-500 text-sm">Departamento</p>

                <p class="font-semibold">

                    {{ $horaExtra->departamento->nombre }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Supervisor</p>

                <p class="font-semibold">

                    {{ $horaExtra->supervisor->name }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Fecha</p>

                <p class="font-semibold">

                    {{ \Carbon\Carbon::parse($horaExtra->fecha)->format('d/m/Y') }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Estado</p>

                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">

                    {{ $horaExtra->estatus->nombre }}

                </span>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Hora Inicio</p>

                <p class="font-semibold">

                    {{ $horaExtra->hora_inicio }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Hora Fin</p>

                <p class="font-semibold">

                    {{ $horaExtra->hora_fin }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Tipo</p>

                <p class="font-semibold">

                    {{ $horaExtra->tipo }}

                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">Registró</p>

                <p class="font-semibold">

                    {{ $horaExtra->supervisor->name }}

                </p>

            </div>

        </div>

        <div class="p-6 border-b">

            <h2 class="font-bold mb-2">

                Motivo

            </h2>

            <p>

                {{ $horaExtra->motivo }}

            </p>

        </div>

        <div class="p-6">

            <h2 class="font-bold text-xl mb-4">

                Empleados

            </h2>

            <table class="min-w-full border">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="p-3 text-left">

                            Empleado

                        </th>

                        <th class="p-3">

                            Horas

                        </th>

                        <th class="p-3">

                            Observaciones

                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($horaExtra->detalles as $detalle)

                        <tr class="border-t">

                            <td class="p-3">

                                {{ $detalle->empleado->nombre }}

                                {{ $detalle->empleado->apellido_paterno }}

                            </td>

                            <td class="text-center">

                                {{ $detalle->horas }}

                            </td>

                            <td>

                                {{ $detalle->observaciones }}

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        @if($horaExtra->estatus->nombre == 'PENDIENTE')

            @can('aprobar horas extra')

            <div class="border-t p-6">

                <form
                    action="{{ route('hora-extras.aprobar',$horaExtra) }}"
                    method="POST">

                    @csrf

                    @method('PATCH')

                    <button
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">

                        Aprobar Solicitud

                    </button>

                </form>

            </div>

            @endcan

            @can('rechazar horas extra')

            <div class="border-t p-6">

                <form
                    action="{{ route('hora-extras.rechazar',$horaExtra) }}"
                    method="POST">

                    @csrf

                    @method('PATCH')

                    <textarea
                        name="observaciones_coordinacion"
                        rows="4"
                        class="w-full border rounded-lg p-3"
                        placeholder="Motivo del rechazo..."></textarea>

                    <button
                        class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg">

                        Rechazar Solicitud

                    </button>

                </form>

            </div>

            @endcan

        @endif

    </div>

</div>

@endsection
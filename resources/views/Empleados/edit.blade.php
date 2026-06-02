<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">

            Editar empleado

        </h1>

        <form action="{{ route('empleados.update', $empleado) }}"
              method="POST">

            @csrf
            @method('PUT')

            @include('empleados.form')

            <button
                class="bg-blue-500 text-white px-4 py-2 rounded mt-4">

                Actualizar

            </button>

        </form>

    </div>

</x-app-layout>
<x-app-layout>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-4">
            Nuevo empleado
        </h1>

        <form action="{{ route('empleados.store') }}"
              method="POST">

            @csrf

            @include('empleados.form')

            <button class="bg-green-500 text-white px-4 py-2 rounded mt-4">

                Guardar

            </button>

        </form>

    </div>

</x-app-layout>
<x-app-layout>

    <div class="p-6 bg-gray-100 min-h-screen">

        <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-3xl overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-8 text-white">
                <h1 class="text-3xl font-semibold">Editar empleado</h1>
                <p class="mt-2 text-sm text-cyan-100">Actualiza la información del empleado y guarda los cambios.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('empleados.update', $empleado) }}" method="POST">

                    @csrf
                    @method('PATCH')

                    @include('empleados.form')

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition duration-200">
                            Actualizar
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>

</x-app-layout>
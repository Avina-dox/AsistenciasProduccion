<x-app-layout>

    <div class="min-h-screen bg-gray-100 py-10">
        <div class="mx-auto max-w-3xl rounded-3xl bg-white shadow-xl ring-1 ring-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-slate-800 to-indigo-600 px-8 py-8 text-white">
                <h1 class="text-3xl font-semibold">Nuevo empleado</h1>
                <p class="mt-2 text-slate-200">Registra los datos del empleado para mantener tu equipo organizado.</p>
            </div>

            <div class="px-8 py-8">
                <form action="{{ route('empleados.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @include('empleados.form')

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
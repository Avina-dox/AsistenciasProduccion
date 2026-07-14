<x-app-layout>
<div class="p-6">

    <h1 class="text-2xl font-century mb-4">
        Eliminar empleado
    </h1>
    <form action="{{ route('empleados.destroy', $empleado) }}"
            method="POST"
            class="mt-4">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-500 text-white px-4 py-2 rounded">
            Eliminar
        </button>
    </form>

</div>
</x-app-layout>
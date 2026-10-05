<x-app-layout>

    <div class="relative bg-background min-h-screen">
        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-1.5 font-label-sm text-on-surface-variant hover:text-primary mb-2 transition-colors duration-200">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Panel de Administración
                    </a>
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Configuración</p>
                    <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary-container">schedule</span>
                        Turnos
                    </h1>
                    <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Gestiona los turnos de trabajo (Matutino, Nocturno, etc.).</p>
                </div>

                <a href="{{ route('turnos.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span> Nuevo Turno
                </a>
            </div>

            @if(session('success'))
                <div class="rounded-2xl border border-outline-variant bg-tertiary-container/20 px-4 py-3 font-body-sm text-body-sm text-on-tertiary-container">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl border border-outline-variant bg-error-container/30 px-4 py-3 font-body-sm text-body-sm text-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- TABLE CARD --}}
            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.04]">

                        <thead>
                            <tr class="bg-surface-container-high/40">
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Nombre</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Empleados</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Horarios</th>
                                <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-black/[0.04]">

                            @forelse($turnos as $turno)
                                <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">

                                    <td class="px-5 py-4 font-label-lg text-label-lg font-semibold text-on-surface">
                                        {{ $turno->nombre }}
                                    </td>

                                    <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant">
                                        {{ $turno->empleados_count }}
                                    </td>

                                    <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant">
                                        {{ $turno->horarios_count }}
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-2">

                                            <a href="{{ route('turnos.edit', $turno) }}"
                                                class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-4 py-2 font-label-md text-label-md font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                                                <span class="material-symbols-outlined text-[16px]">edit</span> Editar
                                            </a>

                                            <form method="POST" action="{{ route('turnos.destroy', $turno) }}"
                                                onsubmit="return confirm('¿Eliminar este turno? Se eliminarán también sus horarios asociados.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-error hover:text-on-error px-4 py-2 font-label-md text-label-md font-semibold text-error shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span> Eliminar
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center p-12">
                                        <div class="flex flex-col items-center gap-2 text-on-surface-variant">
                                            <span class="material-symbols-outlined text-[36px] text-outline">folder_off</span>
                                            <p class="font-body-md text-body-md font-medium">No hay turnos registrados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>

            <div>
                {{ $turnos->links() }}
            </div>

        </div>

    </div>

</x-app-layout>

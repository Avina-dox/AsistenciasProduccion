<x-app-layout>

    <div class="relative bg-background min-h-screen">
        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative w-full max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- BACK --}}
            <div>
                <a href="{{ route('empleados.index') }}"
                    class="inline-flex items-center gap-2 font-label-md text-label-md font-semibold text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Volver a Empleados
                </a>
            </div>

            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">

                {{-- HEADER --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-primary to-primary-container px-8 py-8 text-on-primary">

                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur border border-white/20 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[26px]">person_add</span>
                        </div>
                        <div>
                            <p class="font-label-caps text-label-caps uppercase tracking-wider text-white/70 font-semibold mb-1">
                                Gestión de Personal
                            </p>
                            <h1 class="font-headline-lg text-headline-lg font-bold tracking-tight">Nuevo Empleado</h1>
                        </div>
                    </div>

                    <p class="relative z-10 mt-4 font-body-md text-body-md text-white/85">
                        Registra los datos del empleado para mantener tu equipo organizado.
                    </p>
                </div>

                <div class="p-6 sm:p-8">
                    <form action="{{ route('empleados.store') }}" method="POST" class="space-y-6">
                        @csrf

                        @include('empleados.form')

                        <div class="pt-6 border-t border-black/[0.06] dark:border-white/10 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-7 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[18px]">save</span> Guardar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

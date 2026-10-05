<x-app-layout>

    <div class="relative bg-background min-h-screen">
        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative w-full max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-6 sm:p-8 text-center space-y-4">

                <div class="mx-auto w-14 h-14 rounded-2xl bg-error-container/40 flex items-center justify-center">
                    <span class="material-symbols-outlined text-error text-[28px]">delete</span>
                </div>

                <h1 class="font-headline-md text-headline-md text-primary font-bold tracking-tight">
                    Eliminar empleado
                </h1>

                <p class="font-body-md text-body-md text-on-surface-variant">
                    Esta acción no se puede deshacer. ¿Deseas continuar?
                </p>

                <form action="{{ route('empleados.destroy', $empleado) }}"
                        method="POST"
                        class="pt-2 flex items-center justify-center gap-3">
                    @csrf
                    @method('DELETE')

                    <a href="{{ route('empleados.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-5 py-2.5 font-label-lg text-label-lg font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-full bg-error-container/50 hover:bg-error hover:text-on-error px-5 py-2.5 font-label-lg text-label-lg font-semibold text-error transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">delete</span> Eliminar
                    </button>
                </form>

            </div>

        </div>
    </div>

</x-app-layout>

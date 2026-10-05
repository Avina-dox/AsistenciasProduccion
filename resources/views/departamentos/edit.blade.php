<x-app-layout>

    <div class="relative bg-background min-h-screen">
        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative mx-auto max-w-xl px-4 sm:px-6 py-10 space-y-6">

            <a href="{{ route('departamentos.index') }}"
                class="inline-flex items-center gap-2 font-label-md text-label-md font-semibold text-on-surface-variant hover:text-primary transition-colors duration-200">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Volver a Departamentos
            </a>

            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-6 sm:p-8">

                <h1 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-primary-container">domain</span>
                    Editar Departamento
                </h1>

                <form action="{{ route('departamentos.update', $departamento) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @include('departamentos.form')

                    <div class="pt-6 border-t border-outline-variant flex justify-end gap-3">
                        <a href="{{ route('departamentos.index') }}"
                            class="inline-flex items-center justify-center rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-6 py-3 font-label-lg text-label-lg font-semibold text-on-surface-variant shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-7 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span> Actualizar Departamento
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-app-layout>

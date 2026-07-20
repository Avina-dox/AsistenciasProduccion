<x-app-layout>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

        .font-century {
            font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
                         Questrial, 'Avenir Next', sans-serif;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="font-century min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3] py-10">
        <div class="mx-auto max-w-3xl px-6">

            {{-- BACK --}}
            <div class="mb-6 opacity-0" style="animation: fadeUp .5s ease forwards;">
                <a href="{{ route('empleados.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-[#6E6274] hover:text-[#6A2C75] transition-colors duration-200">
                    <span aria-hidden="true">←</span> Volver a Empleados
                </a>
            </div>

            <div class="relative overflow-hidden rounded-2xl bg-white shadow-sm border border-[#2B2030]/10 opacity-0" style="animation: fadeUp .6s .1s ease forwards;">

                {{-- gold hairline --}}
                <div class="h-[3px] bg-gradient-to-r from-[#B6A644] via-[#6A2C75] to-[#B6A644]"></div>

                {{-- HEADER --}}
                <div class="relative overflow-hidden bg-gradient-to-br from-[#6A2C75] via-[#5A2465] to-[#45193F] px-8 py-8 text-white">

                    <div class="absolute right-0 top-0 opacity-[0.08]">
                        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 200 200">
                            <path class="text-[#E4D9A0]" d="M46,-73.5C59.2,-66.7,69.5,-53.8,76.5,-39.4C83.5,-25,87.3,-9.2,84.7,5.8C82.1,20.8,73.2,35.1,62.3,47.3C51.5,59.5,38.7,69.5,24.2,75.3C9.8,81,-6.3,82.5,-21.4,78.6C-36.5,74.7,-50.5,65.5,-60.6,53.4C-70.8,41.2,-77,26.1,-79.1,10.3C-81.3,-5.5,-79.4,-21.9,-72.7,-35.7C-66,-49.5,-54.5,-60.8,-41.1,-67.6C-27.8,-74.4,-13.9,-76.7,1.3,-78.8C16.4,-80.8,32.8,-82.4,46,-73.5Z"/>
                        </svg>
                    </div>

                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur border border-[#E4D9A0]/30 flex items-center justify-center text-2xl shrink-0">
                            👥
                        </div>
                        <div>
                            <p class="uppercase tracking-[0.25em] text-[#D9BFE0] text-xs font-semibold mb-1">
                                Gestión de Personal
                            </p>
                            <h1 class="text-3xl font-bold">Nuevo Empleado</h1>
                        </div>
                    </div>

                    <p class="relative z-10 mt-4 text-[#E9DCEC]">
                        Registra los datos del empleado para mantener tu equipo organizado.
                    </p>
                </div>

                <div class="px-8 py-8">
                    <form action="{{ route('empleados.store') }}" method="POST" class="space-y-6">
                        @csrf

                        @include('empleados.form')

                        <div class="pt-6 border-t border-[#2B2030]/10 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#6A2C75] to-[#45193F] px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-[#45193F]/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#6A2C75]/40 focus:ring-offset-2">
                                Guardar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
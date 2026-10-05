<x-app-layout>

    <div class="relative bg-background min-h-screen">
        <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

        <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- HEADER --}}
            <div>
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Configuración</p>
                <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">settings</span>
                    Panel de Administración
                </h1>
                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Gestiona usuarios, turnos, departamentos y horarios del sistema.</p>
            </div>

            {{-- TARJETAS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                @php
                    $tarjetas = [
                        [
                            'titulo' => 'Usuarios',
                            'descripcion' => 'Cuentas de acceso y roles',
                            'total' => $totalUsuarios,
                            'ruta' => route('usuarios.index'),
                            'icono' => 'badge',
                        ],
                        [
                            'titulo' => 'Turnos',
                            'descripcion' => 'Matutino, nocturno, etc.',
                            'total' => $totalTurnos,
                            'ruta' => route('turnos.index'),
                            'icono' => 'schedule',
                        ],
                        [
                            'titulo' => 'Departamentos',
                            'descripcion' => 'Áreas de la planta',
                            'total' => $totalDepartamentos,
                            'ruta' => route('departamentos.index'),
                            'icono' => 'domain',
                        ],
                        [
                            'titulo' => 'Horarios',
                            'descripcion' => 'Entrada y salida por área/turno',
                            'total' => $totalHorarios,
                            'ruta' => route('horarios.index'),
                            'icono' => 'calendar_month',
                        ],
                    ];
                @endphp

                @foreach($tarjetas as $tarjeta)
                    <a href="{{ $tarjeta['ruta'] }}"
                        class="group relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(74,30,82,0.12),inset_0_1px_2px_rgba(255,255,255,0.95)]">

                        <div class="flex items-center justify-between">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-container/20 text-primary transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110">
                                <span class="material-symbols-outlined text-[26px]">{{ $tarjeta['icono'] }}</span>
                            </div>

                            <span class="font-headline-md text-headline-md font-bold tabular-nums text-primary">
                                {{ $tarjeta['total'] }}
                            </span>
                        </div>

                        <h2 class="mt-4 font-label-lg text-label-lg font-bold text-on-surface">
                            {{ $tarjeta['titulo'] }}
                        </h2>

                        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
                            {{ $tarjeta['descripcion'] }}
                        </p>

                    </a>
                @endforeach

            </div>

        </div>

    </div>

</x-app-layout>

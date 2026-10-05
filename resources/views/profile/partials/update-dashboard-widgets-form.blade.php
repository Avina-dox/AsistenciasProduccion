<section>
    <header>
        <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-container">dashboard</span>
            {{ __('Panel del Dashboard') }}
        </h2>

        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
            {{ __('Elige qué secciones quieres ver en tu dashboard. Tu elección se guarda en tu cuenta.') }}
        </p>
    </header>

    @php
        $widgetsDisponibles = [
            'kpis_principales' => [
                'titulo' => 'KPIs principales',
                'descripcion' => 'Plantilla activa, presentes hoy, faltas y horas extra pendientes.',
            ],
            'indicadores_operativos' => [
                'titulo' => 'Indicadores Operativos',
                'descripcion' => 'Retardos, horas extra autorizadas, cobertura matutina y nocturna.',
            ],
            'cobertura_area' => [
                'titulo' => 'Cobertura por Área',
                'descripcion' => 'Tabla de personal activo frente a la plantilla autorizada, por departamento y turno.',
            ],
            'distribucion_asistencia' => [
                'titulo' => 'Distribución de Asistencia',
                'descripcion' => 'Desglose de presentes, faltas, horas extra, permisos y vacaciones del día.',
            ],
        ];

        $widgetsActivos = auth()->user()->widgetsDashboardVisibles();
    @endphp

    <form method="post" action="{{ route('profile.dashboard-widgets.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div class="space-y-3">
            @foreach($widgetsDisponibles as $clave => $widget)
                <label class="relative flex items-start gap-3 rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/30 p-4 cursor-pointer transition-all has-[:checked]:ring-2 has-[:checked]:ring-primary has-[:checked]:border-transparent has-[:checked]:bg-primary-container/10 hover:bg-white dark:hover:bg-surface-container-high/50">
                    <input
                        type="checkbox"
                        name="widgets[]"
                        value="{{ $clave }}"
                        {{ in_array($clave, $widgetsActivos) ? 'checked' : '' }}
                        class="sr-only peer">

                    <span class="material-symbols-outlined mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full border-2 border-outline-variant text-[14px] text-transparent peer-checked:border-primary peer-checked:bg-primary peer-checked:text-on-primary transition-colors">check</span>

                    <span>
                        <span class="block font-label-lg text-label-lg font-semibold text-on-surface">{{ $widget['titulo'] }}</span>
                        <span class="block font-body-sm text-body-sm text-on-surface-variant">{{ $widget['descripcion'] }}</span>
                    </span>
                </label>
            @endforeach
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar preferencias') }}</x-primary-button>

            @if (session('status') === 'dashboard-widgets-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>

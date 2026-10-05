<div class="p-6 sm:p-8 space-y-6">

    <div class="grid md:grid-cols-2 gap-6">

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Tipo
            </label>

            <select
                name="tipo"
                id="tipo"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                <option value="AREA">
                    Área
                </option>

                <option value="EMPLEADO">
                    Empleado
                </option>

            </select>
        </div>

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Departamento
            </label>

            <select
                name="departamento_id"
                id="departamento"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                @foreach($departamentos as $departamento)
                    <option value="{{ $departamento->id }}">
                        {{ $departamento->nombre }}
                    </option>
                @endforeach

            </select>
        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Fecha
            </label>

            <input
                type="date"
                name="fecha"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Motivo
            </label>

            <textarea
                name="motivo"
                rows="3"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all"></textarea>
        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Hora inicio
            </label>

            <input
                type="time"
                name="hora_inicio"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

        <div>
            <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                Hora fin
            </label>

            <input
                type="time"
                name="hora_fin"
                class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
        </div>

    </div>

    <div class="border-t border-outline-variant/40 pt-6">

        <h2 class="font-headline-md text-headline-md text-primary font-bold tracking-tight flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-primary-container">group</span>
            Empleados
        </h2>

        <div class="grid md:grid-cols-3 gap-3 max-h-80 overflow-y-auto p-1">

            @foreach($empleados as $empleado)

                <label
                    class="flex items-center gap-2 rounded-xl border border-black/10 dark:border-white/10 bg-white/60 dark:bg-surface-container-high/30 p-3 hover:bg-white dark:hover:bg-surface-container-high/50 cursor-pointer transition-colors">

                    <input
                        type="checkbox"
                        name="empleados[]"
                        value="{{ $empleado->id }}"
                        class="rounded border-black/20 dark:border-white/20 text-primary focus:ring-primary/30">

                    <span class="font-body-sm text-body-sm text-on-surface">
                        {{ $empleado->apellido_paterno }}
                        {{ $empleado->apellido_materno }}
                        {{ $empleado->nombre }}
                    </span>

                </label>

            @endforeach

        </div>

    </div>

    <div class="flex justify-end">

        <button
            class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
            <span class="material-symbols-outlined text-[18px]">save</span> Guardar Solicitud
        </button>

    </div>

</div>

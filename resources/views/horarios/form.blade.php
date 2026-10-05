<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Turno</label>

        <select name="turno_id"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer @error('turno_id') border-error focus:border-error focus:ring-error/20 @enderror">
            <option value="">Seleccionar</option>

            @foreach($turnos as $turno)
                <option value="{{ $turno->id }}"
                    @selected(old('turno_id', $horario->turno_id ?? '') == $turno->id)>
                    {{ $turno->nombre }}
                </option>
            @endforeach
        </select>

        @error('turno_id')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Departamento</label>

        <select name="departamento_id"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer @error('departamento_id') border-error focus:border-error focus:ring-error/20 @enderror">
            <option value="">Seleccionar</option>

            @foreach($departamentos as $departamento)
                <option value="{{ $departamento->id }}"
                    @selected(old('departamento_id', $horario->departamento_id ?? '') == $departamento->id)>
                    {{ $departamento->nombre }}
                </option>
            @endforeach
        </select>

        @error('departamento_id')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Hora de entrada</label>

        <input type="time"
            name="hora_entrada"
            value="{{ old('hora_entrada', isset($horario) ? \Carbon\Carbon::parse($horario->hora_entrada)->format('H:i') : '') }}"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('hora_entrada') border-error focus:border-error focus:ring-error/20 @enderror">

        @error('hora_entrada')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Hora de salida</label>

        <input type="time"
            name="hora_salida"
            value="{{ old('hora_salida', isset($horario) ? \Carbon\Carbon::parse($horario->hora_salida)->format('H:i') : '') }}"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('hora_salida') border-error focus:border-error focus:ring-error/20 @enderror">

        @error('hora_salida')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Plantilla autorizada</label>

        <input type="number"
            name="plantilla_autorizada"
            min="0"
            max="9999"
            step="1"
            placeholder="Ej. 14"
            value="{{ old('plantilla_autorizada', $horario->plantilla_autorizada ?? '') }}"
            class="w-full md:w-1/2 rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('plantilla_autorizada') border-error focus:border-error focus:ring-error/20 @enderror">

        <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">
            Personal objetivo para este turno y departamento. Se usa en "Cobertura por Área" del dashboard.
        </p>

        @error('plantilla_autorizada')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

</div>

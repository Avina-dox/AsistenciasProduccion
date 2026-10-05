<div>
    <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Nombre del turno</label>

    <input type="text"
        name="nombre"
        value="{{ old('nombre', $turno->nombre ?? '') }}"
        placeholder="Ej. MATUTINO"
        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('nombre') border-error focus:border-error focus:ring-error/20 @enderror">

    @error('nombre')
        <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
    @enderror
</div>

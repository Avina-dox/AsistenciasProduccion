<div class="space-y-6">

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Nombre</label>

        <input type="text"
            name="nombre"
            value="{{ old('nombre', $departamento->nombre ?? '') }}"
            placeholder="Ej. Empaque Granola"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('nombre') border-error focus:border-error focus:ring-error/20 @enderror">

        @error('nombre')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Descripción</label>

        <textarea
            name="descripcion"
            rows="3"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all @error('descripcion') border-error focus:border-error focus:ring-error/20 @enderror">{{ old('descripcion', $departamento->descripcion ?? '') }}</textarea>

        @error('descripcion')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <label class="flex items-center gap-2.5 font-body-md text-body-md font-medium text-on-surface">
        <input type="checkbox"
            name="activo"
            value="1"
            {{ old('activo', $departamento->activo ?? true) ? 'checked' : '' }}
            class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/30">
        Departamento activo
    </label>

</div>

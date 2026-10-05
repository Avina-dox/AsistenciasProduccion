<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Código</label>

        <input type="text"
            name="codigo_empleado"
            value="{{ old('codigo_empleado', $empleado->codigo_empleado ?? '') }}"
            class="w-full rounded-2xl border {{ $errors->has('codigo_empleado') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

        @error('codigo_empleado')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Nombre</label>

        <input type="text"
            name="nombre"
            value="{{ old('nombre', $empleado->nombre ?? '') }}"
            class="w-full rounded-2xl border {{ $errors->has('nombre') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

        @error('nombre')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Apellido</label>

        <input type="text"
            name="apellido_paterno"
            value="{{ old('apellido_paterno', $empleado->apellido_paterno ?? '') }}"
            class="w-full rounded-2xl border {{ $errors->has('apellido_paterno') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

        @error('apellido_paterno')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Departamento</label>

        <select name="departamento_id"
            class="w-full rounded-2xl border {{ $errors->has('departamento_id') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

            <option value="">Seleccionar</option>

            @foreach($departamentos as $departamento)

            <option value="{{ $departamento->id }}"
                @selected(old('departamento_id', $empleado->departamento_id ?? '') == $departamento->id)>

                {{ $departamento->nombre }}

            </option>

            @endforeach

        </select>

        @error('departamento_id')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Turno</label>

        <select name="turno_id"
            class="w-full rounded-2xl border {{ $errors->has('turno_id') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

            <option value="">Seleccionar</option>

            @foreach($turnos as $turno)

            <option value="{{ $turno->id }}"
                @selected(old('turno_id', $empleado->turno_id ?? '') == $turno->id)>

                {{ $turno->nombre }}

            </option>

            @endforeach

        </select>

        @error('turno_id')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Cuenta de usuario</label>

        <select name="user_id"
            class="w-full rounded-2xl border {{ $errors->has('user_id') ? 'border-error ring-2 ring-error/10' : 'border-black/10 dark:border-white/10' }} bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

            <option value="">Sin cuenta vinculada</option>

            @foreach($usuarios as $usuario)

            <option value="{{ $usuario->id }}"
                @selected(old('user_id', $empleado->user_id ?? '') == $usuario->id)>

                {{ $usuario->name }}{{ $usuario->getRoleNames()->isNotEmpty() ? ' (' . $usuario->getRoleNames()->implode(', ') . ')' : '' }}

            </option>

            @endforeach

        </select>

        <p class="mt-1.5 font-body-sm text-body-sm text-outline">
            Vincula este empleado con su cuenta de acceso (necesario para detectar, por ejemplo, si es Supervisor).
        </p>

        @error('user_id')
            <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">Estado</label>

        <select
            name="estatus"
            class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

            <option value="ACTIVO"
                @selected(old('estatus', $empleado->estatus ?? 'ACTIVO') == 'ACTIVO')>
                ACTIVO
            </option>

            <option value="INACTIVO"
                @selected(old('estatus', $empleado->estatus ?? 'ACTIVO') == 'INACTIVO')>
                INACTIVO
            </option>

            <option value="VACACIONES"
                @selected(old('estatus', $empleado->estatus ?? 'ACTIVO') == 'VACACIONES')>
                VACACIONES
            </option>

            @hasanyrole('RH|Admin|Coordinacion')
            <option value="BAJA"
                @selected(old('estatus', $empleado->estatus ?? 'ACTIVO') == 'BAJA')>
                BAJA
            </option>
            @endhasanyrole

        </select>
    </div>

</div>

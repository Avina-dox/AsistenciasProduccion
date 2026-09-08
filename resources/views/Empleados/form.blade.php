<style>
    @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

    .font-century {
        font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
                     Questrial, 'Avenir Next', sans-serif;
    }

    .field-label {
        display: block;
        font-size: .75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #6E6274;
        margin-bottom: .4rem;
    }

    .field-input {
        width: 100%;
        border: 1px solid rgba(43, 32, 48, 0.15);
        border-radius: 0.75rem;
        padding: 0.65rem 0.9rem;
        font-size: 0.9rem;
        color: #2B2030;
        background: #fff;
        transition: all 0.25s ease;
    }
    .field-input:hover { border-color: rgba(106, 44, 117, 0.35); }
    .field-input:focus {
        outline: none;
        border-color: #6A2C75;
        box-shadow: 0 0 0 3px rgba(106, 44, 117, 0.12);
    }
    .field-input.has-error {
        border-color: #DC2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
    }

    .field-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236E6274'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right .85rem center;
        background-size: 1rem;
        padding-right: 2.25rem;
        cursor: pointer;
    }

    .field-error {
        margin-top: .35rem;
        font-size: .75rem;
        color: #DC2626;
        font-weight: 500;
    }
</style>

<div class="font-century grid grid-cols-1 md:grid-cols-2 gap-5">

    <div>
        <label class="field-label">Código</label>

        <input type="text"
            name="codigo_empleado"
            value="{{ old('codigo_empleado', $empleado->codigo_empleado ?? '') }}"
            class="field-input @error('codigo_empleado') has-error @enderror">

        @error('codigo_empleado')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label">Nombre</label>

        <input type="text"
            name="nombre"
            value="{{ old('nombre', $empleado->nombre ?? '') }}"
            class="field-input @error('nombre') has-error @enderror">

        @error('nombre')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label">Apellido</label>

        <input type="text"
            name="apellido_paterno"
            value="{{ old('apellido_paterno', $empleado->apellido_paterno ?? '') }}"
            class="field-input @error('apellido_paterno') has-error @enderror">

        @error('apellido_paterno')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label">Departamento</label>

        <select name="departamento_id"
            class="field-input field-select @error('departamento_id') has-error @enderror">

            <option value="">Seleccionar</option>

            @foreach($departamentos as $departamento)

            <option value="{{ $departamento->id }}"
                @selected(old('departamento_id', $empleado->departamento_id ?? '') == $departamento->id)>

                {{ $departamento->nombre }}

            </option>

            @endforeach

        </select>

        @error('departamento_id')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label">Turno</label>

        <select name="turno_id"
            class="field-input field-select @error('turno_id') has-error @enderror">

            <option value="">Seleccionar</option>

            @foreach($turnos as $turno)

            <option value="{{ $turno->id }}"
                @selected(old('turno_id', $empleado->turno_id ?? '') == $turno->id)>

                {{ $turno->nombre }}

            </option>

            @endforeach

        </select>

        @error('turno_id')
            <p class="field-error">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label">Estado</label>

        <select
            name="estatus"
            class="field-input field-select">

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
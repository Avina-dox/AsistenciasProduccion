<div class="grid grid-cols-2 gap-4">

    <div>
        <label>Código</label>

        <input type="text"
               name="codigo_empleado"
               value="{{ old('codigo_empleado', $empleado->codigo_empleado ?? '') }}"
               class="w-full border rounded">
    </div>

    <div>
        <label>Nombre</label>

        <input type="text"
               name="nombre"
               value="{{ old('nombre', $empleado->nombre ?? '') }}"
               class="w-full border rounded">
    </div>

    <div>
        <label>Apellido paterno</label>

        <input type="text"
               name="apellido_paterno"
               value="{{ old('apellido_paterno', $empleado->apellido_paterno ?? '') }}"
               class="w-full border rounded">
    </div>

    <div>
        <label>Departamento</label>

        <select name="departamento_id"
                class="w-full border rounded">

            <option value="">
                Seleccionar
            </option>

            @foreach($departamentos as $departamento)

            <option value="{{ $departamento->id }}">

                {{ $departamento->nombre }}

            </option>

            @endforeach

        </select>

    </div>
    <div>
        <label>Turno</label>

        <select name="turno_id"
                class="w-full border rounded">

            <option value="">
                Seleccionar
            </option>

            @foreach($turnos as $turno)

            <option value="{{ $turno->id }}">

                {{ $turno->nombre }}

            </option>

            @endforeach

        </select>

</div>
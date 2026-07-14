<div class="p-6 space-y-6">

    <div class="grid md:grid-cols-2 gap-6">

        <div>

            <label class="font-semibold">

                Tipo

            </label>

            <select
                name="tipo"
                id="tipo"
                class="w-full border rounded-lg p-2">

                <option value="AREA">

                    Área

                </option>

                <option value="EMPLEADO">

                    Empleado

                </option>

            </select>

        </div>

        <div>

            <label class="font-semibold">

                Departamento

            </label>

            <select
                name="departamento_id"
                id="departamento"
                class="w-full border rounded-lg p-2">

                @foreach($departamentos as $departamento)

                    <option
                        value="{{ $departamento->id }}">

                        {{ $departamento->nombre }}

                    </option>

                @endforeach

            </select>

        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <div>

            <label>

                Fecha

            </label>

            <input
                type="date"
                name="fecha"
                class="w-full border rounded-lg p-2">

        </div>

        <div>

            <label>

                Motivo

            </label>

            <textarea
                name="motivo"
                rows="3"
                class="w-full border rounded-lg p-2"></textarea>

        </div>

    </div>

    <div class="grid md:grid-cols-2 gap-6">

        <div>

            <label>

                Hora inicio

            </label>

            <input
                type="time"
                name="hora_inicio"
                class="w-full border rounded-lg p-2">

        </div>

        <div>

            <label>

                Hora fin

            </label>

            <input
                type="time"
                name="hora_fin"
                class="w-full border rounded-lg p-2">

        </div>

    </div>

    <hr>

    <h2 class="text-lg font-bold">

        Empleados

    </h2>

    <div
        class="grid md:grid-cols-3 gap-3
               max-h-80 overflow-y-auto">

        @foreach($empleados as $empleado)

            <label
                class="flex items-center gap-2 p-2 border rounded hover:bg-gray-100">

                <input
                    type="checkbox"
                    name="empleados[]"
                    value="{{ $empleado->id }}">

                <span>

                    {{ $empleado->nombre }}
                    {{ $empleado->apellido_paterno }}

                </span>

            </label>

        @endforeach

    </div>

    <div class="flex justify-end">

        <button
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

            Guardar Solicitud

        </button>

    </div>

</div>
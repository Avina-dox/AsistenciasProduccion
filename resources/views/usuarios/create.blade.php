<form
    action="{{ route('usuarios.store') }}"
    method="POST">

    @csrf

    <input
        type="text"
        name="name"
        placeholder="Nombre">

    <input
        type="email"
        name="email"
        placeholder="Correo">

    <input
        type="password"
        name="password"
        placeholder="Contraseña">

    <select name="role">

        @foreach($roles as $role)

        <option
            value="{{ $role->name }}">

            {{ $role->name }}

        </option>

        @endforeach

    </select>
    <div class="mb-4">

    <label class="block font-medium">

        Usuario

    </label>

    <select
        name="user_id"
        class="w-full border rounded-lg p-2">

        <option value="">

            Sin usuario

        </option>

        @foreach($usuarios as $usuario)

            <option
                value="{{ $usuario->id }}">

                {{ $usuario->name }}

            </option>

        @endforeach

    </select>

</div>

    <button>
        Guardar
    </button>

</form>
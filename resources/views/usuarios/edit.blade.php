@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow p-6">

        <h1 class="text-2xl font-bold mb-6">
            Editar Usuario
        </h1>

        <form
            action="{{ route('usuarios.update', $usuario) }}"
            method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-2">
                    Nombre
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $usuario->name) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2">
                    Correo
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $usuario->email) }}"
                    class="w-full border rounded-lg p-2">
            </div>

            <div class="mb-4">
                <label class="block mb-2">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg p-2">

                <small class="text-gray-500">
                    Déjalo vacío si no deseas cambiarla.
                </small>
            </div>

            <div class="mb-6">
                <label class="block mb-2">
                    Roles
                </label>

                <div class="space-y-2 border rounded-lg p-3">

                    @foreach($roles as $role)

                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="roles[]"
                                value="{{ $role->name }}"
                                @checked($usuario->hasRole($role->name))
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                            {{ $role->name }}

                        </label>

                    @endforeach

                </div>

                @error('roles')
                    <p class="text-red-600 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg">

                    Actualizar

                </button>

                <a
                    href="{{ route('usuarios.index') }}"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg">

                    Cancelar

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
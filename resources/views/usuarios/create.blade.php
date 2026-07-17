@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">

            <h1 class="text-2xl font-bold">

                Nuevo Usuario

            </h1>

            <p class="text-gray-500 mt-1">

                Registra un nuevo usuario del sistema.

            </p>

        </div>

        <form
            action="{{ route('usuarios.store') }}"
            method="POST"
            class="p-6 space-y-6">

            @csrf

            <div>

                <label class="block text-sm font-medium mb-2">

                    Nombre

                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                @error('name')

                    <p class="text-red-600 text-sm mt-1">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            <div>

                <label class="block text-sm font-medium mb-2">

                    Correo electrónico

                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                @error('email')

                    <p class="text-red-600 text-sm mt-1">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            <div>

                <label class="block text-sm font-medium mb-2">

                    Contraseña

                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                @error('password')

                    <p class="text-red-600 text-sm mt-1">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            <div>

                <label class="block text-sm font-medium mb-2">

                    Rol

                </label>

                <select
                    name="role"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                    required>

                    <option value="">

                        Seleccione un rol

                    </option>

                    @foreach($roles as $role)

                        <option
                            value="{{ $role->name }}"
                            @selected(old('role') == $role->name)>

                            {{ $role->name }}

                        </option>

                    @endforeach

                </select>

                @error('role')

                    <p class="text-red-600 text-sm mt-1">

                        {{ $message }}

                    </p>

                @enderror

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('usuarios.index') }}"
                    class="px-5 py-2 border rounded-lg hover:bg-gray-100">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">

                    Guardar Usuario

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
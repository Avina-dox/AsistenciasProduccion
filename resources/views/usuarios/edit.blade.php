@extends('layouts.app')

@section('content')

<div class="relative bg-background min-h-screen">
    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] p-6 sm:p-8">

            <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Administración</p>
            <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-primary-container">edit</span>
                Editar Usuario
            </h1>

            <form
                action="{{ route('usuarios.update', $usuario) }}"
                method="POST"
                class="space-y-6">

                @csrf
                @method('PUT')

                <div>
                    <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $usuario->name) }}"
                        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                    @error('name')
                        <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                        Correo
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $usuario->email) }}"
                        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                    @error('email')
                        <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-4 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">

                    <small class="block mt-1.5 font-body-sm text-body-sm text-on-surface-variant">
                        Déjalo vacío si no deseas cambiarla.
                    </small>

                    @error('password')
                        <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-label-md text-label-md font-semibold text-on-surface mb-1.5">
                        Roles
                    </label>

                    <div class="space-y-2 rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 px-4 py-3">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-2 font-body-md text-body-md text-on-surface">
                                <input
                                    type="checkbox"
                                    name="roles[]"
                                    value="{{ $role->name }}"
                                    @checked($usuario->hasRole($role->name))
                                    class="rounded border-outline-variant text-primary focus:ring-primary/30">

                                {{ $role->name }}
                            </label>
                        @endforeach
                    </div>

                    @error('roles')
                        <p class="mt-1 font-body-sm text-body-sm text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-5 py-2.5 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Actualizar
                    </button>

                    <a
                        href="{{ route('usuarios.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-5 py-2.5 font-label-lg text-label-lg font-semibold text-on-surface-variant shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection

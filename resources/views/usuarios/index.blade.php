<x-app-layout>

<div class="min-h-screen bg-gray-50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Usuarios</h1>
            <p class="mt-1 text-sm text-gray-500">Administración de usuarios y roles</p>
        </div>
        <a href="{{ route('usuarios.create') }}"
           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
            Nuevo usuario
        </a>
    </div>

    {{-- Alert de éxito --}}
    @if(session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tarjetas de métricas --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total usuarios</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $usuarios->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Activos</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $totalActivos ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Con rol asignado</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $conRol ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 px-5 py-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Sin rol</p>
            <p class="mt-1 text-2xl font-semibold text-amber-600">{{ $sinRol ?? '—' }}</p>
        </div>
    </div>

    {{-- Toolbar: búsqueda + filtro --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <form method="GET" action="{{ route('usuarios.index') }}" class="flex flex-col sm:flex-row gap-3 w-full">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Buscar por nombre o correo…"
                    class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select
                name="rol"
                onchange="this.form.submit()"
                class="text-sm border border-gray-200 rounded-lg bg-white text-gray-700 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Todos los roles</option>
                @foreach($roles ?? [] as $rol)
                    <option value="{{ $rol->name }}" {{ request('rol') === $rol->name ? 'selected' : '' }}>
                        {{ ucfirst($rol->name) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="sm:hidden inline-flex justify-center items-center gap-2 text-sm font-medium text-gray-700 border border-gray-200 bg-white hover:bg-gray-50 px-4 py-2 rounded-lg transition-colors">
                Buscar
            </button>
            @if(request('search') || request('rol'))
                <a href="{{ route('usuarios.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg border border-gray-200 bg-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide w-12">#</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Nombre</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wide">Correo</th>
                    <th class="px-5 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wide">Rol</th>
                    <th class="px-5 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wide">Estado</th>
                    <th class="px-5 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wide">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">

                @forelse($usuarios as $usuario)
                    @php
                        $rolesUsuario = $usuario->getRoleNames();
                        $iniciales = collect(explode(' ', $usuario->name))
                            ->map(fn($p) => strtoupper($p[0] ?? ''))
                            ->take(2)
                            ->implode('');
                        $rolClasses = fn($rol) => match($rol) {
                            'Admin'          => 'bg-purple-100 text-purple-800',
                            'RH'             => 'bg-emerald-100 text-emerald-800',
                            'Supervisor'     => 'bg-amber-100 text-amber-800',
                            'Coordinacion'   => 'bg-blue-100 text-blue-800',
                            default          => 'bg-gray-100 text-gray-500',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-100">

                        {{-- ID --}}
                        <td class="px-5 py-3.5 text-gray-400 text-xs">
                            {{ $usuario->id }}
                        </td>

                        {{-- Nombre con avatar --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 text-xs font-medium flex items-center justify-center flex-shrink-0">
                                    {{ $iniciales }}
                                </span>
                                <span class="font-medium text-gray-900 truncate max-w-[160px]">
                                    {{ $usuario->name }}
                                </span>
                            </div>
                        </td>

                        {{-- Correo --}}
                        <td class="px-5 py-3.5 text-gray-500 truncate max-w-[200px]">
                            {{ $usuario->email }}
                        </td>

                        {{-- Rol --}}
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex flex-wrap items-center justify-center gap-1">
                                @forelse($rolesUsuario as $rol)
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium {{ $rolClasses($rol) }}">
                                        {{ $rol }}
                                    </span>
                                @empty
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Sin rol
                                    </span>
                                @endforelse
                            </div>
                        </td>

                        {{-- Estado --}}
                        <td class="px-5 py-3.5 text-center">
                            @if($usuario->active ?? true)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Activo
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-colors duration-150"
                                   title="Editar usuario">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                </a>
                                <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                      onsubmit="return confirm('¿Estás seguro de que deseas eliminar a {{ addslashes($usuario->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-200 text-gray-400 hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition-colors duration-150"
                                            title="Eliminar usuario">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-10 h-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            <p class="text-sm font-medium text-gray-400">No hay usuarios registrados</p>
                            <p class="text-xs text-gray-300 mt-1">Crea el primero con el botón de arriba</p>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($usuarios->hasPages())
        <div class="mt-4 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} usuarios
            </p>
            <div>
                {{ $usuarios->appends(request()->query())->links() }}
            </div>
        </div>
    @endif

</div>
</div>

</x-app-layout>
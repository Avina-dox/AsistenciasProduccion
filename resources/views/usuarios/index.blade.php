<x-app-layout>

<div class="relative bg-background min-h-screen">
    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Administración</p>
                <h1 class="font-headline-lg text-headline-lg text-primary font-bold tracking-tight flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary-container">manage_accounts</span>
                    Usuarios
                </h1>
                <p class="mt-1 font-body-sm text-body-sm text-on-surface-variant">Administración de usuarios y roles del sistema.</p>
            </div>
            <a href="{{ route('usuarios.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-container px-6 py-3 font-label-lg text-label-lg font-semibold text-white shadow-[0_8px_20px_rgba(74,30,82,0.22),inset_0_1px_1px_rgba(255,255,255,0.4)] hover:brightness-110 active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                Nuevo usuario
            </a>
        </div>

        {{-- Alert de éxito --}}
        @if(session('success'))
            <div class="flex items-center gap-3 rounded-2xl bg-tertiary-container/40 border border-tertiary-container text-on-tertiary-container font-body-sm text-body-sm px-4 py-3">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        {{-- Tarjetas de métricas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] px-5 py-4">
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">Total usuarios</p>
                <p class="mt-1 font-headline-md text-headline-md text-on-surface font-bold">{{ $usuarios->total() }}</p>
            </div>
            <div class="rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] px-5 py-4">
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">Activos</p>
                <p class="mt-1 font-headline-md text-headline-md text-on-surface font-bold">{{ $totalActivos ?? '—' }}</p>
            </div>
            <div class="rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] px-5 py-4">
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">Con rol asignado</p>
                <p class="mt-1 font-headline-md text-headline-md text-on-surface font-bold">{{ $conRol ?? '—' }}</p>
            </div>
            <div class="rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)] px-5 py-4">
                <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold">Sin rol</p>
                <p class="mt-1 font-headline-md text-headline-md text-error font-bold">{{ $sinRol ?? '—' }}</p>
            </div>
        </div>

        {{-- Toolbar: búsqueda + filtro --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('usuarios.index') }}" class="flex flex-col sm:flex-row gap-3 w-full">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-outline pointer-events-none">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar por nombre o correo…"
                        class="w-full pl-9 pr-4 py-2.5 rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface placeholder-outline font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                <select
                    name="rol"
                    onchange="this.form.submit()"
                    class="rounded-2xl border border-black/10 dark:border-white/10 bg-white/70 dark:bg-surface-container-high/40 text-on-surface px-3 py-2.5 font-body-md text-body-md shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                    <option value="">Todos los roles</option>
                    @foreach($roles ?? [] as $rol)
                        <option value="{{ $rol->name }}" {{ request('rol') === $rol->name ? 'selected' : '' }}>
                            {{ ucfirst($rol->name) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="sm:hidden inline-flex justify-center items-center gap-2 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-4 py-2.5 font-label-md text-label-md font-semibold text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                    Buscar
                </button>
                @if(request('search') || request('rol'))
                    <a href="{{ route('usuarios.index') }}"
                       class="inline-flex items-center gap-1.5 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high px-4 py-2.5 font-label-md text-label-md font-semibold text-on-surface-variant shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabla --}}
        <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-black/[0.04]">
                    <thead>
                        <tr class="bg-surface-container-high/40">
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant w-12">#</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Nombre</th>
                            <th class="px-5 py-4 text-left font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Correo</th>
                            <th class="px-5 py-4 text-center font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Rol</th>
                            <th class="px-5 py-4 text-center font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Estado</th>
                            <th class="px-5 py-4 text-center font-label-caps text-label-caps uppercase tracking-wider text-on-surface-variant">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.04]">

                        @forelse($usuarios as $usuario)
                            @php
                                $rolesUsuario = $usuario->getRoleNames();
                                $iniciales = collect(explode(' ', $usuario->name))
                                    ->map(fn($p) => strtoupper($p[0] ?? ''))
                                    ->take(2)
                                    ->implode('');
                                $rolClasses = fn($rol) => match($rol) {
                                    'Admin'          => 'bg-primary-container/15 text-on-primary-container',
                                    'RH'             => 'bg-tertiary-container/40 text-on-tertiary-container',
                                    'Supervisor'     => 'bg-secondary-container/50 text-on-secondary-container',
                                    'Coordinacion'   => 'bg-surface-container-high text-on-surface',
                                    default          => 'bg-surface-container text-outline',
                                };
                            @endphp
                            <tr class="hover:bg-white/60 dark:hover:bg-white/5 transition-colors">

                                {{-- ID --}}
                                <td class="px-5 py-4 font-body-sm text-body-sm text-outline">
                                    {{ $usuario->id }}
                                </td>

                                {{-- Nombre con avatar --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-primary-container/20 text-on-primary-container font-label-md text-label-md font-bold flex items-center justify-center flex-shrink-0">
                                            {{ $iniciales }}
                                        </span>
                                        <span class="font-label-lg text-label-lg font-semibold text-on-surface truncate max-w-[160px]">
                                            {{ $usuario->name }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Correo --}}
                                <td class="px-5 py-4 font-body-md text-body-md text-on-surface-variant truncate max-w-[200px]">
                                    {{ $usuario->email }}
                                </td>

                                {{-- Rol --}}
                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                                        @forelse($rolesUsuario as $rol)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-label-md text-label-md font-bold {{ $rolClasses($rol) }}">
                                                {{ $rol }}
                                            </span>
                                        @empty
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full font-label-md text-label-md font-bold bg-surface-container text-outline">
                                                Sin rol
                                            </span>
                                        @endforelse
                                    </div>
                                </td>

                                {{-- Estado --}}
                                <td class="px-5 py-4 text-center">
                                    @if($usuario->active ?? true)
                                        <span class="inline-flex items-center gap-1.5 font-label-md text-label-md font-semibold text-on-tertiary-container">
                                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary-container"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 font-label-md text-label-md font-semibold text-outline">
                                            <span class="w-1.5 h-1.5 rounded-full bg-outline"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('usuarios.edit', $usuario) }}"
                                           class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-white dark:hover:bg-surface-container-high text-primary shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all"
                                           title="Editar usuario">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>
                                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST"
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar a {{ addslashes($usuario->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/80 dark:bg-surface-container-high/50 hover:bg-error hover:text-on-error text-error shadow-[0_2px_8px_rgba(0,0,0,0.04)] transition-all"
                                                    title="Eliminar usuario">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center">
                                    <span class="material-symbols-outlined text-[40px] text-outline mb-3 block">group_off</span>
                                    <p class="font-label-lg text-label-lg font-semibold text-on-surface-variant">No hay usuarios registrados</p>
                                    <p class="font-body-sm text-body-sm text-outline mt-1">Crea el primero con el botón de arriba</p>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        @if($usuarios->hasPages())
            <div class="flex items-center justify-between">
                <p class="font-body-sm text-body-sm text-on-surface-variant">
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

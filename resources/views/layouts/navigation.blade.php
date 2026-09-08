@php
    $iniciales = collect(explode(' ', Auth::user()->name))
        ->map(fn($p) => strtoupper($p[0] ?? ''))
        ->take(2)
        ->implode('');

    $navClass = fn(bool $active) => $active
        ? 'bg-[#6A2C75]/10 text-[#6A2C75]'
        : 'text-[#6E6274] hover:bg-[#6A2C75]/5 hover:text-[#6A2C75]';
@endphp

<nav x-data="{ open: false, userOpen: false }" class="sticky top-0 z-40 border-b border-[#2B2030]/10 bg-white/90 backdrop-blur-md">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center gap-8">

                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                </a>

                <div class="hidden sm:flex sm:items-center sm:gap-1">

                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ $navClass(request()->routeIs('dashboard')) }}">
                        Dashboard
                    </a>

                    @hasanyrole('Admin|RH|Supervisor|Coordinacion')
                    <a href="{{ route('empleados.index') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ $navClass(request()->routeIs('empleados.*')) }}">
                        Empleados
                    </a>
                    @endhasanyrole

                    <a href="{{ route('asistencias.index') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ $navClass(request()->routeIs('asistencias.*')) }}">
                        Asistencias
                    </a>

                    <a href="{{ route('hora-extras.index') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ $navClass(request()->routeIs('hora-extras.*')) }}">
                        Horas Extra
                    </a>

                    @role('Admin')
                    <a href="{{ route('usuarios.index') }}"
                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-150 {{ $navClass(request()->routeIs('usuarios.*')) }}">
                        Usuarios
                    </a>
                    @endrole

                </div>
            </div>

            <!-- User menu (desktop) -->
            <div class="hidden sm:flex sm:items-center">
                <div class="relative" @click.outside="userOpen = false">

                    <button @click="userOpen = !userOpen"
                        class="flex items-center gap-2 rounded-full py-1.5 pl-1.5 pr-3 transition-colors duration-150 hover:bg-[#6A2C75]/5">

                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-[#6A2C75] to-[#45193F] text-xs font-semibold text-white">
                            {{ $iniciales }}
                        </span>

                        <span class="text-sm font-medium text-[#2B2030]">
                            {{ Auth::user()->name }}
                        </span>

                        <svg class="h-4 w-4 text-[#6E6274] transition-transform duration-150"
                            :class="{ 'rotate-180': userOpen }"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.19l3.71-3.96a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="userOpen"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 origin-top-right overflow-hidden rounded-xl border border-[#2B2030]/10 bg-white py-1.5 shadow-lg shadow-[#45193F]/10"
                        style="display: none;">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-[#2B2030] transition-colors duration-150 hover:bg-[#6A2C75]/5">
                            Mi perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full px-4 py-2 text-left text-sm text-red-600 transition-colors duration-150 hover:bg-red-50">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-[#6E6274] transition-colors duration-150 hover:bg-[#6A2C75]/5 focus:outline-none">

                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-transition class="border-t border-[#2B2030]/10 bg-white sm:hidden" style="display: none;">

        <div class="space-y-1 px-4 py-3">

            <a href="{{ route('dashboard') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium {{ $navClass(request()->routeIs('dashboard')) }}">
                Dashboard
            </a>

            @hasanyrole('Admin|RH|Supervisor|Coordinacion')
            <a href="{{ route('empleados.index') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium {{ $navClass(request()->routeIs('empleados.*')) }}">
                Empleados
            </a>
            @endhasanyrole

            <a href="{{ route('asistencias.index') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium {{ $navClass(request()->routeIs('asistencias.*')) }}">
                Asistencias
            </a>

            <a href="{{ route('hora-extras.index') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium {{ $navClass(request()->routeIs('hora-extras.*')) }}">
                Horas Extra
            </a>

            @role('Admin')
            <a href="{{ route('usuarios.index') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium {{ $navClass(request()->routeIs('usuarios.*')) }}">
                Usuarios
            </a>
            @endrole

        </div>

        <div class="border-t border-[#2B2030]/10 px-4 py-3">

            <div class="mb-3 flex items-center gap-3">

                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-[#6A2C75] to-[#45193F] text-xs font-semibold text-white">
                    {{ $iniciales }}
                </span>

                <div>
                    <div class="text-sm font-semibold text-[#2B2030]">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#6E6274]">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}"
                class="block rounded-lg px-3 py-2 text-sm font-medium text-[#6E6274] transition-colors duration-150 hover:bg-[#6A2C75]/5 hover:text-[#6A2C75]">
                Mi perfil
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 transition-colors duration-150 hover:bg-red-50">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</nav>

<x-guest-layout layout="split">
    {{-- Logo real (sin filtros ni deformación; ya incluye el nombre "Dasavena") --}}
    <div class="flex justify-center">
        <img src="{{ asset('images/logo-caja.png') }}"
            alt="Dasavena — Recetas de familia"
            width="112"
            height="112"
            class="h-24 w-24 object-contain sm:h-28 sm:w-28">
    </div>

    {{-- Encabezado --}}
    <div class="mt-6 text-center">
        <h2 class="font-headline-xl text-[2rem] font-bold leading-tight tracking-tight text-[color:var(--dsv-ink)] sm:text-4xl">Bienvenido</h2>
        <p class="mt-2 text-sm text-[color:var(--dsv-muted)]">Sistema de Control de Asistencias</p>
    </div>

    {{-- Estado de sesión (p. ej. "contraseña restablecida") --}}
    <x-auth-session-status class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm !text-emerald-800" :status="session('status')" />

    <form method="POST"
        action="{{ route('login') }}"
        class="mt-8 space-y-5"
        x-data="{ enviando: false }"
        @submit="enviando = true">
        @csrf

        {{-- Correo (la autenticación actual es por correo electrónico) --}}
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-[color:var(--dsv-ink)]">Correo electrónico</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A79DAB]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="8" r="4" />
                    <path stroke-linecap="round" d="M4 20c1.5-3.5 4.4-5 8-5s6.5 1.5 8 5" />
                </svg>
                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nombre@dasavena.com"
                    class="login-field"
                    @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            </div>
            <x-input-error id="email-error" :messages="$errors->get('email')" class="mt-2 !text-[#C2413B]" />
        </div>

        {{-- Contraseña con mostrar / ocultar --}}
        <div x-data="{ visible: false }">
            <label for="password" class="mb-1.5 block text-sm font-medium text-[color:var(--dsv-ink)]">Contraseña</label>
            <div class="relative">
                <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#A79DAB]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
                    <rect x="5" y="10.5" width="14" height="10" rx="2" />
                    <path stroke-linecap="round" d="M8 10.5V7.5a4 4 0 0 1 8 0v3" />
                </svg>
                <input id="password"
                    type="password"
                    :type="visible ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Contraseña"
                    class="login-field !pr-12"
                    @if ($errors->has('password')) aria-invalid="true" aria-describedby="password-error" @endif>
                <button type="button"
                    @click="visible = !visible"
                    :aria-pressed="visible.toString()"
                    :aria-label="visible ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                    aria-label="Mostrar contraseña"
                    class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-[#8B7F8F] transition-colors hover:bg-[#F3EEF4] hover:text-[color:var(--dsv-purple)] focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--dsv-purple)]">
                    {{-- Ojo abierto --}}
                    <svg x-show="!visible" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    {{-- Ojo tachado --}}
                    <svg x-show="visible" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.1A9.6 9.6 0 0 1 12 6c6 0 9.5 6 9.5 6a17 17 0 0 1-3 3.6M6.4 7.9A16.6 16.6 0 0 0 2.5 12S6 18 12 18a9.4 9.4 0 0 0 4.2-1M9.9 10a3 3 0 0 0 4.1 4.1" />
                    </svg>
                </button>
            </div>
            <x-input-error id="password-error" :messages="$errors->get('password')" class="mt-2 !text-[#C2413B]" />
        </div>

        {{-- Opciones --}}
        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex cursor-pointer select-none items-center gap-2.5">
                <input id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="h-4 w-4 rounded border-[#CFC6D2] text-[color:var(--dsv-purple)] focus:ring-2 focus:ring-[rgba(106,44,117,0.3)] focus:ring-offset-0">
                <span class="text-sm text-[color:var(--dsv-muted)]">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="rounded text-sm font-medium text-[color:var(--dsv-purple)] underline-offset-4 hover:underline focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--dsv-purple)]">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Entrar (Enter también envía; se deshabilita mientras procesa) --}}
        <button type="submit"
            class="login-submit inline-flex h-[3.25rem] w-full items-center justify-center gap-2 rounded-xl text-[15px] font-semibold text-white"
            :disabled="enviando"
            :aria-busy="enviando.toString()">
            <span x-text="enviando ? 'Entrando…' : 'Entrar'">Entrar</span>
            <svg x-show="!enviando" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
            </svg>
            <svg x-show="enviando" x-cloak class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".3" stroke-width="2.5" />
                <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
            </svg>
        </button>
    </form>

    {{--
        Microsoft 365: el proyecto aún no tiene inicio de sesión con Microsoft
        (Graph se usa sólo para enviar correos). El botón aparece en cuanto
        exista una ruta llamada "login.microsoft".
    --}}
    @if (Route::has('login.microsoft'))
        <div class="my-6 flex items-center gap-4" role="separator">
            <span class="h-px flex-1 bg-[#E4DFE6]"></span>
            <span class="text-xs uppercase tracking-widest text-[#A79DAB]">o</span>
            <span class="h-px flex-1 bg-[#E4DFE6]"></span>
        </div>

        <a href="{{ route('login.microsoft') }}"
            class="flex h-[3.25rem] w-full items-center justify-center gap-3 rounded-xl border border-[#E4DFE6] bg-white text-sm font-medium text-[color:var(--dsv-ink)] transition-colors hover:border-[#D3CAD6] hover:bg-[#FAF8FB] focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--dsv-purple)]">
            <svg class="h-5 w-5" viewBox="0 0 21 21" aria-hidden="true">
                <rect x="1" y="1" width="9" height="9" fill="#F25022" />
                <rect x="11" y="1" width="9" height="9" fill="#7FBA00" />
                <rect x="1" y="11" width="9" height="9" fill="#00A4EF" />
                <rect x="11" y="11" width="9" height="9" fill="#FFB900" />
            </svg>
            Iniciar sesión con Microsoft 365
        </a>
    @endif

    {{-- Pie del sistema --}}
    <div class="mt-10 flex items-center justify-center gap-3 border-t border-[#ECE7EE] pt-6">
        {{-- Calendario con palomita (asistencia registrada) --}}
        <svg class="h-7 w-7 text-[color:var(--dsv-gold)]" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 28 28" aria-hidden="true">
            <rect x="4" y="5.5" width="20" height="18.5" rx="3" />
            <path d="M4 11h20M9.5 3.5v4M18.5 3.5v4" />
            <path d="m10.5 17.2 2.4 2.3 4.8-4.8" stroke="var(--dsv-purple)" />
        </svg>
        <div class="leading-tight">
            <p class="text-xs text-[color:var(--dsv-muted)]">Sistema de Control de Asistencias</p>
            <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-[color:var(--dsv-ink)]">Dasavena Gourmet</p>
        </div>
    </div>
</x-guest-layout>

@php
    // <x-guest-layout layout="split"> → login corporativo: panel de marca + formulario.
    $layoutDividido = $attributes->get('layout') === 'split';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $layoutDividido ? 'Iniciar sesión · Dasavena Gourmet' : config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @if ($layoutDividido)
        <body class="font-sans antialiased">
            <div class="login-page">

                {{--
                    Fondo 3D (Three.js) preparado pero sin inicializar.
                    Para activarlo: data-production-background data-mode="login"
                --}}
                <div id="production-background" class="production-background" aria-hidden="true"></div>
                <div class="production-overlay" aria-hidden="true"></div>

                <div class="login-content min-h-screen md:grid md:grid-cols-[42%_58%] lg:grid-cols-[45%_55%]">

                    {{-- ============ PANEL DE MARCA ============ --}}
                    <section class="login-brand-panel relative overflow-hidden text-white" aria-label="Dasavena Gourmet">

                        {{-- Decoración botánica (avena, hojas y curvas) muy tenue --}}
                        <svg class="pointer-events-none absolute inset-0 h-full w-full" viewBox="0 0 600 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                            <g fill="none" stroke="#fff" stroke-opacity=".06">
                                <circle cx="610" cy="140" r="210" />
                                <circle cx="610" cy="140" r="290" />
                                <circle cx="610" cy="140" r="370" />
                            </g>
                            <path d="M-40 760 C 140 650, 330 700, 640 560" fill="none" stroke="#E2B45A" stroke-opacity=".10" stroke-width="1.5" />
                            <path d="M-40 815 C 170 720, 360 770, 640 640" fill="none" stroke="#fff" stroke-opacity=".05" stroke-width="1" />

                            {{-- Espigas de avena --}}
                            <g fill="none" stroke="#E2B45A" stroke-opacity=".16" stroke-width="1.6" stroke-linecap="round">
                                <path d="M470 920 C 462 800, 470 690, 520 560" />
                                <path d="M520 560 C 500 530, 478 520, 452 522" />
                                <path d="M507 600 C 532 590, 552 592, 572 604" />
                                <path d="M492 650 C 466 640, 446 642, 424 654" />
                                <path d="M482 705 C 508 698, 528 702, 548 716" />
                            </g>
                            <g fill="#E2B45A" fill-opacity=".10">
                                <ellipse cx="452" cy="522" rx="17" ry="8" transform="rotate(-20 452 522)" />
                                <ellipse cx="572" cy="604" rx="17" ry="8" transform="rotate(25 572 604)" />
                                <ellipse cx="424" cy="654" rx="17" ry="8" transform="rotate(-25 424 654)" />
                                <ellipse cx="548" cy="716" rx="17" ry="8" transform="rotate(30 548 716)" />
                                <ellipse cx="522" cy="556" rx="9" ry="18" transform="rotate(22 522 556)" />
                            </g>

                            {{-- Hojas orgánicas --}}
                            <g fill="#C9AFCD" fill-opacity=".07">
                                <path d="M560 300 C 470 330, 430 420, 470 500 C 540 450, 580 380, 560 300 Z" />
                                <path d="M80 880 C 40 780, 90 700, 180 690 C 190 780, 150 850, 80 880 Z" />
                            </g>
                        </svg>

                        {{-- Franja superior en móvil --}}
                        <div class="relative flex items-center justify-between gap-4 px-5 py-4 md:hidden">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.32em]">Dasavena Gourmet</p>
                                <span class="mt-2 block h-px w-10 bg-[color:var(--dsv-gold)]"></span>
                            </div>
                            <p class="text-right text-xs text-white/70">Transformando alimentos<br>en <span class="text-[color:var(--dsv-gold-soft)]">un mejor mañana.</span></p>
                        </div>

                        {{-- Contenido completo (tablet / escritorio) --}}
                        <div class="relative hidden h-full min-h-screen flex-col justify-between px-8 py-10 md:flex lg:px-14 lg:py-14 xl:px-16">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/90">Dasavena Gourmet</p>
                                <span class="mt-4 block h-px w-14 bg-[color:var(--dsv-gold)]"></span>
                            </div>

                            <div class="max-w-md py-10">
                                <h1 class="font-headline-xl text-[2.1rem] leading-[1.15] font-bold tracking-tight lg:text-[2.75rem] xl:text-5xl">
                                    Transformando<br>
                                    alimentos en<br>
                                    <span class="text-[color:var(--dsv-gold-soft)]">un mejor mañana.</span>
                                </h1>
                                <p class="mt-6 max-w-sm text-[15px] leading-relaxed text-white/65 lg:text-base">
                                    Procesos eficientes, equipos conectados y decisiones inteligentes para impulsar el crecimiento de Dasavena Gourmet.
                                </p>
                            </div>

                            <div>
                                {{-- Indicadores --}}
                                <ul class="grid grid-cols-3 divide-x divide-white/15 border-y border-white/10 py-5">
                                    <li class="flex flex-col gap-2 pr-3 lg:flex-row lg:items-center lg:gap-3 lg:pr-4">
                                        <svg class="h-6 w-6 shrink-0 text-[color:var(--dsv-gold)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l5-5 4 4 8-8M15 8h5v5" />
                                        </svg>
                                        <span class="text-xs leading-snug text-white/80 lg:text-sm">Procesos<br>más eficientes</span>
                                    </li>
                                    <li class="flex flex-col gap-2 px-3 lg:flex-row lg:items-center lg:gap-3 lg:px-4">
                                        <svg class="h-6 w-6 shrink-0 text-[color:var(--dsv-gold)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <circle cx="12" cy="7" r="3" /><circle cx="5" cy="17" r="2.5" /><circle cx="19" cy="17" r="2.5" />
                                            <path stroke-linecap="round" d="M10.3 9.5 6.5 14.8M13.7 9.5l3.8 5.3M7.5 17h9" />
                                        </svg>
                                        <span class="text-xs leading-snug text-white/80 lg:text-sm">Equipos<br>conectados</span>
                                    </li>
                                    <li class="flex flex-col gap-2 pl-3 lg:flex-row lg:items-center lg:gap-3 lg:pl-4">
                                        <svg class="h-6 w-6 shrink-0 text-[color:var(--dsv-gold)]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21V9M12 9c0-3 2-5 5-6 0 3-2 5-5 6Zm0 4c0-3-2-5-5-6 0 3 2 5 5 6Zm0 4c0-2.5 1.8-4.2 4.5-5-.2 2.6-2 4.4-4.5 5Z" />
                                        </svg>
                                        <span class="text-xs leading-snug text-white/80 lg:text-sm">Alimentos<br>de calidad</span>
                                    </li>
                                </ul>

                                <ul class="mt-6 flex flex-wrap items-center gap-x-3 gap-y-2 text-[10px] font-semibold uppercase tracking-[0.22em] text-white/45 lg:gap-x-4 lg:text-[11px] lg:tracking-[0.3em]">
                                    @foreach (['Calidad', 'Personas', 'Innovación', 'Alimentos'] as $valor)
                                        @if (! $loop->first)
                                            <li aria-hidden="true" class="text-[#E2B45A]/60">·</li>
                                        @endif
                                        <li>{{ $valor }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </section>

                    {{-- ============ PANEL DE LOGIN ============ --}}
                    <main class="flex min-h-[calc(100vh-72px)] items-center justify-center bg-[color:var(--dsv-ivory)] px-5 py-10 sm:px-10 md:min-h-screen lg:px-16">
                        <div class="w-full max-w-[420px]">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </body>
    @else
        <body class="font-sans text-gray-900 antialiased dark:text-gray-100">
            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500 dark:text-gray-400" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg dark:bg-gray-800">
                    {{ $slot }}
                </div>
            </div>
        </body>
    @endif
</html>

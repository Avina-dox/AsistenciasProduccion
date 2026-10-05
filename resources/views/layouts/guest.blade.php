@php
    // <x-guest-layout background="production"> activa el fondo 3D de planta.
    $fondoProduccion = $attributes->get('background') === 'production';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @if ($fondoProduccion)
        <body class="font-sans text-gray-900 antialiased bg-[#0A1119]">
            <x-production-background mode="login" />

            <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 py-10 lg:items-end lg:pr-[8vw] xl:pr-[11vw]">
                <div class="w-full sm:max-w-md flex flex-col items-center">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-white/80 drop-shadow-[0_4px_20px_rgba(0,0,0,0.45)]" />
                    </a>

                    <div class="w-full mt-6 px-6 py-6 bg-white/95 backdrop-blur-xl shadow-2xl shadow-black/40 ring-1 ring-white/10 overflow-hidden rounded-2xl">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            {{-- Leyenda discreta del gemelo digital (sólo escritorio) --}}
            <div class="hidden lg:flex fixed bottom-8 left-10 z-10 items-center gap-3 text-white/60 pointer-events-none select-none">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60 motion-safe:animate-ping"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                </span>
                <span class="text-xs font-semibold uppercase tracking-[0.2em]">Planta en línea</span>
                <span class="text-xs text-white/40">· Preparación → Procesamiento → Empaquetado</span>
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

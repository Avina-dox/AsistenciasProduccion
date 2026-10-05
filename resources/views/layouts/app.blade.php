<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Dasaasistencias') }}</title>

        {{-- Aplica el tema guardado (o el del sistema) antes de pintar, para evitar parpadeo --}}
        <script>
            (function () {
                try {
                    const guardado = localStorage.getItem('theme');
                    const prefiereOscuro = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (guardado === 'dark' || (!guardado && prefiereOscuro)) {
                        document.documentElement.classList.add('dark');
                    }
                } catch (e) {}
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-brand-page-to dark:bg-brand-page-to">
        <div class="min-h-screen bg-gray-100 dark:bg-brand-page-to">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @endisset

                @yield('content')
            </main>
        </div>
    @livewireScripts
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')

    </body>
</html>

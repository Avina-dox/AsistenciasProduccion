<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Dasaasistencias') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <!-- Tailwind (utility layer for layout/spacing/typography) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            plum: { DEFAULT: '#6A2C75', deep: '#45193F', soft: '#8A4C96' },
                            gold: { DEFAULT: '#B6A644', light: '#C9BA5A' },
                            ivory: { DEFAULT: '#FBF8F3', deep: '#F4EFE6' },
                            ink: { DEFAULT: '#2B2030', soft: '#5A4E60' },
                        },
                        fontFamily: {
                            display: ['Century Gothic', 'AppleGothic', 'sans-serif'],
                            serif: ['Cormorant Garamond', 'Georgia', 'serif'],
                        },
                    }
                }
            }
        </script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

        <style>
            :root {
                --plum: #6A2C75;
                --plum-deep: #45193F;
                --gold: #B6A644;
            }

            body {
                font-family: 'Century Gothic', 'AppleGothic', 'CentGothic', sans-serif;
            }

            /* ---------- ambient orbs ---------- */
            .orb-field {
                position: fixed;
                inset: 0;
                overflow: hidden;
                z-index: 0;
                pointer-events: none;
            }
            .orb {
                position: absolute;
                border-radius: 50%;
                border: 1px solid rgba(182,166,68,0.35);
                background: transparent;
                filter: blur(0.3px);
                opacity: 0;
                animation: orb-in 1.4s ease forwards, orb-drift 22s ease-in-out infinite;
            }
            .orb::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 50%;
                background: radial-gradient(circle at 35% 30%, rgba(106,44,117,0.10), transparent 70%);
            }
            .orb-1 { width: 420px; height: 420px; top: -8%; left: -10%;  border-color: rgba(106,44,117,0.28); animation-delay: 0s, 0s; }
            .orb-2 { width: 260px; height: 260px; top: 55%; left: 78%;  border-color: rgba(182,166,68,0.4);  animation-delay: 0.2s, 2s; }
            .orb-3 { width: 340px; height: 340px; top: 68%; left: -6%;  border-color: rgba(106,44,117,0.22); animation-delay: 0.4s, 4s; }
            .orb-4 { width: 180px; height: 180px; top: 6%;  left: 82%;  border-color: rgba(182,166,68,0.35); animation-delay: 0.6s, 1s; }
            .orb-5 { width: 130px; height: 130px; top: 38%; left: 46%;  border-color: rgba(106,44,117,0.18); animation-delay: 0.8s, 3s; }

            @keyframes orb-in {
                to { opacity: 1; }
            }
            @keyframes orb-drift {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33%      { transform: translate(18px, -24px) scale(1.04); }
                66%      { transform: translate(-16px, 14px) scale(0.97); }
            }

            /* ---------- entrance choreography ---------- */
            .fade-up {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeUp 0.8s ease forwards;
            }
            .fade-down {
                opacity: 0;
                transform: translateY(-15px);
                animation: fadeDown 0.8s ease forwards;
            }
            .pop-in {
                opacity: 0;
                transform: scale(0.7);
                animation: popIn 0.9s cubic-bezier(.2,.8,.2,1) forwards;
            }
            @keyframes fadeUp   { to { opacity: 1; transform: translateY(0); } }
            @keyframes fadeDown { to { opacity: 1; transform: translateY(0); } }
            @keyframes popIn    { to { opacity: 1; transform: scale(1); } }

            .headline em {
                font-family: 'Cormorant Garamond', Georgia, serif;
                font-style: italic;
                font-weight: 500;
                background: linear-gradient(100deg, var(--plum) 20%, var(--gold) 50%, var(--plum) 80%);
                background-size: 220% auto;
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer 6s linear infinite;
                padding-right: 4px;
            }
            @keyframes shimmer { to { background-position: -220% center; } }

            .divider::before {
                content: '◆';
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                font-size: 0.6rem;
                color: var(--gold);
                background: #FDFBF7;
                padding: 0 12px;
            }

            .card::after {
                content: '';
                position: absolute;
                inset: 0;
                border-radius: 16px;
                padding: 1px;
                background: linear-gradient(135deg, rgba(106,44,117,0.2), transparent 40%, rgba(182,166,68,0.4) 60%, rgba(106,44,117,0.2));
                -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                -webkit-mask-composite: xor;
                mask-composite: exclude;
                opacity: 0.3;
                transition: opacity 0.5s ease;
            }
            .card:hover::after { opacity: 1; }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body class="min-h-screen relative text-ink"
          style="background-color:#FBF8F3; background-image: radial-gradient(circle at 15% 15%, rgba(106,44,117,0.06) 0%, transparent 45%), radial-gradient(circle at 85% 85%, rgba(182,166,68,0.08) 0%, transparent 50%), linear-gradient(180deg, #FDFBF7 0%, #FBF8F3 100%);">

        <!-- Orbes animados de fondo -->
        <div class="orb-field">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
            <div class="orb orb-4"></div>
            <div class="orb orb-5"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col items-center px-6 pt-10 pb-16">
            @if (Route::has('login'))
                <header class="fade-down w-full max-w-5xl flex justify-end gap-4 mb-20">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="inline-block px-9 py-3.5 rounded text-xs tracking-[0.15em] uppercase font-bold text-ivory transition-all duration-300 hover:-translate-y-0.5"
                           style="background: linear-gradient(135deg, var(--plum) 0%, var(--plum-deep) 100%); box-shadow: 0 10px 25px -8px rgba(106,44,117,0.35);">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-block px-9 py-3.5 rounded text-xs tracking-[0.15em] uppercase font-bold border transition-all duration-300 hover:-translate-y-0.5"
                           style="color: var(--plum); border-color: rgba(106,44,117,0.3);">
                            Iniciar sesión
                        </a>
                    @endauth
                </header>
            @endif

            <main class="w-full max-w-3xl text-center mt-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                     class="pop-in mx-auto mb-9 rounded-full bg-white shadow-lg"
                     style="width: 130px; height: auto; animation-delay: 0.15s; box-shadow: 0 8px 20px rgba(43,32,48,0.04);">

                <p class="fade-up font-bold uppercase tracking-[0.5em] text-xs text-ink-soft mb-6"
                   style="animation-delay: 0.3s;">
                    Sistema de asistencias
                </p>

                <h1 class="headline fade-up text-[clamp(3rem,6.5vw,4.8rem)] leading-[1.1] mb-6 font-normal"
                    style="color: var(--plum-deep); letter-spacing: -0.02em; animation-delay: 0.45s;">
                    <em>Bienvenido</em>
                </h1>

                <div class="divider fade-up relative w-[140px] h-px mx-auto my-8"
                     style="background: linear-gradient(90deg, transparent, var(--gold), transparent); animation-delay: 0.6s;"></div>

                <p class="fade-up text-xl text-ink-soft mb-20 max-w-xl mx-auto leading-relaxed"
                   style="animation-delay: 0.7s;">
                    Precisión, elegancia y control absoluto sobre el registro de tu equipo.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-7 rounded-3xl backdrop-blur-xl"
                     style="background: rgba(255,255,255,0.4); border: 1px solid rgba(182,166,68,0.18); box-shadow: 0 30px 70px rgba(43,32,48,0.05);">

                    <div class="card fade-up relative text-left p-10 rounded-2xl min-h-[250px] transition-all duration-500 hover:-translate-y-2"
                         style="background: rgba(255,255,255,0.85); border: 1px solid rgba(106,44,117,0.08); animation-delay: 0.85s;">
                        <span class="block mb-5 font-serif italic text-lg font-semibold" style="color: var(--gold);">I.</span>
                        <h3 class="text-2xl font-normal mb-4" style="color: var(--plum-deep);">Reportes</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Analiza en tiempo real tus datos de asistencia con paneles claros y detallados.</p>
                    </div>

                    <div class="card fade-up relative text-left p-10 rounded-2xl min-h-[250px] transition-all duration-500 hover:-translate-y-2"
                         style="background: rgba(255,255,255,0.85); border: 1px solid rgba(106,44,117,0.08); animation-delay: 1.0s;">
                        <span class="block mb-5 font-serif italic text-lg font-semibold" style="color: var(--gold);">II.</span>
                        <h3 class="text-2xl font-normal mb-4" style="color: var(--plum-deep);">Seguro</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Protege tu información con encriptación avanzada y control de acceso riguroso.</p>
                    </div>

                    <div class="card fade-up relative text-left p-10 rounded-2xl min-h-[250px] transition-all duration-500 hover:-translate-y-2"
                         style="background: rgba(255,255,255,0.85); border: 1px solid rgba(106,44,117,0.08); animation-delay: 1.15s;">
                        <span class="block mb-5 font-serif italic text-lg font-semibold" style="color: var(--gold);">III.</span>
                        <h3 class="text-2xl font-normal mb-4" style="color: var(--plum-deep);">Rápido</h3>
                        <p class="text-sm text-ink-soft leading-relaxed">Acceso instantáneo a tu información, donde y cuando lo necesites.</p>
                    </div>
                </div>
            </main>

            <footer class="h-20"></footer>
        </div>
    </body>
</html>
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

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

        <style>
            :root {
                --plum: #6A2C75;
                --plum-deep: #45193F;
                --plum-soft: #8A4C96;
                --gold: #B6A644;
                --gold-light: #C9BA5A;
                --ivory: #FBF8F3;
                --ivory-deep: #F4EFE6;
                --ink: #2B2030;
                --ink-soft: #5A4E60;
            }

            * { box-sizing: border-box; }

            html, body {
                margin: 0;
                padding: 0;
                min-height: 100vh;
                /* Century Gothic con fallbacks limpios */
                font-family: 'Century Gothic', 'AppleGothic', 'CentGothic', sans-serif;
                color: var(--ink);
                background-color: var(--ivory);
                background-image: 
                    radial-gradient(circle at 15% 15%, rgba(106,44,117,0.06) 0%, transparent 45%),
                    radial-gradient(circle at 85% 85%, rgba(182,166,68,0.08) 0%, transparent 50%),
                    linear-gradient(180deg, #FDFBF7 0%, var(--ivory) 100%);
                overflow-x: hidden;
            }

            /* ---------- layout shell ---------- */
            .page {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 2.5rem 1.5rem 4rem;
            }

            header.top-nav {
                width: 100%;
                max-width: 80rem;
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                margin-bottom: 5rem;
                opacity: 0;
                animation: fadeDown 0.8s ease forwards;
            }

            .btn {
                display: inline-block;
                padding: 0.85rem 2.2rem;
                border-radius: 4px;
                font-size: 0.8rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                text-decoration: none;
                font-weight: bold;
                transition: all 0.4s cubic-bezier(.4,0,.2,1);
            }
            .btn-ghost {
                color: var(--plum);
                border: 1px solid rgba(106,44,117,0.3);
                background: transparent;
            }
            .btn-ghost:hover {
                background: rgba(106,44,117,0.05);
                border-color: var(--plum);
                transform: translateY(-1px);
            }
            .btn-solid {
                color: var(--ivory);
                background: linear-gradient(135deg, var(--plum) 0%, var(--plum-deep) 100%);
                box-shadow: 0 10px 25px -8px rgba(106,44,117,0.35);
            }
            .btn-solid:hover {
                box-shadow: 0 15px 30px -5px rgba(106,44,117,0.45);
                transform: translateY(-3px);
            }

            /* ---------- hero ---------- */
            main {
                width: 100%;
                max-width: 64rem;
                text-align: center;
                margin-top: 1rem;
            }

            .crest {
                width: 76px;
                height: 76px;
                margin: 0 auto 2.25rem;
                border: 1px solid var(--gold);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Cormorant Garamond', serif;
                font-style: italic;
                font-size: 1.9rem;
                color: var(--plum);
                position: relative;
                opacity: 0;
                animation: popIn 0.9s 0.15s cubic-bezier(.2,.8,.2,1) forwards;
                background: white;
                box-shadow: 0 8px 20px rgba(43,32,48,0.04);
            }
            .crest::before {
                content: '';
                position: absolute;
                inset: -8px;
                border: 1px solid rgba(182,166,68,0.3);
                border-radius: 50%;
            }

            .eyebrow {
                text-transform: uppercase;
                letter-spacing: 0.5em;
                font-size: 0.75rem;
                color: var(--ink-soft);
                margin-bottom: 1.5rem;
                font-weight: bold;
                opacity: 0;
                animation: fadeUp 0.8s 0.3s ease forwards;
            }

            h1.headline {
                font-size: clamp(3rem, 6.5vw, 4.8rem);
                font-weight: normal; /* Century Gothic luce mejor sin exceso de peso */
                line-height: 1.1;
                margin: 0 0 1.5rem;
                color: var(--plum-deep);
                opacity: 0;
                animation: fadeUp 0.9s 0.45s ease forwards;
                letter-spacing: -0.02em;
            }
            h1.headline em {
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

            .divider {
                width: 140px;
                height: 1px;
                margin: 2rem auto;
                background: linear-gradient(90deg, transparent, var(--gold), transparent);
                position: relative;
                opacity: 0;
                animation: fadeUp 0.8s 0.6s ease forwards;
            }
            .divider::before {
                content: '◆';
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                font-size: 0.6rem;
                color: var(--gold);
                background: #FDFBF7; /* Combina con el gradiente superior */
                padding: 0 12px;
            }

            p.subhead {
                font-size: 1.25rem;
                color: var(--ink-soft);
                font-weight: normal;
                letter-spacing: 0.02em;
                margin-bottom: 5rem;
                opacity: 0;
                animation: fadeUp 0.8s 0.7s ease forwards;
                max-width: 42rem;
                margin-left: auto;
                margin-right: auto;
                line-height: 1.6;
            }

            /* ---------- feature cards ---------- */
            .cards {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                gap: 2rem;
                margin-top: 1.5rem;
                padding: 1.75rem;
                background: rgba(255, 255, 255, 0.4);
                border: 1px solid rgba(182,166,68,0.18);
                border-radius: 24px;
                box-shadow: 0 30px 70px rgba(43,32,48,0.05);
                backdrop-filter: blur(20px);
            }
            @media (min-width: 768px) {
                .cards { grid-template-columns: repeat(3, 1fr); }
            }

            .card {
                position: relative;
                padding: 2.5rem 2rem;
                background: rgba(255, 255, 255, 0.85);
                border: 1px solid rgba(106,44,117,0.08);
                border-radius: 16px;
                text-align: left;
                overflow: hidden;
                min-height: 250px;
                opacity: 0;
                transform: translateY(20px);
                animation: fadeUp 0.8s ease forwards;
                transition: all 0.5s cubic-bezier(.2,.8,.2,1);
            }
            .card:nth-child(1) { animation-delay: 0.85s; }
            .card:nth-child(2) { animation-delay: 1.0s; }
            .card:nth-child(3) { animation-delay: 1.15s; }

            /* Borde interno metalizado y sutil */
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

            .card:hover {
                box-shadow: 0 25px 50px rgba(43,32,48,0.08);
                transform: translateY(-8px);
                border-color: rgba(106,44,117,0.18);
                background: #FFFFFF;
            }
            .card:hover::after {
                opacity: 1;
            }

            .card .num {
                font-family: 'Cormorant Garamond', serif;
                font-style: italic;
                font-size: 1.1rem;
                color: var(--gold);
                letter-spacing: 0.1em;
                margin-bottom: 1.25rem;
                display: block;
                font-weight: 600;
            }
            .card h3 {
                font-size: 1.5rem;
                font-weight: normal;
                color: var(--plum-deep);
                margin: 0 0 1rem;
                letter-spacing: -0.01em;
            }
            .card p {
                font-size: 0.95rem;
                color: var(--ink-soft);
                line-height: 1.7;
                margin: 0;
            }

            footer.spacer { height: 5rem; }

            /* ---------- keyframes ---------- */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes fadeDown {
                from { opacity: 0; transform: translateY(-15px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes popIn {
                from { opacity: 0; transform: scale(0.7); }
                to   { opacity: 1; transform: scale(1); }
            }
            @keyframes shimmer {
                to { background-position: -220% center; }
            }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body>
        <div class="page">
            @if (Route::has('login'))
                <header class="top-nav">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-solid">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost">Iniciar sesión</a>
                    @endauth
                </header>
            @endif

            <main>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="crest" style="width: 130px; height: auto;">
                <p class="eyebrow">Sistema de asistencias</p>
                <h1 class="headline">Bien<em>venido</em></h1>
                <div class="divider"></div>
                <p class="subhead">Precisión, elegancia y control absoluto sobre el registro de tu equipo.</p>

                <div class="cards">
                    <div class="card">
                        <span class="num">I.</span>
                        <h3>Reportes</h3>
                        <p>Analiza en tiempo real tus datos de asistencia con paneles claros y detallados.</p>
                    </div>
                    <div class="card">
                        <span class="num">II.</span>
                        <h3>Seguro</h3>
                        <p>Protege tu información con encriptación avanzada y control de acceso riguroso.</p>
                    </div>
                    <div class="card">
                        <span class="num">III.</span>
                        <h3>Rápido</h3>
                        <p>Acceso instantáneo a tu información, donde y cuando lo necesites.</p>
                    </div>
                </div>
            </main>

            <footer class="spacer"></footer>
        </div>
    </body>
</html>
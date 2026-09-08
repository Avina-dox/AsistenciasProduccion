@props([
    'titulo',
    'valor',
    'descripcion' => null,
    'color' => 'violet',
])

@php
    $paleta = [
        'amber' => ['ring' => '#B6A644', 'text' => '#92752F', 'from' => '#FBF6E4', 'to' => '#F3EDE3'],
        'violet' => ['ring' => '#6A2C75', 'text' => '#6A2C75', 'from' => '#F3EAF5', 'to' => '#F8F0FA'],
        'sky' => ['ring' => '#0284C7', 'text' => '#0284C7', 'from' => '#E6F4FB', 'to' => '#EFF8FF'],
        'indigo' => ['ring' => '#4338CA', 'text' => '#4338CA', 'from' => '#EEF2FF', 'to' => '#F3F0FF'],
        'orange' => ['ring' => '#EA580C', 'text' => '#C2410C', 'from' => '#FDECE2', 'to' => '#FFF4ED'],
    ];

    $c = $paleta[$color] ?? $paleta['violet'];
@endphp

<div class="group relative w-full rounded-2xl transition-transform duration-500 hover:-translate-y-1"
    style="padding:1px; background: linear-gradient(135deg, {{ $c['ring'] }}66, rgba(182,166,68,.35), {{ $c['ring'] }}66);">

    <div class="hud-corners relative h-full overflow-hidden rounded-2xl bg-white p-6"
        style="color: {{ $c['ring'] }};">

        <div class="pointer-events-none absolute -right-6 -bottom-6 h-24 w-24 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-30"
            style="background: {{ $c['ring'] }};"></div>

        <div class="relative flex items-start justify-between gap-4">

            <div class="min-w-0">
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#6E6274]">
                    {{ $titulo }}
                </p>

                <h3 class="mono-font mt-2 text-3xl font-bold tabular-nums" style="color: {{ $c['text'] }};">
                    {{ $valor }}
                </h3>

                @if($descripcion)
                    <p class="mt-1 text-xs text-[#A8A0AC]">
                        {{ $descripcion }}
                    </p>
                @endif
            </div>

            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-110"
                style="background: linear-gradient(135deg, {{ $c['from'] }}, {{ $c['to'] }}); color: {{ $c['text'] }};">
                {{ $slot }}
            </div>

        </div>
    </div>
</div>

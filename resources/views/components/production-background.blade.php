{{--
    Fondo 3D de línea de producción (Three.js).

    Uso:
        <x-production-background mode="login" />
        <x-production-background mode="dashboard" :data="$productionData" />

    `data` es opcional (si no se pasa se usan datos mock). Formato:
        ['status' => 'running', 'speed' => 72, 'efficiency' => 94,
         'stations' => [['id' => 'preparation', 'status' => 'running'], ...]]

    Capas (z-index): 0 canvas · 1 overlay · 2 etiquetas · 10 contenido.
    Todo es pointer-events: none: nunca bloquea la interfaz.
--}}
@props(['mode' => 'login', 'data' => null])

<div class="production-bg production-bg--{{ $mode }}" aria-hidden="true">
    <div
        id="production-background"
        class="production-bg__scene"
        data-production-background
        data-mode="{{ $mode }}"
        @if ($data) data-production='@json($data)' @endif
    ></div>

    <div class="production-overlay production-overlay--{{ $mode }}"></div>

    @if ($mode === 'dashboard')
        <div class="production-labels" data-production-labels></div>
    @endif
</div>

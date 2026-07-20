@props([
    'titulo',
    'valor',
    'icono' => '📊',
    'color' => 'blue',
    'descripcion' => null,
])

@php

$colors = [

    'blue' => [
        'bg' => 'bg-blue-100',
        'text' => 'text-blue-600',
    ],

    'emerald' => [
        'bg' => 'bg-emerald-100',
        'text' => 'text-emerald-600',
    ],

    'red' => [
        'bg' => 'bg-red-100',
        'text' => 'text-red-600',
    ],

    'amber' => [
        'bg' => 'bg-amber-100',
        'text' => 'text-amber-600',
    ],

    'sky' => [
        'bg' => 'bg-sky-100',
        'text' => 'text-sky-600',
    ],

    'violet' => [
        'bg' => 'bg-violet-100',
        'text' => 'text-violet-600',
    ],

    'indigo' => [
        'bg' => 'bg-indigo-100',
        'text' => 'text-indigo-600',
    ],

    'slate' => [
        'bg' => 'bg-slate-100',
        'text' => 'text-slate-600',
    ],

];

$style = $colors[$color] ?? $colors['blue'];

@endphp

<div
    class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-lg transition duration-300">

    <div class="flex justify-between items-start">

        <div>

            <p class="text-sm text-slate-500">

                {{ $titulo }}

            </p>

            <h2 class="mt-2 text-4xl font-black {{ $style['text'] }}">

                {{ $valor }}

            </h2>

            @if($descripcion)

                <p class="mt-2 text-sm text-slate-400">

                    {{ $descripcion }}

                </p>

            @endif

        </div>

        <div
            class="w-14 h-14 rounded-2xl {{ $style['bg'] }} flex items-center justify-center text-2xl">

            {{ $icono }}

        </div>

    </div>

</div>
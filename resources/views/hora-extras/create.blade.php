@extends('layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Questrial&display=swap');

    .font-century {
        font-family: 'Century Gothic', CenturyGothic, 'Century Gothic Paneuropean',
                     Questrial, 'Avenir Next', sans-serif;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="font-century min-h-screen bg-gradient-to-br from-[#FBF8F3] to-[#F3EDE3] py-8">

    <div class="max-w-6xl mx-auto px-6">

        {{-- BREADCRUMB / BACK --}}
        <div class="mb-6 opacity-0" style="animation: fadeUp .5s ease forwards;">
            <a href="{{ route('hora-extras.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-[#6E6274] hover:text-[#6A2C75] transition-colors duration-200">
                <span aria-hidden="true">←</span> Volver a Solicitudes
            </a>
        </div>

        <div class="relative overflow-hidden rounded-2xl bg-white shadow-sm border border-[#2B2030]/10 opacity-0" style="animation: fadeUp .6s .1s ease forwards;">

            {{-- gold hairline --}}
            <div class="h-[3px] bg-gradient-to-r from-[#B6A644] via-[#6A2C75] to-[#B6A644]"></div>

            {{-- HEADER --}}
            <div class="px-6 py-4 border-b border-[#2B2030]/10 flex items-center gap-4">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#6A2C75] to-[#45193F] flex items-center justify-center text-xl shrink-0">
                    ⏰
                </div>
                <div>
                    <p class="uppercase tracking-[0.25em] text-[#6A2C75]/60 text-xs font-semibold mb-1">
                        Gestión de Personal
                    </p>
                    <h1 class="text-2xl font-bold text-[#2B2030]">
                        Nueva Solicitud de Horas Extra
                    </h1>
                </div>
            </div>

            {{-- FORM --}}
            <form
                action="{{ route('hora-extras.store') }}"
                method="POST">

                @csrf

                @include('hora-extras._form')

            </form>

        </div>

    </div>

</div>

@endsection
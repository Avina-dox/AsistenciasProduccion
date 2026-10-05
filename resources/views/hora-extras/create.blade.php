@extends('layouts.app')

@section('content')

<div class="relative bg-background min-h-screen">
    <div class="fixed top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-gradient-to-br from-primary-fixed-dim/20 to-transparent blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-[500px] h-[450px] rounded-full bg-gradient-to-tr from-primary-container/10 via-surface-variant/30 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <div class="relative w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- BREADCRUMB / BACK --}}
        <div>
            <a href="{{ route('hora-extras.index') }}"
                class="inline-flex items-center gap-2 font-label-md text-label-md font-semibold text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Volver a Solicitudes
            </a>
        </div>

        <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest/85 backdrop-blur-2xl shadow-[0_10px_30px_rgba(74,30,82,0.04),inset_0_1px_2px_rgba(255,255,255,0.95)]">

            {{-- HEADER --}}
            <div class="px-6 sm:px-8 py-6 border-b border-outline-variant/40 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-container flex items-center justify-center shrink-0 shadow-[0_8px_20px_rgba(74,30,82,0.22)]">
                    <span class="material-symbols-outlined text-white">alarm_add</span>
                </div>
                <div>
                    <p class="font-label-caps text-label-caps uppercase tracking-wider text-outline font-semibold mb-1">Gestión de Personal</p>
                    <h1 class="font-headline-md text-headline-md text-primary font-bold tracking-tight">
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

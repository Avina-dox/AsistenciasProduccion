@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-slate-100">

    <div class="max-w-7xl mx-auto px-6 py-8">

        {{-- HEADER --}}

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-700 via-purple-700 to-fuchsia-700 shadow-2xl">

            <div class="absolute right-0 top-0 opacity-10">

                <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 200 200">
                    <path d="M46,-73.5C59.2,-66.7,69.5,-53.8,76.5,-39.4C83.5,-25,87.3,-9.2,84.7,5.8C82.1,20.8,73.2,35.1,62.3,47.3C51.5,59.5,38.7,69.5,24.2,75.3C9.8,81,-6.3,82.5,-21.4,78.6C-36.5,74.7,-50.5,65.5,-60.6,53.4C-70.8,41.2,-77,26.1,-79.1,10.3C-81.3,-5.5,-79.4,-21.9,-72.7,-35.7C-66,-49.5,-54.5,-60.8,-41.1,-67.6C-27.8,-74.4,-13.9,-76.7,1.3,-78.8C16.4,-80.8,32.8,-82.4,46,-73.5Z"/>
                </svg>

            </div>

            <div class="relative z-10 p-10">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="uppercase tracking-[0.30em] text-violet-200 text-xs font-semibold">

                            Sistema de Control de Asistencias

                        </p>

                        <h1 class="mt-3 text-4xl font-black text-white">

                            ¡Bienvenido,
                            {{ auth()->user()->name }}!

                        </h1>

                        <p class="mt-4 text-violet-100 text-lg">

                            Administra asistencias, permisos, horas extra y personal desde un solo lugar.

                        </p>

                    </div>

                    <div class="hidden lg:flex flex-col items-end">

                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl px-8 py-6 border border-white/20">

                            <p class="text-violet-200 uppercase text-xs tracking-widest">

                                Hoy

                            </p>

                            <h2 class="text-5xl font-black text-white">

                                {{ now()->format('d') }}

                            </h2>

                            <p class="text-violet-100 text-lg">

                                {{ now()->translatedFormat('F Y') }}

                            </p>

                            <p class="text-violet-300 mt-3">

                                {{ now()->translatedFormat('l') }}

                            </p>

                        </div>

                    </div>

                </div>

                <div class="mt-10 flex flex-wrap gap-4">

                    <a
                        href="{{ route('asistencias.index') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-white px-6 py-3 font-semibold text-violet-700 shadow hover:scale-105 transition">

                        📅 Registrar Asistencia

                    </a>

                    <a
                        href="{{ route('hora-extras.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-amber-400 px-6 py-3 font-semibold text-slate-900 shadow hover:scale-105 transition">

                        ⏰ Nueva Hora Extra

                    </a>

                    <a
                        href="{{ route('empleados.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-emerald-400 px-6 py-3 font-semibold text-slate-900 shadow hover:scale-105 transition">

                        👥 Nuevo Empleado

                    </a>

                    <a
                        href="{{ route('usuarios.create') }}"
                        class="inline-flex items-center gap-3 rounded-xl bg-sky-400 px-6 py-3 font-semibold text-slate-900 shadow hover:scale-105 transition">

                        👤 Nuevo Usuario

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto py-8">

    <div class="bg-white rounded-xl shadow">

        <div class="border-b px-6 py-4">

            <h1 class="text-2xl font-bold">

                Nueva Solicitud de Horas Extra

            </h1>

        </div>

        <form
            action="{{ route('hora-extras.store') }}"
            method="POST">

            @csrf

            @include('hora-extras._form')

        </form>

    </div>

</div>

@endsection
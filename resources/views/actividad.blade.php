@extends('layouts.app')

@section('content')
<div class="p-6 bg-white shadow rounded-lg max-w-5xl mx-auto mt-10">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Actividad</h1>
    <p class="text-gray-600 mb-2">Bienvenido, {{ $user->name }} ({{ $user->agente->Linea ?? 'No asignada' }})</p>

    <div class="text-gray-500">
        Aquí podrás consultar tu historial de movimientos y cálculos realizados.
    </div>
</div>
@endsection

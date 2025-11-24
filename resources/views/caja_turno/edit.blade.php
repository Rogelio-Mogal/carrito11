@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Caja-Turno',
        'url' => route('admin.caja.turno.index')
    ],
    [
        'name' => $caja->tipo
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.caja.turno.update', $caja->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('caja_turno._form')
        </form>
    </div>
@stop
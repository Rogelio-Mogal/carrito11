@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Asignar gasto',
        'url' => route('admin.asignar.gasto.index')
    ],
    [
        'name' => $asignarGasto->id
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.asignar.gasto.update', $asignarGasto->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('asignar_gasto._form')
        </form>
    </div>
@stop
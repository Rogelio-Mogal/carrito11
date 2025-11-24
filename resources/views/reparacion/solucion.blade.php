@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Reparaciones / Solución',
        'url' => route('admin.reparacion.index')
    ],
    [
        'name' => $reparacion->folio
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.reparacion.update', $reparacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('reparacion._form')
        </form>
    </div>
@stop
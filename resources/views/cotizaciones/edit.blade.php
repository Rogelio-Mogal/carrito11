@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Cotizaciones',
        'url' => route('admin.cotizacion.index')
    ],
    [
        'name' => $cotizacion->clienteDocumento->full_name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.cotizacion.update', $cotizacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('cotizaciones._form')
        </form>
    </div>
@stop
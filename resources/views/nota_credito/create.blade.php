@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ventas',
        'url' => route('admin.ventas.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.ventas.store') }}" method="POST" id="formulario-venta" name="formulario-venta">
            @csrf
            @include('ventas._form')
        </form>
    </div>
@stop
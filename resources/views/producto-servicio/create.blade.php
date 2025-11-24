@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Producto/Servicio',
        'url' => route('admin.producto.servicio.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.producto.servicio.store') }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @include('producto-servicio._form')
        </form>
    </div>
@stop
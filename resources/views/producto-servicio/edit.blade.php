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
        'name' => $productoServicio->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.producto.servicio.update', $productoServicio->id) }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('producto-servicio._form')
        </form>
    </div>
@stop
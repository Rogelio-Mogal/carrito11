@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Producto caracteristica',
        'url' => route('admin.producto.caracteristica.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.producto.caracteristica.store') }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @include('producto_caracteristica._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Inventario',
        'url' => route('admin.inventario.index')
    ],
    [
        'name' => $inventario->producto->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.inventario.update', $inventario->id) }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('inventario._form')
        </form>
    </div>
@stop
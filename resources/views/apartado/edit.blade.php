@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Inventario',
        'url' => route('admin.apartado.index')
    ],
    [
        'name' => $inventario->producto->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.apartado.update', $inventario->id) }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('apartado._form')
        </form>
    </div>
@stop
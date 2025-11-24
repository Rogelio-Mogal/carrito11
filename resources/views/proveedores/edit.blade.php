@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Proveedores',
        'url' => route('admin.proveedores.index')
    ],
    [
        'name' => $proveedor->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.proveedores.update', $proveedor->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('proveedores._form')
        </form>
    </div>
@stop
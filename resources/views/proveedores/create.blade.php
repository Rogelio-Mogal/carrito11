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
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.proveedores.store') }}" method="POST">
            @csrf
            @include('proveedores._form')
        </form>
    </div>
@stop
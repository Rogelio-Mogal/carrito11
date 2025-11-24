@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Sucursales',
        'url' => route('admin.sucursales.index')
    ],
    [
        'name' => $sucursales->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.sucursales.update', $sucursales->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('sucursales._form')
        </form>
    </div>
@stop
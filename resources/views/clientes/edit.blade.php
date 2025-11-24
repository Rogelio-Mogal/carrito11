@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Clientes',
        'url' => route('admin.clientes.index')
    ],
    [
        'name' => $cliente->full_name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('clientes._form')
        </form>
    </div>
@stop
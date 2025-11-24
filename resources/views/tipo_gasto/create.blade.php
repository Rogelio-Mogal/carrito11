@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Tipo de gasto',
        'url' => route('admin.tipo.gasto.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.tipo.gasto.store') }}" method="POST">
            @csrf
            @include('tipo_gasto._form')
        </form>
    </div>
@stop
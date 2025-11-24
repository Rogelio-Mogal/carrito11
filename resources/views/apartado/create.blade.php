@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Apartado',
        'url' => route('admin.apartado.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.apartado.store') }}" method="POST" id="formulario-apartado">
            @csrf
            @include('apartado._form')
        </form>
    </div>
@stop
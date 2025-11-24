@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Familia-Atrubitos',
        'url' => route('admin.familia.atributos.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.familia.atributos.store') }}" method="POST">
            @csrf
            @include('familia_atributo._form')
        </form>
    </div>
@stop
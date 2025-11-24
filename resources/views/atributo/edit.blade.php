@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Atributos',
        'url' => route('admin.atributos.index')
    ],
    [
        'name' => $atributo->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.atributos.update', $atributo->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('atributo._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Familia-Atributos',
        'url' => route('admin.familia.atributos.index')
    ],
    [
        'name' => $famAtributo->familia_id
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.familia.atributos.update', $familiaAtributo->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('familia_atributo._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Anticipo',
        'url' => route('admin.anticipo.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.anticipo.store') }}" method="POST">
            @csrf
            @include('anticipo._form')
        </form>
    </div>
    @include('garantias.partials._modal_cliente')
@stop
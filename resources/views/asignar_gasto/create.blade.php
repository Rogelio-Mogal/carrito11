@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Asignar gasto',
        'url' => route('admin.asignar.gasto.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.asignar.gasto.store') }}" method="POST">
            @csrf
            @include('asignar_gasto._form')
        </form>
    </div>
@stop
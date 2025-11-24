@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Caja',
        'url' => route('admin.caja.movimiento.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.caja.movimiento.store') }}" method="POST">
            @csrf
            @include('caja._form')
        </form>
    </div>
@stop
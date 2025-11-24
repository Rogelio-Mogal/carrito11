@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Reparaciones',
        'url' => route('admin.reparacion.index')
    ],
    [
        'name' => 'Nueva '
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.reparacion.store') }}" method="POST">
            @csrf
			@include('reparacion._form')
        </form>
    </div>
    @include('reparacion.partials._modal_cliente')
@stop
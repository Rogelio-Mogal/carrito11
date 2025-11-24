@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Nota de venta',
        'url' => route('admin.nota.venta.index')
    ],
    [
        'name' => 'Nueva '
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.nota.venta.store') }}" method="POST">
            @csrf
			@include('nota_venta._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ventas',
        'url' => route('admin.ventas.index')
    ],
    [
        'name' => $ventas->folio
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.ventas.update', $ventas->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('ventas._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Forma de pago',
        'url' => route('admin.forma.pago.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.forma.pago.store') }}" method="POST">
            @csrf
            @include('forma_pago._form')
        </form>
    </div>
@stop
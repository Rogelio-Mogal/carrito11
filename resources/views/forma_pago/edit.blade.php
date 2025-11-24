@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Forma pago',
        'url' => route('admin.forma.pago.index')
    ],
    [
        'name' => $formapago->forma_pago
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.forma.pago.update', $formapago->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('forma_pago._form')
        </form>
    </div>
@stop
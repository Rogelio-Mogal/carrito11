@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Gastos',
        'url' => route('admin.gastos.index')
    ],
    [
        'name' => $gasto->gasto
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.gastos.update', $gasto->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('gasto._form')
        </form>
    </div>
@stop
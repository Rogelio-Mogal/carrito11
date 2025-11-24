@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Precios',
        'url' => route('admin.precios.index')
    ],
    [
        'name' => $user->name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.precios.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('precios._form')
        </form>
    </div>
@stop
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Perfi',
        'url' => route('admin.perfil.edit', ['perfil' => auth()->id()])
    ],
    [
        'name' => $perfil->name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.perfil.update', $perfil->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users-profile._form')
        </form>
    </div>
@stop
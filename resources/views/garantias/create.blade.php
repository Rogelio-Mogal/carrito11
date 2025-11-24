@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Garantías',
        'url' => route('admin.garantias.index')
    ],
    [
        'name' => 'Nueva '
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.garantias.store') }}" method="POST">
            @csrf
			@include('garantias._form')
        </form>
    </div>
    @include('garantias.partials._modal_cliente')
@stop
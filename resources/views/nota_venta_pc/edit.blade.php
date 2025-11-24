@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Nota de venta PC',
        'url' => route('admin.nota.pc.venta.index')
    ],
    [
        'name' => $user->name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.nota.pc.venta.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('nota_venta_pc._form')
        </form>
    </div>
@stop
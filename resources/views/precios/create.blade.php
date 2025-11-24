@php
    if($tipoPrecio == 1){
        $nombreTipoPrecio = 'precio general';
    }
    if($tipoPrecio == 2){
        $nombreTipoPrecio = 'precio específico';
    }

@endphp
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
        'name' => 'Nuevo ' . $nombreTipoPrecio
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.precios.store') }}" method="POST">
            @csrf
            @if($tipoPrecio == 1)
			    @include('precios._form_general')
            @endif
            @if($tipoPrecio == 2)
                @include('precios._form_especifico')
            @endif
        </form>
    </div>
@stop
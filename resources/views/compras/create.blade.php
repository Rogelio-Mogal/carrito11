@php
    if($tipoCompra == 1){
        $nombreTipoPrecio = 'compra interna';
    }
    if($tipoCompra == 2){
        $nombreTipoPrecio = 'compra web';
    }

@endphp
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Compras',
        'url' => route('admin.compras.index')
    ],
    [
        'name' => 'Nueva ' . $nombreTipoPrecio
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.compras.store') }}" method="POST">
            @csrf
            @if($tipoCompra == 1)
			    @include('compras._form_interna')
            @endif
            @if($tipoCompra == 2)
                @include('compras._form_web')
            @endif
        </form>
    </div>
@stop
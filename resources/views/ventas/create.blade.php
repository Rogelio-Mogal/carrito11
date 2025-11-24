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
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <div class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-2 mb-2 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-2">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                <div>
                    <span class="text-gray-600 dark:text-gray-300 font-semibold">Inicio de turno:</span>
                    <span class="text-gray-800 dark:text-gray-100">
                        {{ $turnoAbierto->fecha_apertura->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-600 dark:text-gray-300 font-semibold">Total Efectivo:</span>
                    <span class="text-green-600 font-bold">
                        ${{ number_format($totalEfectivo, 2) }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('admin.caja.turno.create') }}"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-md focus:ring-2 focus:ring-red-500 inline-block">
                        Cerrar Turno
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.ventas.store') }}" method="POST" id="formulario-venta" name="formulario-venta">
            @csrf
            @include('ventas._form')
        </form>
    </div>
@stop
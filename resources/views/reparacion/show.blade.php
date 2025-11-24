@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Reparaciones',
            'url' => route('admin.reparacion.index'),
        ],
        [
            'name' => $cotizacion->clienteDocumento->full_name,
        ],
    ],
])

@section('css')

@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
            @if ($cotizacion->activo == 0)
                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 flex justify-center items-center">
                    <span class="text-red-600 text-3xl font-bold">REGISTRO ELIMINADO</span>
                </div>
            @endif
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Fecha
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $cotizacion->fecha }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Cliente
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $cotizacion->cliente ? $cotizacion->cliente : optional($cotizacion->clienteDocumento)->full_name }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Dirección
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $cotizacion->direccion ? $cotizacion->direccion : optional($cotizacion->clienteDocumento)->direccion }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Total
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ '$' . number_format($cotizacion->total, 2, '.', ',') }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Estado
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $cotizacion->estado }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTOS COTIZADOS</h4>
            </div>
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <table id="cotizacion_detalles" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cantidad</th>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach ($cotizacion->detallesDocumentos as $detalle)
                                <tr>
                                    <td> {{ $detalle->id }} </td>
                                    <td> {{ $detalle->cantidad }} </td>
                                    <td> {{ $detalle->productoDocumento->nombre }} </td>
                                    <td> {{ '$' . number_format($detalle->precio, 2, '.', ',') }} </td>
                                    <td> {{ '$' . number_format($detalle->importe, 2, '.', ',') }} </td>
                                </tr>
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var rolesTable = new DataTable('#cotizacion_detalles', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
            });
        });
    </script>
@stop

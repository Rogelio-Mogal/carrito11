@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Cotizaciones',
            'url' => route('admin.cotizacion.index'),
        ],
        [
            'name' => $compra[0]->num_factura,
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
            @if ($compra[0]->activo == 0)
                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 flex justify-center items-center">
                    <span class="text-red-600 text-3xl font-bold">REGISTRO ELIMINADO</span>
                </div>
            @endif
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">N°
                    factura</label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $compra[0]->num_factura }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Proveedor
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ $compra[0]->proveedor->proveedor }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Fecha de captura
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ Carbon::parse($compra[0]->fecha_captura)->format('d/m/Y H:i:s') }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Fecha de compra
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ Carbon::parse($compra[0]->fecha_compra)->format('d/m/Y H:i:s') }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Total
                </label>
                <p
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    {{ '$' . number_format($compra[0]->total, 2, '.', ',') }}
                </p>
            </div>
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTOS COMPRADOS</h4>
            </div>
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <table id="compras_detalles" class="table table-striped" style="width:100%">
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
                        @foreach ($compra as $item)
                            @foreach ($item->compraDetalles as $detalle)
                                <tr>
                                    <td> {{ $detalle->id }} </td>
                                    <td> {{ $detalle->cantidad }} </td>
                                    <td> {{ $detalle->producto->nombre }} </td>
                                    <td> {{ '$' . number_format($detalle->precio, 2, '.', ',') }} </td>
                                    <td> {{ '$' . number_format($detalle->importe, 2, '.', ',') }} </td>
                                </tr>
                            @endforeach
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
            var rolesTable = new DataTable('#compras_detalles', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
            });
        });
    </script>
@stop

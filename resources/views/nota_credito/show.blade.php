@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Venta',
            'url' => route('admin.ventas.index'),
        ],
        [
            'name' => 'name',
            //'name' => $venta->folio,
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
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 lg:col-span-12 space-y-2">
                <!-- Aquí van tus controles y la tabla -->
                <div class="grid grid-cols-2 gap-4 mb-3 border-b pb-1">
                    <h3 class="font-bold text-purple-600">Nota de Crédito #{{ $notaCredito->id }}
                        (${{ number_format($totalDevoluciones, 2) }})</h3>
                    {{--<h3 class="font-bold text-purple-600 text-right">Venta origen: {{ $venta->folio }}</h3>--}}
                    <h3 class="font-bold text-purple-600 text-right">
                        @if($venta)
                            Venta origen: {{ $venta->folio }}
                        @elseif($garantia)
                            Garantía origen: {{ $garantia->folio }}
                        @endif
                    </h3>
                </div>
                <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <!-- ##### MODULO DE PRODUCTOS-PONCHADOS  #########   -->

                    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                        <div class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
                            <div
                                class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                                <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                                    <div
                                        class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[400px] overflow-y-auto">
                                        @php
                                            $hoy = now()->toDateString();
                                            //$fechaVenta = \Carbon\Carbon::parse($venta->fecha)->toDateString();
                                            $fechaVenta = $venta 
                                            ? \Carbon\Carbon::parse($venta->fecha)->toDateString() 
                                            : ($garantia 
                                                ? \Carbon\Carbon::parse($garantia->created_at)->toDateString() 
                                                : null);
                                        @endphp
                                        <table id="item_table_0"
                                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead
                                                class="text-xs text-blue-700 uppercase bg-blue-50 dark:bg-blue-700 dark:text-blue-400">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3">Cant.</th>
                                                    <th scope="col" class="px-6 py-3">Producto</th>
                                                    <th scope="col" class="px-6 py-3">P.U.</th>
                                                    <th scope="col" class="px-6 py-3">Importe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{--
                                                @foreach ($devoluciones as $dev)
                                                    <tr>
                                                        <td>{{ $dev->cantidad }}</td>
                                                        <td>
                                                            {{ optional($dev->detalle->producto)->nombre ?? $dev->detalle->producto_comun }}
                                                        </td>
                                                        <td>${{ number_format(optional($dev->detalle)->precio ?? 0, 2) }}
                                                        </td>
                                                        <td>${{ number_format($dev->monto, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                                --}}
                                                @foreach ($devoluciones as $dev)
                                                    <tr class="text-center">
                                                        {{-- Cantidad --}}
                                                        <td>{{ $dev->detalle->cantidad ?? $dev->cantidad ?? 0 }}</td>

                                                        {{-- Producto --}}
                                                        <td>
                                                            {{ optional($dev->detalle->producto)->nombre 
                                                            ?? $dev->detalle->producto_comun 
                                                            ?? $dev->detalle->producto?->nombre 
                                                            ?? 'N/A' }}
                                                        </td>

                                                        {{-- Precio unitario --}}
                                                        <td>
                                                            ${{ number_format(optional($dev->detalle)->precio 
                                                                ?? $dev->detalle->precio_producto 
                                                                ?? $dev->detalle->producto?->precio_producto 
                                                                ?? 0, 2) }}
                                                        </td>

                                                        {{-- Importe --}}
                                                        <td>${{ number_format($dev->monto, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                        <h3 class="font-bold text-blue-600">Estado de la Nota</h3>
                        @if ($notaCredito->activo)
                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded">Disponible</span>
                        @else
                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded">Aplicada</span>

                            @if ($ventasAplicadas->count())
                                <h4 class="mt-3 font-bold text-purple-600">Aplicada en las siguiente venta:</h4>
                                <table class="w-full text-sm text-left text-gray-500 mt-2">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2">Folio</th>
                                            <th class="px-4 py-2">Cliente</th>
                                            <th class="px-4 py-2">Fecha</th>
                                            <th class="px-4 py-2">Monto aplicado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notaCredito->devoluciones as $devolucion)
                                            @if ($devolucion->ventaAplicada)
                                                <tr>
                                                    <td class="px-4 py-2">
                                                        <a href="{{ route('ticket.venta', ['id' => $devolucion->ventaAplicada->id]) }}"
                                                            target="_blank" class="text-blue-500 underline">
                                                            {{ $devolucion->ventaAplicada->folio }}
                                                        </a>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{ $devolucion->ventaAplicada->cliente->full_name ?? 'SIN CLIENTE' }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{ $devolucion->ventaAplicada->fecha->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        ${{ number_format($totalDevoluciones, 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        @endif
                    </div>



                    <!-- ##### FIN MODULO DE PRODUCTOS-PONCHADOS  #########   -->
                </div>
            </div>

            <!-- Panel lateral (formas de pago) -->
            {{--
            <div class="col-span-12 lg:col-span-3 space-y-2">
                <!-- Paneles de forma de pago y total -->
                <div class="bg-white rounded-xl shadow p-4 space-y-6">

                    <!-- Formas de pago -->
                    <div>
                        <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @php
                                // Métodos de pago que quieres mostrar
                                $metodos = ['Efectivo', 'TDD', 'TDC', 'Transferencia'];
                                // Indexamos los pagos existentes por método
                                $pagosArray = $venta->pagos->keyBy('metodo');
                            @endphp
                            @foreach ($metodos as $index => $metodo)
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">{{ $metodo }}</label>
                                    <input type="hidden" name="formas_pago[{{ $index }}][metodo]" value="{{ $metodo }}">
                                    <input type="number" name="formas_pago[{{ $index }}][monto]"
                                        value="{{ isset($pagosArray[$metodo]) ? $pagosArray[$metodo]->monto : 0 }}"
                                        step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            @endforeach

                            <!-- Monto a crédito -->
                            <div id="monto_credito_container" class="sm:col-span-2">
                                <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Monto a crédito</label>
                                <input type="number" name="monto_credito" id="monto_credito" step="any"
                                    value="{{ $venta->monto_credito ?? 0 }}"
                                    class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                    min="0">
                            </div>
                        </div>

                    </div>

                    <!-- Totales -->
                    @php
                        // Sumar los pagos existentes
                        $totalPagos = $venta->pagos->sum('monto') + ($venta->monto_credito ?? 0);

                        // Total de la venta
                        $totalVenta = $venta->total;

                        // Adelanto = suma de pagos (efectivo + tarjeta + transferencia)
                        $adelanto = 0;

                        // Faltante
                        $faltante = 0;//max($totalVenta - $adelanto, 0);

                        // Cambio
                        $cambio = max($adelanto - $totalVenta, 0);
                    @endphp
                    <div>
                        <h3 class="font-bold text-blue-600 border-b pb-1 mb-3">Total / Cambio</h3>
                        <div class="space-y-3">
                            <div>
                                <label for="total" class="block mb-1 text-sm font-medium text-gray-700">Total</label>
                                <span id="total_mostrado" class="font-bold text-xl text-black-500 dark:text-black-400"> ${{ number_format($totalVenta, 2) }}</span>
                                <input type="hidden" id="total_venta" name="total_venta">
                            </div>
                            <div>
                                <label class="block text-xl font-medium text-gray-900">Adelanto</label>
                                <span id="adelanto_texto" class="text-green-600 font-bold">
                                ${{ number_format($adelanto, 2) }}
                                </span>
                                <input type="hidden" id="adelanto" name="adelanto" value="0">
                            </div>
                            <div class="sm:col-span-12 lg:col-span-3">
                                <label class="block text-xl font-medium text-gray-900">Faltante</label>
                                <span id="faltante_texto" class="text-red-600 font-bold">${{ number_format($faltante, 2) }}</span>
                                <input type="hidden" id="total_faltante" name="total_faltante">


                            </div>
                            <div class="sm:col-span-12 lg:col-span-3">
                                <label class="block text-xl font-medium text-gray-900">Cambio</label>
                                <span id="cambio_texto" class="text-green-600 font-bold">${{ number_format($cambio, 2) }}</span>
                                <input type="hidden" id="total_cambio" name="total_cambio">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            --}}
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

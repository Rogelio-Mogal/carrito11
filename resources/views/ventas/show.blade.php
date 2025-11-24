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
            'name' => $venta->folio,
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
            <div class="col-span-12 lg:col-span-9 space-y-2">
                <!-- Aquí van tus controles y la tabla -->
                <div class="grid grid-cols-2 gap-4 mb-3 border-b pb-1">
                    <h3 class="font-bold text-purple-600">Venta: {{ $venta->folio }}</h3>
                    <h3 class="font-bold text-purple-600 text-right">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}</h3>
                </div>
                <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo venta</label>
                        {{ $venta->tipo_venta }}
                    </div>



                    <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
                        <label for="nombre_cliente"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                        {{ $venta->cliente->full_name ?? 'SIN CLIENTE' }}
                    </div>


                    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                        <label for="tipo_cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Tipo de cliente
                        </label>
                        {{ $venta->cliente->tipo_cliente ?? 'SIN TIPO' }}
                    </div>


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
                                            $fechaVenta = \Carbon\Carbon::parse($venta->fecha)->toDateString();
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
                                                    <th scope="col" class="px-6 py-3">Devolver</th>
                                                    <th scope="col" class="px-6 py-3">Motivo</th>
                                                    <th scope="col" class="px-6 py-3">Detalle</th>
                                                    <th scope="col" class="px-6 py-3">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($venta->detalles as $detalle)
                                                    <tr>
                                                        <td>{{ $detalle->cantidad }}</td>
                                                        <td>{{ $detalle->producto ? $detalle->producto->nombre : $detalle->producto_comun }}
                                                        </td>
                                                        <td>{{ number_format($detalle->precio, 2) }}</td>
                                                        <td>{{ number_format($detalle->total, 2) }}</td>
                                                            @if ($detalle->tipo_item === 'PRODUCTO')
                                                                <form action="{{ route('admin.ventas.cancelarProducto', $detalle->id) }}" method="POST">
                                                                    @csrf
                                                                    <!-- Cantidad a devolver -->
                                                                    <td>
                                                                        <input type="number" name="cantidad" min="1" max="{{ $detalle->cantidad }}" value="1"
                                                                            class="w-16 border border-gray-300 rounded px-2 py-1 text-sm">
                                                                    </td>

                                                                    <!-- Motivo (radios + textarea en una sola línea) -->
                                                                    <td colspan="2">
                                                                        <div class="flex items-center gap-3">
                                                                            <!-- Radios -->
                                                                            <div class="flex items-center gap-2 text-xs">
                                                                                <label class="inline-flex items-center">
                                                                                    <input type="radio" name="tipo_cancelacion" value="devolucion" checked class="mr-1">
                                                                                    Devolución
                                                                                </label>
                                                                                <label class="inline-flex items-center">
                                                                                    <input type="radio" name="tipo_cancelacion" value="error" class="mr-1">
                                                                                    Error
                                                                                </label>
                                                                            </div>

                                                                            <!-- Detalle -->
                                                                            <textarea name="motivo_cancelacion" rows="1"
                                                                                class="flex-1 border border-gray-300 rounded px-2 py-1 text-xs"
                                                                                placeholder="Detalle"></textarea>
                                                                        </div>
                                                                    </td>

                                                                    <!-- Botón -->
                                                                    <td>
                                                                        <button type="submit"
                                                                            class="text-white bg-yellow-500 hover:bg-yellow-600 font-medium rounded-lg text-xs px-3 py-1.5">
                                                                            Devolver
                                                                        </button>
                                                                    </td>
                                                                </form>
                                                            @else
                                                                <td colspan="4" class="text-center text-gray-400">No aplica</td>
                                                            @endif
                                                       
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- ##### FIN MODULO DE PRODUCTOS-PONCHADOS  #########   -->
                </div>
            </div>

            <!-- Panel lateral (formas de pago) -->
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
                            @foreach($metodos as $index => $metodo)
                                <div>
                                    <label class="block mb-1 text-sm font-medium text-gray-700">{{ $metodo }}</label>
                                    <input type="hidden" name="formas_pago[{{ $index }}][metodo]" value="{{ $metodo }}">
                                    <input type="number" name="formas_pago[{{ $index }}][monto]"
                                        value="{{ isset($pagosArray[$metodo]) ? $pagosArray[$metodo]->monto : 0 }}"
                                        step="any"
                                        class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            @endforeach

                            {{-- Monto a crédito --}}
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

                            <form action="{{ route('admin.ventas.cancelarVenta', $venta->id) }}" method="POST" class="mt-4 p-3 border rounded bg-gray-50">
                                @csrf
                                <label class="block mb-1 font-semibold">Motivo de cancelación:</label>
                                <div class="flex items-center gap-4 mb-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="tipo_cancelacion" value="devolucion" checked class="mr-2">
                                        Cambio / Devolución
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="tipo_cancelacion" value="error" class="mr-2">
                                        Por Error
                                    </label>
                                </div>
                                <textarea name="motivo_cancelacion" placeholder="Escribe el motivo"
                                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm mb-2" rows="3"></textarea>
                                
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                                    Cancelar Venta
                                </button>
                            </form>
                            



                        </div>
                    </div>
                </div>
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

    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-nota-credito') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop

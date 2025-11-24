@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Apartado',
            'url' => route('admin.apartado.index'),
        ],
        [
            'name' => 'name',
            'name' => $cliente->full_name,
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

                <!-- Cliente -->
                <div class="grid grid-cols-2 gap-4 mb-3 border-b pb-1">
                    <h3 class="font-bold text-purple-600">Cliente: {{ $cliente->full_name }}</h3>
                </div>

                <!-- Módulo de Abono -->
                <div class="mb-4">
                    <h3 class="font-bold text-blue-600">REGISTRAR ABONO -ANTICIPO-</h3>

                    <form id="form-abono" method="POST" action="{{ route('admin.apartado.abono.store') }}"
                        class="grid grid-cols-12 gap-2">
                        @csrf
                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}">

                        <!-- Tipo de abono -->
                        <div class="sm:col-span-12 md:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-sm mb-2">Tipo de abono:</label>
                            <select name="tipo_abono" id="tipo_abono" class="form-select w-full">
                                <option value="anticipo">Por anticipo</option>
                                <option value="monto">Por monto</option>
                            </select>
                            <!-- Leyenda debajo del select -->
                            <p id="abono-global-leyenda" class="text-sm text-gray-500 dark:text-gray-300 mt-1 hidden">
                                El abono se aplicará de la más antigua a la más reciente.
                            </p>
                        </div>

                        <!-- Abono por venta -->
                        <div id="abono-por-venta" class="sm:col-span-12 md:col-span-5 lg:col-span-5">
                            <label class="block font-semibold text-sm mb-2">Seleccionar anticipo:</label>
                            <select name="anticipo_id" class="form-select w-full">
                                @foreach ($anticipos as $vc)
                                    <option value="{{ $vc->id }}">
                                        Anticipo: {{ $vc->folio }} -
                                        Monto: ${{ number_format($vc->debia, 2) }} -
                                        Saldo: ${{ number_format($vc->debe, 2) }} -
                                        {{ $vc->created_at->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Referencia -->
                        <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
                            <label for="referencia"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Referencia</label>
                            <input type="text" id="referencia" name="referencia"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Rerefencia (Opcional)" value="" />
                        </div>

                        <!-- Formas de pago -->
                        <div class="sm:col-span-12 md:col-span-12 lg:col-span-12">
                            <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
                            <div class="grid grid-cols-2 gap-4">
                                @php
                                    $metodos = ['Efectivo', 'TDD', 'TDC', 'Transferencia'];
                                @endphp
                                @foreach ($metodos as $index => $metodo)
                                    <div>
                                        <label
                                            class="block mb-2 text-sm font-medium text-gray-700">{{ $metodo }}</label>
                                        <input type="hidden" name="formas_pago[{{ $index }}][metodo]"
                                            value="{{ $metodo }}">
                                        <input type="number" name="formas_pago[{{ $index }}][monto]" step="any"
                                            class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="sm:col-span-12 md:col-span-2 lg:col-span-2">
                            <label class="block font-semibold text-sm mb-2">&nbsp;</label>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                Registrar Abono
                            </button>
                        </div>
                    </form>

                </div>

                <!-- Listado de Ventas a Crédito Pendientes -->
                <div class="mb-4">
                    <h3 class="font-bold text-blue-600">LISTADO DE ANTICIPOS (Pendientes)</h3>
                    <table id="ventas-credito" class="table table-striped w-full">
                        <thead>
                            <tr>
                                <th>Venta</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Saldo Actual</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($anticipos as $vc)
                                <tr>
                                    <td>{{ $vc->folio ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($vc->fecha)->format('d/m/Y H:i:s') ?? '-' }}</td>
                                    <td>${{ number_format($vc->debia, 2) }}</td>
                                    <td>${{ number_format($vc->debe, 2) }}</td>
                                    <td>
                                        <a href="{{ route('ticket.anticipo', ['id' => $vc->id]) }}" target="_blank"
                                        data-popover-target="ticket-venta{{ $vc->id }}" data-popover-placement="bottom"
                                        class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                                            </svg>
                                            <span class="sr-only">Ticket</span>
                                        </a>
                                        <div id="ticket-venta{{ $vc->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket</h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Listado de Abonos -->
                <div class="mb-4">
                    <h3 class="font-bold text-blue-600">ABONOS</h3>

                    <!-- Filtros -->
                    <div class="flex gap-2 mb-2">
                        <button class="filtro-abonos px-3 py-1 bg-gray-300 rounded" data-dias="30">Últimos 30 días</button>
                        <button class="filtro-abonos px-3 py-1 bg-gray-300 rounded" data-dias="90">Últimos 90 días</button>
                        <button class="filtro-abonos px-3 py-1 bg-gray-300 rounded" data-dias="365">Año actual</button>
                    </div>

                    <table id="tabla-abonos" class="table table-striped w-full">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Monto</th>
                                <th>Fecha</th>
                                <th>Referencia</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($abonos as $abono)
                                <tr>
                                    <td>{{ $abono->id }}</td>
                                    <td>${{ number_format($abono->monto, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($abono->fecha)->format('d/m/Y H:i:s') ?? '-' }}</td>
                                    <td>{{ $abono->referencia ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('ticket.abono', ['id' => $abono->id]) }}" target="_blank"
                                        data-popover-target="ticket-venta{{ $abono->id }}" data-popover-placement="bottom"
                                        class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                                            </svg>
                                            <span class="sr-only">Ticket</span>
                                        </a>
                                        <div id="ticket-venta{{ $abono->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket</h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Mostrar/ocultar campos de abono según tipo
        document.getElementById('tipo_abono').addEventListener('change', function() {
            const leyenda = document.getElementById('abono-global-leyenda');
            const abonoPorVenta = document.getElementById('abono-por-venta');

            if (this.value === 'monto') {
                leyenda.classList.remove('hidden'); // mostrar leyenda
                abonoPorVenta.classList.add('hidden'); // ocultar select de ventas
            } else {
                leyenda.classList.add('hidden'); // ocultar leyenda
                abonoPorVenta.classList.remove('hidden'); // mostrar select de ventas
            }
        });

        $(document).ready(function() {
            // Ventas a crédito
            $('#ventas-credito').DataTable({
                responsive: true,
                "order": [
                    [1, "desc"]
                ], // columna de fecha (index 3) descendente
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                }
            });

            // Abonos
            $('#tabla-abonos').DataTable({
                responsive: true,
                "order": [
                    [2, "desc"]
                ], // columna de fecha (index 2) descendente
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                }
            });
            // Confirmar antes de enviar abono
            $('#form-abono').on('submit', function(e) {
                e.preventDefault(); // detener envío

                Swal.fire({
                    title: 'Confirmar abono',
                    text: "¿Estás seguro de registrar este abono?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar',
                    cancelButtonText: 'Cancelar',
                    // Usar clases de Tailwind/Flowbite
                    customClass: {
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded',
                        actions: 'space-x-3'
                    },
                    buttonsStyling: false // importante para que se respeten las clases
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // enviar formulario
                    }
                });
            });
        });
    </script>

    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-abono') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop


@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ventas'
    ]
]])

@section('css')

@stop

@section('content')
    @section('action')
        <a href="{{ route('admin.ventas.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
    @endsection
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <table id="venta" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Venta</th>
                            <th>Total</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $item->folio }}</td>
                                <td>{{ $item->cliente ? $item->cliente->full_name : 'SIN CLIENTE' }}</td>
                                <td>{{ $item->tipo_venta }}</td>
                                <td>{{ '$' . number_format($item->total, 2, '.', ',') }}</td>
                                {{--<td>{{ $item['fecha_estimada_entrega'] }}</td>--}}
                                
                                {{--
                                <td> {{ '$' . number_format($item->total, 2, '.', ',') }} </td>
                                <td>
                                    @if( $item->activo == 0 )
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>
                                    @endif
                                    @if( $item->activo == 1 )
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                                    @endif
                                </td>
                                --}}
                                <td>
                                    <a href="{{ route('ticket.venta', ['id' => $item->id]) }}" target="_blank"
                                    data-popover-target="ticket-venta{{ $item->id }}" data-popover-placement="bottom"
                                    class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                        <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                                        </svg>
                                        <span class="sr-only">Ticket de venta</span>
                                    </a>
                                    <div id="ticket-venta{{ $item->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-2 space-y-2">
                                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket de venta</h6>
                                        </div>
                                    </div>

                                    @if ($item->activo == 1)
                                     
                                        <!-- Botón para pasar a venta (solo para pedidos finalizados) -->
                                        @if($item->tipo == 'pedido' && $item->estatus == 'Finalizado')
                                            <a href="{{ route('admin.ventas.create', ['referencia_cliente' => $item->referencia_cliente]) }}"
                                                data-id="{{ $item->id }}"
                                                data-popover-target="pasar-venta{{ $item->id }}" data-popover-placement="bottom"
                                                class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z"/>
                                                </svg>
                                                <span class="sr-only">Pasar a venta</span>
                                            </a>
                                            <div id="pasar-venta{{ $item->id }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                                <div class="p-2 space-y-2">
                                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Pasar a venta</h6>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Botón para ticket (solo para ventas) -->
                                        @if($item->tipo == 'venta')
                                            <a href="{{ route('ticket.venta', ['id' => $item->id]) }}" target="_blank"
                                            data-popover-target="ticket-venta{{ $item->id }}" data-popover-placement="bottom"
                                            class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
                                                <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z"/>
                                                </svg>
                                                <span class="sr-only">Ticket de venta</span>
                                            </a>
                                            <div id="ticket-venta{{ $item->id }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                                <div class="p-2 space-y-2">
                                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket de venta</h6>
                                                </div>
                                            </div>
                                        @endif

                                        {{--
                                        <a href="{{ route('admin.ventas.create', ['referencia_cliente' => $item->referencia_cliente]) }}" 
                                        class="text-white bg-green-600 hover:bg-green-700 px-3 py-1 rounded">Crear venta</a>

                                        <a href="{{ route('admin.ventas.edit', $item->id) }}"
                                            data-id="{{ $item->id }}"
                                            data-popover-target="editar{{ $item->id }}" data-popover-placement="bottom"
                                            class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                            </svg>
                                            <span class="sr-only">Editar</span>
                                        </a>
                                        --}}

                                        


                                        <a href="{{ route('admin.ventas.show', $item->id) }}"
                                            data-id="{{ $item->id }}"
                                            data-popover-target="cancela{{ $item->id }}" data-popover-placement="bottom"
                                            class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span class="sr-only">Cancela venta</span>
                                        </a>
                                        <div id="cancela{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cancela venta</h6>
                                            </div>
                                        </div>

                                        
                                    @else
                                        <a href="#"
                                            data-popover-target="eliminado{{ $item->id }}" data-popover-placement="bottom"
                                            data-id="{{ $item->id }}"
                                            class="mb-1 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                                                                          
                                            <span class="sr-only">Activar</span>
                                        </a>
                                        <div id="eliminado{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminado</h6>
                                            </div>
                                        </div>
                                    @endif
                                </td>
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
            var VentaTable = new DataTable('#venta', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
            });
            //  Re-inicializa Flowbite cada vez que DataTables repinta
            VentaTable.on('draw', function () {
                if (typeof window.initFlowbite === "function") {
                    window.initFlowbite();
                }
            });

            // Manejar el clic en la opción "Eliminar"
            $('#venta').on('click', '.delete-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: 'No podrás revertir esto',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.ventas.destroy', ':id') }}"
                                .replace(':id', id),
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE"
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                });
            });

            // Manejar el clic en la opción "Avtivar"
            $('#venta').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'El proveedor está deshabilitada',
                    text: '¿Está seguro de activar al proveedor?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, activar'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.ventas.update', ':id') }}"
                                .replace(':id', id),
                            type: 'PUT',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "PUT",
                                "activa" : 1
                            },
                            success: function(data) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            },
                            error: function(xhr, status, error) {
                                //console.error(xhr.responseText);
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    }
                });
            });
        });
    </script>
    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-venta') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop


@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Clientes'
    ]
]])

@section('css')

@stop

@section('content')
    @section('action')
        <a href="{{ route('admin.clientes.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
    @endsection
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                 <div class="mb-4">
                    <button id="reloadTable"
                        class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Recargar Tabla
                    </button>
                </div>
                <table id="cliente" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>

                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Tipo</th>
                            <th>Ejecutivo</th>
                            <th>Estatus</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{--
                        @foreach ($clientes as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->full_name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->telefono }}</td>
                                <td>{{ $item->direccion }}</td>
                                <td>{{ $item->tipo_cliente }}</td>
                                <td>{{ $item->ejecutivo_id }}</td>
                                <td>
                                    @if( $item->activo == 0 )
                                        <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>
                                    @endif
                                    @if( $item->activo == 1 )
                                        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->activo == 1)
                                        <a href="{{ route('admin.clientes.edit', $item->id) }}"
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
                                        <a href="{{ route('admin.clientes.destroy', $item->id) }}"
                                            data-popover-target="eliminar{{ $item->id }}" data-popover-placement="bottom"
                                            data-id="{{ $item->id }}"
                                            class="delete-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span class="sr-only">Eliminar</span>
                                        </a>
                                        <div id="editar{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                            </div>
                                        </div>
                                        <div id="eliminar{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                                            </div>
                                        </div>
                                    @else
                                        <a href="#"
                                            data-popover-target="activar{{ $item->id }}" data-popover-placement="bottom"
                                            data-id="{{ $item->id }}"
                                            class="activa-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3"/>
                                            </svg>
                                            <span class="sr-only">Activar</span>
                                        </a>
                                        <div id="activar{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cambiar a activo</h6>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        --}}
                    </tbody>
                </table>
            </div>
        </div>

    </div>


@endsection

@section('js')
    <script>

        $(document).ready(function() {

            let tblGastos;
            cargarCliente();

            function cargarCliente() {

                if ($.fn.DataTable.isDataTable('#cliente')) {
                    $('#cliente').DataTable().clear().destroy();
                }

                // ORDENAR CANTIDADES CON FORMATO "$1,234.56"
                $.extend($.fn.dataTable.ext.type.order, {
                    "currency-mx-pre": function (data) {
                        if (!data) return 0;

                        // Elimina $, comas y espacios
                        return parseFloat(
                            data.replace('$', '').replace(/,/g, '').trim()
                        );
                    }
                });

                tblGastos = $('#cliente').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    order: [], // evita que intente ordenar automático
                    ajax: {
                        url: "{{ route('clientes.index.ajax') }}",
                        type: "POST",
                        data: function(d){
                            return $.extend(d, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                origen: "clientes.index",
                            });
                        }
                    },
                    columns: [
                        { data: 'id'},
                        { data: 'full_name'},
                        { data: 'email' },
                        { data: 'telefono' },
                        { data: 'direccion' },
                        { data: 'tipo_cliente' },
                        { data: 'ejecutivo_id' },
                        { data: 'es_activo' },
                        { data: 'acciones', render: function(data){
                            return $('<div/>').html(data).text();
                        }}
                    ],
                    language: { url: "{{ asset('/json/i18n/es_es.json') }}" }
                });

                //  Re-inicializa Flowbite cada vez que DataTables repinta
                tblGastos.on('draw', function () {
                    if (typeof window.initFlowbite === "function") {
                        window.initFlowbite();
                    }
                });
            }

            // 🔄 Botón de recargar
            $("#reloadTable").on("click", function() {
                cargarCliente();
            });

            // Manejar el clic en la opción "Eliminar"
            $('#cliente').on('click', '.delete-item', function(e) {
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
                            url: "{{ route('admin.clientes.destroy', ':id') }}"
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
            $('#cliente').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'El cliente está deshabilitada',
                    text: '¿Está seguro de activar al cliente?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, activar'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        var showUrl = "{{ route('admin.clientes.update', ':id') }}";
                        var showLink = showUrl.replace(':id', id);
                        $.ajax({
                            url: showLink,
                            type: 'PUT',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "PUT",
                                "activa" : 1
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: data.swal.icon,
                                    title: data.swal.title,
                                    text: data.swal.text,
                                    customClass: data.swal.customClass,
                                    buttonsStyling: data.swal.buttonsStyling
                                }).then(() => {
                                    // Después de que el usuario cierre el SweetAlert, recarga la página
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                //console.error(xhr.responseText);
                                if (xhr.status === 400) {
                                    var swalData = xhr.responseJSON.swal;
                                    Swal.fire({
                                        icon: swalData.icon,
                                        title: swalData.title,
                                        text: swalData.text,
                                        customClass: swalData.customClass,
                                        buttonsStyling: swalData.buttonsStyling
                                    });
                                } else {
                                    console.error(xhr.responseText);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop

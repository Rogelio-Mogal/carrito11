
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Sucursales'
    ]
]])

@section('css')

@stop

@section('content')
    @section('action')
        <a href="{{ route('admin.sucursales.create') }}"
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

                <!-- Mensaje de carga sobre la tabla -->
                <div id="loadingOverlay" class="absolute inset-0 flex items-center justify-center z-50 hidden">
                    <div class="relative flex items-center">
                        <!-- Contenedor para el texto de carga -->
                        <div class="text-white text-lg font-bold p-4 bg-gray-900 rounded flex items-center">
                            <svg aria-hidden="true"
                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />
                            </svg>
                            &nbsp;Procesando
                        </div>
                    </div>
                </div>

                <table id="TblSucursal" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sucursal</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Matriz</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {

            cargarSucursales();

            function cargarSucursales() {
                const postData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origen: "sucursal.index"
                };

                if ($.fn.DataTable.isDataTable('#TblSucursal')) {
                    $('#TblSucursal').DataTable().clear().destroy();
                }

                var ReparacionesTable = $('#TblSucursal').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    ajax: {
                        url: "{{ route('sucursal.index.ajax') }}",
                        type: "POST",
                        data: postData
                    },
                    columns: [
                        { data: 'id', visible: false },
                        { data: 'nombre' },
                        { data: 'direccion' },
                        { data: 'telefono' },
                        { data: 'es_matriz' },
                        { data: 'acciones', render: function(data){
                            return $('<div/>').html(data).text();
                        }}
                    ],
                    language: { url: "{{ asset('/json/i18n/es_es.json') }}" }
                });

                //  Re-inicializa Flowbite cada vez que DataTables repinta
                ReparacionesTable.on('draw', function () {
                    if (typeof window.initFlowbite === "function") {
                        window.initFlowbite();
                    }
                });
            }

            // 🔄 Botón de recargar
            $("#reloadTable").on("click", function() {
                cargarSucursales();
            });


            // Manejar el clic en la opción "Eliminar"
            $('#TblSucursal').on('click', '.delete-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: '¿Estás seguro de eliminar el registro?',
                    text: 'No podrás revertir esto',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5',
                        cancelButton: 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 ml-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.sucursales.destroy', ':id') }}"
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
            $('#TblSucursal').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'La sucursal está deshabilitada',
                    text: '¿Está seguro de activar la sucursal?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, activar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5',
                        cancelButton: 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 ml-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        $.ajax({
                            url: "{{ route('admin.sucursales.update', ':id') }}"
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
@stop

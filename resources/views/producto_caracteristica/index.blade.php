
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Producto caracteristica'
    ]
]])

@section('css')

@stop

@section('content')
    @section('action')
        <a href="{{ route('admin.producto.caracteristica.create') }}"
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
                <table id="caracteristica" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estatus</th>
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
        /* window.addEventListener('phx:page-loading-stop', (event) => {
                    // trigger flowbite events
                    window.document.dispatchEvent(new Event("DOMContentLoaded", {
                        bubbles: true,
                        cancelable: true
                    }));
                });
            */

        $(document).ready(function() {

            let tblCaracteristica;
            cargarCaracteristica();

            function cargarCaracteristica() {

                if ($.fn.DataTable.isDataTable('#caracteristica')) {
                    $('#caracteristica').DataTable().clear().destroy();
                }

                tblCaracteristica = $('#caracteristica').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    ajax: {
                        url: "{{ route('caracteristica.index.ajax') }}",
                        type: "POST",
                        data: function(d){
                            return $.extend(d, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                origen: "caracteristica.index",
                            });
                        }
                    },
                    columns: [
                        { data: 'id'},
                        {
                            data: 'image',
                            render: function(data){
                                return data; // No lo transformamos, DataTables lo insertará como HTML
                            }
                        },
                        { data: 'nombre' },
                        { data: 'tipo' },
                        { data: 'es_activo' },
                        { data: 'acciones', render: function(data){
                            return $('<div/>').html(data).text();
                        }}
                    ],
                    language: { url: "{{ asset('/json/i18n/es_es.json') }}" }
                });

                //  Re-inicializa Flowbite cada vez que DataTables repinta
                tblCaracteristica.on('draw', function () {
                    if (typeof window.initFlowbite === "function") {
                        window.initFlowbite();
                    }
                });
            }

            // Botón de recargar
            $("#reloadTable").on("click", function() {
                cargarCaracteristica();
            });

            // Manejar el clic en la opción "Eliminar"
            $('#caracteristica').on('click', '.delete-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: '¿Estás seguro?',
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
                            url: "{{ route('admin.producto.caracteristica.destroy', ':id') }}"
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
        });
    </script>
@stop

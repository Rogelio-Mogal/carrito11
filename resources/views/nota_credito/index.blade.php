@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Notas de créditos',
        ],
    ],
])

@section('css')

@stop

@section('content')
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

                <label for="activo_filter">Estatus:</label>
                <select id="activo_filter" class="form-select">
                    <option value="1" selected>Activas</option>
                    <option value="0">Inactivas</option>
                    <option value="">Todos</option>
                </select>
                <br/>

                <table id="nota-credito" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Estatus</th>
                            <th>Aplicar</th>
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

            var NotaCreditoTable = $('#nota-credito').DataTable({
                responsive: true,
                processing: true,
                serverSide: false, // todo el dataset en el frontend
                language: {
                    url: "{{ asset('/json/i18n/es_es.json') }}"
                },
                ajax: {
                    url: "{{ route('nota.credito.ajax') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.origen = "nota.credito.index";
                        d.activo = $('#activo_filter').val();
                    },
                    beforeSend: function() {
                        $("#loadingOverlay").removeClass("hidden");
                    },
                    complete: function() {
                        $("#loadingOverlay").addClass("hidden");
                    }
                },
                columns: [
                    { data: 'folio', name: 'folio' },
                    { data: 'fecha', name: 'fecha' },
                    { data: 'cliente_nombre', name: 'cliente_nombre' },
                    { 
                        data: 'total_monto', 
                        name: 'total_monto',
                        render: function(data) { return '$' + data; }
                    },
                    { data: 'estatus', name: 'estatus' },
                     { data: 'acciones', orderable: false, searchable: false }
                ]
            });

            //  Re-inicializa Flowbite cada vez que DataTables repinta
            NotaCreditoTable.on('draw', function () {
                if (typeof window.initFlowbite === "function") {
                    window.initFlowbite();
                }
            });


            // Botón de recarga
            $("#reloadTable").on("click", function() {
                 NotaCreditoTable.ajax.reload();
            });

            // Cambiar filtro automáticamente al seleccionar
            $("#activo_filter").on("change", function() {
                 NotaCreditoTable.ajax.reload();
            });

            // MUESTRA EL ALERT PARA CONFIRMAR LA DEVOLUCIÓN DE EFECTIVO
            $(document).on('click', '.devolver-efectivo', function() {
                var notaId = $(this).data('id');
                var monto = $(this).data('monto');

                // Obtener la fila de DataTable correspondiente
                var rowData = NotaCreditoTable.row($(this).closest('tr')).data();
                var folio = rowData.folio; // <-- aquí está el folio

                Swal.fire({
                    title: 'Confirmar devolución',
                    text: `¿Deseas devolver $${monto} al cliente?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, devolver',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2', // clases Tailwind
                        cancelButton: 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2'
                    },
                    buttonsStyling: false // importante para que Tailwind funcione
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Enviar petición AJAX
                        $.ajax({
                            url: "{{ route('admin.caja.movimiento.update', ':id') }}".replace(':id', notaId),
                            method: 'PUT',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                monto: monto,
                                tipo: 'salida',
                                motivo: `Devolución de nota de crédito ${folio}`,
                                origen_type: 'App\\Models\\NotaCredito'
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: '¡Devolución realizada!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                    customClass: {
                                        confirmButton: 'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded'
                                    },
                                    buttonsStyling: false // importante para que Tailwind funcione
                                });

                                $('#nota-credito').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error',
                                    text: xhr.responseJSON.message || 'Ocurrió un error',
                                    icon: 'error',
                                    confirmButtonText: 'Aceptar',
                                    customClass: {
                                        confirmButton: 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded'
                                    },
                                    buttonsStyling: false
                                });
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

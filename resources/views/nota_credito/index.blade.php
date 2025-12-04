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

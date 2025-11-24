@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Reparaciones',
        ],
    ],
])

@section('css')

@stop

@php
    use Carbon\Carbon;
@endphp

@section('content')
@section('action')
    <div class="flex justify-start space-x-2">
        <a href="{{ route('admin.reparacion.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Nuevo
        </a>
    </div>
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

            <table id="tablaReparacion" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Equipo</th>
                        <th>Teléfono</th>
                        <th>Inicio</th>
                        <th>Finalizado</th>
                        <th>Entregado</th>
                        <th>Reparador</th>
                        <th>Pago</th>
                        <th>Venta</th>
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
    $(document).ready(function() {
        cargarReparaciones();

        function cargarReparaciones() {
             const postData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                origen: "reparador.index"
            };

            if ($.fn.DataTable.isDataTable('#tablaReparacion')) {
                $('#tablaReparacion').DataTable().clear().destroy();
            }

            var ReparacionesTable = $('#tablaReparacion').DataTable({
                processing: true,
                serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                responsive: true,
                ajax: {
                    url: "{{ route('reparador.index.ajax') }}",
                    type: "POST",
                    data: postData
                },
                columns: [
                    { data: 'id', visible: false },
                    { data: 'folio' },
                    { data: 'cliente_nombre' },
                    { data: 'equipo' },
                    { data: 'tel1' },
                    { data: 'fecha_ingreso' },
                    { data: 'fecha_listo' },
                    { data: 'fecha_entregado' },
                    { data: 'reparador_select', orderable: false, searchable: false },
                    { data: 'costo_servicio', render: function(data) { return '$' + data; } },
                    { data: 'venta_id' },
                    { data: 'estatus_label', orderable: false, searchable: false },
                    { data: 'acciones', orderable: false, searchable: false }
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
            cargarReparaciones();
        });

        // ASIGNAMOS EL REPARADOR
        $(document).on('change', '.asignar-reparador', function () {
            let reparacionId = $(this).data('id');
            let reparadorId  = $(this).val();

            $.post("{{ route('reparador.asignar') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reparacion_id: reparacionId,
                reparador_id: reparadorId
            }, function(res){
                //Swal.fire('Asignado', res.message, 'success');
                Swal.fire({
                    icon: "success",
                    title: "Asignado",
                    text: res.message,
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    },
                    buttonsStyling: false
                });
            });
        });

        // ✅ Pagar servicio (con costo definido por el usuario, puede ser 0)
        $(document).on('click', '.pagar-servicio', function () {
            let reparacionId = $(this).data('id');

            Swal.fire({
                title: 'Definir costo del servicio',
                text: "Ingrese el monto a pagar al reparador externo",
                input: 'number',
                inputAttributes: {
                    min: 0,
                    step: '0.01'
                },
                showCancelButton: true,
                confirmButtonText: 'Continuar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 " +
                                "focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2",
                    cancelButton: "text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 " +
                                "focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                },
                preConfirm: (value) => {
                    if (value === '' || value < 0) {
                        Swal.showValidationMessage('El monto debe ser mayor o igual a 0');
                        return false;
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let monto = parseFloat(result.value);

                    Swal.fire({
                        title: '¿Confirmar pago?',
                        text: `Se pagará $${monto.toFixed(2)} al reparador externo`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, pagar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            confirmButton: "text-white bg-green-600 hover:bg-green-700 focus:ring-4 " +
                                        "focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2",
                            cancelButton: "text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 " +
                                        "focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        }
                    }).then((resConfirm) => {
                        if (resConfirm.isConfirmed) {
                            $.post("{{ route('reparacion.pagar.servicio') }}", {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                reparacion_id: reparacionId,
                                monto: monto
                            }, function(res){
                                //Swal.fire("Éxito", res.message, "success");
                                Swal.fire({
                                    icon: "success",
                                    title: "Éxito",
                                    text: res.message,
                                    confirmButtonText: "OK",
                                    customClass: {
                                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                    },
                                    buttonsStyling: false
                                });
                                cargarReparaciones(); // recargar tabla
                            }).fail(function(err){
                                Swal.fire({
                                    icon: "Error",
                                    title: "Error",
                                    text: err.responseJSON.message,
                                    confirmButtonText: "OK",
                                    customClass: {
                                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                    },
                                    buttonsStyling: false
                                });
                                //Swal.fire("Error", err.responseJSON.message, "error");
                            });
                        }
                    });
                }
            });
        });

        // ✅ Productos/Servicios
        $(document).on('click', '.productos-servicios', function () {
            let reparacionId = $(this).data('id');

            // Abrir modal para capturar solución, recomendaciones y productos
            $('#modalProductosServicios').data('id', reparacionId).modal('show');
        });


        var cotizacionTable = new DataTable('#garantias', {
            responsive: true,
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": 0,
                    "width": "130px"
                },
                {
                    "type": "num",
                    "targets": 1
                } // Define que la primera columna es de tipo numérico
            ],
            "order": [
                [1, "desc"]
            ]
        });

        var selectedItems = [];

        // Manejar la selección y deselección de checkboxes en todas las páginas
        $('#garantias').on('change', '.checkbox_check', function() {
            var id = $(this).val();
            if ($(this).is(':checked')) {
                // Agrega el ID al array si está seleccionado
                if (!selectedItems.includes(id)) {
                    selectedItems.push(id);
                }
            } else {
                // Remueve el ID del array si está deseleccionado
                selectedItems = selectedItems.filter(function(value) {
                    return value != id;
                });
            }
        });

        // Seleccionar o deseleccionar todos los elementos de la página actual
        $('#seleccionarTodo').on('click', function() {
            var checkboxInputs = $('#garantias tbody tr:visible input[type="checkbox"]');

            // Verifica si al menos uno está seleccionado en la página actual
            var alMenosUnoSeleccionado = checkboxInputs.is(':checked');

            // Marcar o desmarcar según la situación
            checkboxInputs.prop('checked', !alMenosUnoSeleccionado).trigger('change');
        });

        // CONFIRMA ELIMINAR
        $(document).on("click", ".btn-eliminar", function () {
            let form = $(this).closest("form")[0];

            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta reparación será eliminada y los cambios no podrán revertirse.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
                customClass: {
                    confirmButton: "bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg px-4 py-2 ml-2 mx-2",
                    cancelButton: "bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg px-4 py-2"
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // ACTUALIZA EL ESTADO DE CREADO A LISTO
        $(document).on('click', '.listo-item', function(e) {
            e.preventDefault();
            console.log('id: ' + $(this).data('id'));
            var id = $(this).data('id');

            Swal.fire({
                title: '¿La cotización esta lista?',
                text: 'No podrás revertir esto',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.value) {
                    console.log(id);
                    // Solicitud AJAX para eliminar el elemento
                    var showUrl = "{{ route('admin.cotizacion.update', ':id') }}";
                    var showLink = showUrl.replace(':id', id);
                    $.ajax({
                        url: showLink,
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PATCH",
                            origen: 'actualiza.estado',
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

        // Manejar el clic en la opción "Eliminar"
        $('#garantias').on('click', '.delete-item', function(e) {
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
                    var showUrl = "{{ route('admin.cotizacion.destroy', ':id') }}";
                    var showLink = showUrl.replace(':id', id);
                    console.log('showLink: ' + showLink);
                    $.ajax({
                        url: showLink,
                        type: 'POST',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "DELETE",
                            origen: "elimina.un.registro"
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

        $('.btn-submit').on('click', async function(e) {
            console.log('submit');
            var id = $(this).data('id');
            $('#form-' + id).submit();
        });

        // CONTROL DE LOS FORMULARIOS DE REASIGNADO-BAJA
        $(document).on('click', '.btn-destino', function (e) {
            e.preventDefault();

            let button = $(this);
            let form = button.closest('form');
            let tipo = button.data('tipo');
            let mensaje = '';

            if (tipo === 'reasignado') {
                mensaje = "¿Seguro que deseas reasignar este producto al inventario?";
            } else if (tipo === 'baja') {
                mensaje = "¿Seguro que deseas dar de baja definitiva este producto?";
            }

            Swal.fire({
                title: 'Confirmar acción',
                text: mensaje,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 mx-1',
                    cancelButton: 'text-gray-600 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mx-1'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });


    });
</script>
    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-reparacion') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop

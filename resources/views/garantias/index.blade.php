@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Garantías',
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
        <a href="{{ route('admin.garantias.create') }}"
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

            <table id="tablaGarantias" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Folio Venta</th>
                        <th>Fallo</th>
                        <th>Nota</th>
                        <th>Solución</th>
                        <th>Estatus</th>
                        <th>Fecha Registro</th>
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
        cargarGarantias();

        function cargarGarantias() {
             const postData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                origen: "garantia.index"
            };

            if ($.fn.DataTable.isDataTable('#tablaGarantias')) {
                $('#tablaGarantias').DataTable().clear().destroy();
            }

            var GarantiasTable = $('#tablaGarantias').DataTable({
                processing: true,
                serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                responsive: true,
                ajax: {
                    url: "{{ route('garantias.index.ajax') }}",
                    type: "POST",
                    data: postData
                },
                columns: [
                    { data: 'id', visible: false },
                    { data: 'folio' },
                    { data: 'cliente_nombre' },
                    { data: 'tel1' },
                    { data: 'producto_nombre' },
                    { data: 'precio_producto', render: function(data) { return '$' + data; } },
                    { data: 'folio_venta' },
                    { data: 'descripcion_fallo' },
                    { data: 'informacion_adicional' },
                    { data: 'solucion' },
                    { data: 'estatus_label', orderable: false, searchable: false },
                    { data: 'fecha_registro' },
                    { data: 'acciones', orderable: false, searchable: false }
                ],
                language: { url: "{{ asset('/json/i18n/es_es.json') }}" }
            });

            //  Re-inicializa Flowbite cada vez que DataTables repinta
            GarantiasTable.on('draw', function () {
                if (typeof window.initFlowbite === "function") {
                    window.initFlowbite();
                }
            });
        }

        // 🔄 Botón de recargar
        $("#reloadTable").on("click", function() {
            cargarGarantias();
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

        // Manejar eventos del botón de selección
        /*$('#seleccionarTodo').on('click', function() {
            // Obtener la información actual de la página
            var pageInfo = cotizacionTable.page.info();

            // Obtener todas las celdas de checkbox en la hoja activa (página actual)
            var checkboxInputs = $('#cotizaciones tbody tr:visible input[type="checkbox"]');

            // Verificar si al menos uno está seleccionado
            var alMenosUnoSeleccionado = checkboxInputs.is(':checked');

            // Marcar o desmarcar según la situación
            checkboxInputs.prop('checked', !alMenosUnoSeleccionado);

            // Volver a la página original
            setTimeout(function() {
                cotizacionTable.page(pageInfo.page).draw('page');
            }, 0);

        });*/

        // CONFIRMA ELIMINAR LOS SELECCIONADOS
        $(document).on('click', '#eliminaSeleccion', function(e) {
            e.preventDefault();
            // Obtener los IDs de los elementos seleccionados (de todas las páginas)
            var cantidadSeleccionados = selectedItems.length;

            if (cantidadSeleccionados > 0) {
                var elimina = '{{ route('admin.cotizacion.destroy', ':id') }}';
                var ruta = elimina.replace(':id', selectedItems.join(','));

                Swal.fire({
                    title: '¿Está seguro de eliminar los elementos seleccionados?',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí",
                    cancelButtonText: "No"
                }).then((result) => {
                    if (result.value) {
                        // Eliminar los elementos seleccionados aquí
                        $.ajax({
                            url: ruta,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: selectedItems, //idsSeleccionados,
                                origen: "elimina.multiple.registros"
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: 'Los elementos han sido eliminados.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    // Después de que el usuario cierre el SweetAlert, recarga la página
                                    location.reload();
                                });
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Hubo un error durante el proceso',
                                    text: 'Por favor intente más tarde.',
                                });
                                console.error('Error en la solicitud AJAX:',
                                    textStatus, errorThrown);

                                // Si el servidor devuelve un mensaje de error, puedes intentar extraerlo de la respuesta JSON
                                if (jqXHR.responseJSON && jqXHR.responseJSON
                                    .error) {
                                    console.error('Mensaje de error del servidor:',
                                        jqXHR.responseJSON.error);
                                }
                            }
                        });
                    }
                });
            } else {
                // Informar al usuario que no hay elementos seleccionado
                Swal.fire({
                    icon: "info",
                    title: 'No hay elementos seleccionados.',
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                    },
                    timer: 1500,
                    buttonsStyling: false
                });
            }
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
                window.open("{{ url('/ticket-garantia') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop

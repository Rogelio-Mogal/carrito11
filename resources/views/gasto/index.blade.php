
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Gasto'
    ]
]])

@section('css')
    <style>
        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
        }
        .custom-modal-bg {
            background-color: rgba(146, 151, 162, 0.688); /* Aplicar bg-gray-100 con opacidad 50% */
        }
        /* Estilos para modo oscuro */
        .dark .custom-modal-bg {
            background-color: rgba(137, 143, 153, 0.688); /* Color oscuro con opacidad */
        }
    </style>
@stop

@section('content')
    @section('action')
        <a href="{{ route('admin.gastos.create') }}"
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
                <table id="gastos" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gasto</th>
                            <th>Tipo de gasto</th>
                            <th>Tipo de gasto_id</th>
                            <th>Estatus</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                @include('gasto._modal_editar')
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script>
        // Función para mostrar el modal
        function showModal(id) {
            var modal = $('#editModal');
            var updateUrl = "{{ route('admin.gastos.update', ':id') }}".replace(':id', id);
            modal.find('form').attr('action', updateUrl);
            modal.show();
        }
        //Mostrar el modal si hay errores
        @if ($errors->any())
            $(document).ready(function() {
                var id = "{{ old('id') }}"; // Obtenemos el ID que se estaba editando
                $('#editId').val(id);
                $('#editGasto').val("{{ old('gasto') }}"); // Poblamos el campo con el valor previo
                $('#editTipoGasto').val("{{ old('tipo_gasto_id') }}").trigger('change');
                showModal(id); // Llamamos a la función que abre el modal
                // Inicializar select2 después de que el modal esté visible
                $('#editTipoGasto').select2({
                    placeholder: "-- TIPO GASTO --",
                    allowClear: true,
                    dropdownParent: $('#editModal') // Esto asegura que el dropdown se renderice dentro del modal
                });
            });
        @endif

        $(document).ready(function() {

            let tblGastos;
            cargarTipoGasto();

            function cargarTipoGasto() {
                const postData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origen: "gasto.index"
                };

                if ($.fn.DataTable.isDataTable('#gastos')) {
                    $('#gastos').DataTable().clear().destroy();
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

                tblGastos = $('#gastos').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    order: [], // evita que intente ordenar automático
                    ajax: {
                        url: "{{ route('gasto.index.ajax') }}",
                        type: "POST",
                        data: postData
                    },
                    columns: [
                        { data: 'id'},
                        { data: 'gasto' },
                        { data: 'tipo_gasto' },
                        { data: 'tipo_gasto_id', visible: false },
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
                cargarTipoGasto();
            });


            // Manejar el clic en el botón para mostrar el modal
            $('#gastos tbody').on('click', '.open-modal', function() {
                var id = $(this).data('id');
                showModal(id);
            });

            // Manejar el clic en el botón para cerrar el modal
            $('.close-modal').on('click', function() {
                $(this).closest('.modal').hide();
            });

            // Listener for details control
            tblGastos.on('responsive-display', function(e, datatable, row, showHide, update) {
                var rowData = row.data();
                if (showHide) {
                    var id = rowData[0];
                    $('.open-modal', row.node()).on('click', function() {
                        showModal(id);
                    });
                }
            });

            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            // AUMENTA-DECREMENTA INPUT
            $('#increment-button').on('click', function() {
                let currentValue = parseInt($('#cantidad_minima').val());
                if (!isNaN(currentValue) && currentValue < 999) {
                    $('#cantidad_minima').val(currentValue - 1);
                }
            });

            // Edita Forma de pago
            $(document).on('click', '.edit-item', function(e) {
                e.preventDefault();

                var table = $('#gastos').DataTable();
                var currentRow = $(this).closest('tr');

                // Detectar si es fila child (responsive)
                var row = table.row(currentRow.hasClass('child') ? currentRow.prev() : currentRow);
                var rowData = row.data();

                if (!rowData) {
                    console.error("No se pudo obtener rowData");
                    return;
                }

                // Obtener datos desde AJAX (datatable)
                var id = rowData.id;
                var gasto = rowData.gasto.replace(/\$/g, '');
                var tipo_gasto_id = rowData.tipo_gasto_id; // ← directo del JSON

                // Llenar campos del modal
                $('#editId').val(id);
                $('#editGasto').val(gasto);

                // Inicializar select2 *ANTES* de asignar el valor
                $('#editTipoGasto').select2({
                    placeholder: "-- TIPO GASTO --",
                    allowClear: true,
                    dropdownParent: $('#editModal')
                });

                // Asignar valor al select2
                $('#editTipoGasto').val(tipo_gasto_id).trigger('change');

                // Mostrar modal
                showModal(id);

                /*** Ajustes visuales (opcional, si lo usabas antes) ***/
                $('.select2-selection--single').css({
                    'height': '2.5rem',
                    'display': 'flex',
                    'align-items': 'center'
                });

                $('.select2-selection__rendered').css({
                    'line-height': '2.5rem',
                    'padding-left': '0.5rem',
                    'color': '#374151'
                });

                $('.select2-selection__arrow').css({
                    'height': '2.5rem',
                    'top': '50%',
                    'transform': 'translateY(-50%)'
                });

                // Activar búsqueda automática al abrir
                $(document).on('select2:open', () => {
                    let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            //COLOCAR COMAS EN INPUT NUMBER
            $(document).on('input', '.gasto', function () {
                let value = $(this).val();
                value = value.replace(/\$/g, '');
                value = value.replace(/[^0-9.]/g, '');
                let parts = value.split('.');
                if (parts.length > 2) value = parts[0] + '.' + parts[1];
                let entero = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                value = parts.length === 2 ? entero + '.' + parts[1] : entero;
                $(this).val(value);
            });

            $('form').on('submit', function () {
                $('.gasto').each(function() {
                    $(this).val($(this).val().replace(/,/g, ''));
                });
            });

            $('#saveChanges').on('click', function() {
                $('#editForm').submit();
            });

            // Manejar el clic en la opción "Eliminar"
            $('#gastos').on('click', '.delete-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: '¿Estás seguro de eliminar el registro?',
                    text: 'No podrás revertir esto',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5',
                        cancelButton: 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 ml-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.value) {
                        console.log('as: '+id);
                        // Solicitud AJAX para eliminar el elemento
                        var showUrl = "{{ route('admin.gastos.destroy', ':id') }}";
                        var showLink = showUrl.replace(':id', id);
                        $.ajax({
                            url: showLink,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": "DELETE"
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

            // Manejar el clic en la opción "Avtivar"
            $('#gastos').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'El gasto está deshabilitada',
                    text: '¿Está seguro de activar el gasto?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, activar',
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
                        var showUrl = "{{ route('admin.gastos.update', ':id') }}";
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

            // Evitar el envío del formulario al presionar Enter, excepto en textarea
            $(document).on('keypress', function(e) {
                if (e.which == 13 && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });

            // Variable para evitar envíos múltiples
            var formSubmitting = false;

            // Manejar el envío del formulario
            $('form').on('submit', function(e) {
                if (formSubmitting) {
                    // Si ya se está enviando, prevenir el envío
                    e.preventDefault();
                } else {
                    // Si no, marcar como enviando
                    formSubmitting = true;
                }
            });
        });
    </script>
@stop

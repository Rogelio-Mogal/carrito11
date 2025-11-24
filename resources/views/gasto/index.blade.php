
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
                        @foreach ($gastos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->gasto }}</td>
                                <td>{{ $item->tipoGasto->tipo_gasto }}</td>
                                <td>{{ $item->tipo_gasto_id }}</td>
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
                                        <a href="#"
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
                                        <a href="{{ route('admin.gastos.destroy', $item->id) }}"
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
                                        <a href="{{ route('admin.gastos.edit', $item->id) }}"
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
            var tblGastos = new DataTable('#gastos', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
                columnDefs: [
                    {
                        targets: 3, // Índice de la columna que quieres ocultar (Tipo de gasto_id)
                        visible: false, // Oculta la columna
                        searchable: false // Opcional: excluye esta columna de la búsqueda
                    }
                ]
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
                
                var id = $(this).data('id');
                var table = $('#gastos').DataTable();
                
                // Encuentra la fila en la que se hizo clic
                var currentRow = $(this).closest('tr');
                
                // Verifica si la fila actual es una fila de detalles
                var isDetailRow = currentRow.hasClass('child');
                
                var rowData;
                
                if (isDetailRow) {
                    // Si es una fila de detalle, obten la fila principal correspondiente
                    var parentRow = currentRow.prev('tr');
                    rowData = table.row(parentRow).data();
                } else {
                    // Si no es una fila de detalle, obtén los datos de la fila actual
                    var row = table.row(currentRow);
                    rowData = row.data();
                }

                // Verifica que rowData esté disponible y contenga la columna deseada
                var tipo_gasto_id = rowData && rowData[3] ? rowData[3] : null;

                // Obtener el valor del texto en el segundo <td> de la fila más cercana
                var gasto = $(this).closest('tr').find('td:eq(1)').text().trim();

                if (!gasto) {
                    // Si no se obtiene el valor de la fila principal, buscar en el row details
                    gasto = $(this).closest('tr').prev('tr').find('td:eq(1)').text().trim();
                }
                
                // Llenar el formulario del modal con los datos
                $('#editId').val(id);
                $('#editGasto').val(gasto);
                $('#editTipoGasto').val(tipo_gasto_id).trigger('change');
                showModal(id);

                // Inicializar select2 después de que el modal esté visible
                $('#editTipoGasto').select2({
                    placeholder: "-- TIPO GASTO --",
                    allowClear: true,
                    dropdownParent: $('#editModal') // Esto asegura que el dropdown se renderice dentro del modal
                });

                // Ajusta la altura del select2
                $('.select2-selection--single').css({
                    'height': '2.5rem', // Ajusta la altura según sea necesario
                    'display': 'flex',
                    'align-items': 'center'
                });

                $('.select2-selection__rendered').css({
                    'line-height': '2.5rem', // Asegúrate de que coincida con la altura del input
                    'padding-left': '0.5rem', // Ajusta el padding según sea necesario
                    'color': '#374151' // Asegúrate de que coincida con el texto del input
                });

                $('.select2-selection__arrow').css({
                    'height': '2.5rem', // Ajusta la altura según sea necesario
                    'top': '50%',
                    'transform': 'translateY(-50%)'
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
            });
            
            /*
            $(document).on('click', '.edit-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var gasto = $(this).closest('tr').find('td:eq(1)').text().trim();
                // Obtén la fila de DataTables correspondiente al botón o enlace clicado
                var row = $('#gastos').DataTable().row($(this).closest('tr'));
                // Obtén los datos de la fila, incluyendo las columnas ocultas
                var rowData = row.data();
                // Accede al valor de la columna oculta (tipo_gasto_id)
                var tipo_gasto_id = rowData[3]; // El índice de la columna que está oculta

                // Llenar el formulario del modal con los datos
                $('#editId').val(id);
                $('#editGasto').val(gasto);
                $('#editTipoGasto').val(tipo_gasto_id).trigger('change');
                showModal(id);

                // Inicializar select2 después de que el modal esté visible
                $('#editTipoGasto').select2({
                    placeholder: "-- TIPO GASTO --",
                    allowClear: true,
                    dropdownParent: $('#editModal') // Esto asegura que el dropdown se renderice dentro del modal
                });

                // Ajusta la altura del select2
                $('.select2-selection--single').css({
                    'height': '2.5rem', // Ajusta la altura según sea necesario
                    'display': 'flex',
                    'align-items': 'center'
                });

                $('.select2-selection__rendered').css({
                    'line-height': '2.5rem', // Asegúrate de que coincida con la altura del input
                    'padding-left': '0.5rem', // Ajusta el padding según sea necesario
                    'color': '#374151' // Asegúrate de que coincida con el texto del input
                });

                $('.select2-selection__arrow').css({
                    'height': '2.5rem', // Ajusta la altura según sea necesario
                    'top': '50%',
                    'transform': 'translateY(-50%)'
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
            });
            */

            $('#saveChanges').on('click', function() {
                $('#editForm').submit();
            });

            // Manejar el clic en la opción "Eliminar"
            $('#gastos').on('click', '.delete-item', function(e) {
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
                    title: 'El tipo de gasto está deshabilitada',
                    text: '¿Está seguro de activar el tipo de gasto?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, activar'
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

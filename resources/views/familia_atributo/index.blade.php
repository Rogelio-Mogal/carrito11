@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Familia-Atrubitos',
        ],
    ],
])

@section('css')

@stop

@section('content')
@section('action')
    <a href="{{ route('admin.familia.atributos.create') }}"
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
                         Procesando
                    </div>
                </div>
            </div>
            <table id="familia-atributo" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Familia</th>
                        <th>Atributo</th>
                        <th>Acciones</th>
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
    let tblAtributo;
    $(document).ready(function() {

        // Verificar si el elemento #atributo existe antes de inicializar
        if ($('#familia-atributo').length) {
            // Inicializar DataTable
            atributo();
        }

        // Manejar el overlay de carga con los eventos de DataTables
        tblAtributo.on('preXhr.dt', function() {
            // Muestra el overlay antes de que la petición AJAX comience
            $('#loadingOverlay').removeClass('hidden');
        }).on('xhr.dt', function() {
            // Oculta el overlay cuando la petición AJAX termina
            $('#loadingOverlay').addClass('hidden');
        });

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            tblAtributo.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });

        // Manejar el clic en la opción "Eliminar"
        $('#familia-atributo').on('click', '.delete-item', function(e) {
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
                        url: "{{ route('admin.familia.atributos.destroy', ':id') }}"
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
        $('#familia-atributo').on('click', '.activa-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
            Swal.fire({
                title: 'El proveedor está deshabilitada',
                text: '¿Está seguro de activar al proveedor?',
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
                        url: "{{ route('admin.familia.atributos.update', ':id') }}"
                            .replace(':id', id),
                        type: 'PUT',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "_method": "PUT",
                            "activa": 1
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

    function atributo() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'familia.atributo.index',
        };
        var editUrl = "{{ route('admin.familia.atributos.edit', ':id') }}";

        // Inicializar DataTable
        tblAtributo = $('#familia-atributo').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}",
                "processing": "Cargando...",
                "emptyTable": "No hay atributos registrados." ,
                "zeroRecords": "No se encontraron coincidencias."
            },
            responsive: true,
            retrieve: true,
            processing: true,
            ajax: {
                url: "{{ route('familia.atributo.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = postData._token;
                    d.origen = postData.origen;
                },
                error: function(xhr, error, code) {
                    console.error("Error al cargar datos:", error);
                    $('#familia-atributo tbody').html(
                        '<tr><td colspan="5" class="text-center">No se pudieron cargar los datos.</td></tr>'
                    );
                }
            },
            'columns': [{
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false
                },
                {
                    data: 'familia.nombre',
                    name: 'familia.nombre'
                },
                {
                    data: 'atributo.nombre',
                    name: 'atributo.nombre'
                },

                {
                    data: 'id',
                    render: function(data, type, row) {
                        var editLink = editUrl.replace(':id', data);
                        var isActive = row.activo;

                        //if (isActive == 1) {
                            return `
                                <a href="${editLink}"
                                    data-id="${data}"
                                    data-popover-target="editar-${data}" data-popover-placement="left"
                                    class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                    </svg>
                                    <span class="sr-only">Editar</span>
                                </a>
                                <div id="editar-${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                    </div>
                                </div>
                                <a href="#"
                                    data-popover-target="eliminar-${data}" data-popover-placement="left"
                                    data-id="${data}"
                                    class="delete-item mb-1  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="sr-only">Eliminar</span>
                                </a>
                                <div id="eliminar-${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                                    </div>
                                </div>
                            `;

                    }
                }
            ],
            initComplete: function(settings, json) {
                let api = this.api();
                if (api.data().count() === 0) {
                    let colspan = api.columns().count();
                    $("#familia-atributo tbody").html(
                        `<tr><td colspan="${colspan}" class="text-center text-gray-500">
                            No hay atributos registrados.
                        </td></tr>`
                    );
                }
            },
            drawCallback: function(settings) {
                // Inicializar popovers para los elementos
                $('[data-popover-target]').each(function() {
                    const triggerEl = $(this);
                    const tooltipId = `#${triggerEl.attr('data-popover-target')}`;
                    const tooltipEl = $(tooltipId);

                    // Asegúrate de que la clase tooltip-content esté presente
                    tooltipEl.addClass('tooltip-content');

                    // Mostrar el tooltip al pasar el cursor
                    triggerEl.hover(
                        function() {
                            tooltipEl.removeClass('invisible opacity-0').addClass('visible opacity-100');
                        },
                        function() {
                            tooltipEl.removeClass('visible opacity-100').addClass('invisible opacity-0');
                        }
                    );
                });
            }
        });
    }
    
</script>
@stop

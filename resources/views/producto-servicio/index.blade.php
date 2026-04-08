@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Producto/Servicio',
        ],
    ],
])

@section('css')
<style>
    .tooltip-content {
        transition: opacity 0.3s ease-in-out;
    }

    .invisible {
        display: none;
    }

    .visible {
        display: block;
        opacity: 1;
    }

    .opacity-0 {
        opacity: 0;
    }

    .tooltip-content {
        transition: opacity 0.3s ease-in-out;
        visibility: hidden; /* Esto es lo que asegura que esté oculto */
        opacity: 0;
    }

    .tooltip-visible {
        visibility: visible; /* Esto lo hará visible */
        opacity: 1;
    }

</style>
@stop

@section('content')
@section('action')
    <a href="{{ route('admin.producto.servicio.create') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
@endsection
<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="hola grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <div class="mb-4">
                <button id="reloadTable"
                    class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Recargar Tabla
                </button>
            </div>

            <table id="productos" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Código</th>
                        <th>Marca</th>
                        <th>Familia</th>
                        <th>Sub familia</th>
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
        // Inicializar DataTable
        var table = productos();

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });
        // Manejar el clic en la opción "Eliminar"
        $('#productos').on('click', '.delete-item', function(e) {
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
                        url: "{{ route('admin.producto.servicio.destroy', ':id') }}"
                            .replace(':id', id),
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

    function productos() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'productos.index',
        };
        var editUrl = "{{ route('admin.producto.servicio.edit', ':id') }}";

        // Inicializar DataTable
        return $('#productos').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: true,
            retrieve: true,
            processing: true,
            ajax: {
                url: "{{ route('productos.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = postData._token;
                    d.origen = postData.origen;
                }
            },
            'columns': [{
                    data: 'id',
                    name: 'id',
                    visible: false,
                    searchable: false
                },
                {
                    data: 'image',
                    render: function(data, type, row) {
                        return '<img class="h-auto max-w-20 sm:max-w-20 md:max-w-40 lg:max-w-40 object-cover object-center" src="' +
                            data + '" alt="">';
                    }
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'codigo_barra',
                    name: 'codigo_barra'
                },

                {
                    data: 'marca_c.nombre'
                },
                {
                    data: 'familia_c.nombre'
                },
                {
                    data: 'subFamilia_c.nombre',
                    defaultContent: 'NO ASIGNADO'
                },
                {
                    data: 'activo',
                    render: function(data, type, row) {
                        if (data == 0) {
                            return '<span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>';
                        } else if (data == 1) {
                            return '<span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>';
                        }
                        return '';
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        var editLink = editUrl.replace(':id', data);
                        var isActive = row.activo;

                        if (isActive == 1) {
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
                        } else {
                            // COLOCAR EL BOTÓN PARA ACTIVAR EL PRODUCTO
                            return `

                            `;
                        }
                    }
                }
            ],
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

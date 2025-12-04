@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Inventario',
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

    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="hola grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
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

                <label for="sucursal_filter">Sucursal:</label>
                <select id="sucursal_filter" class="form-select">
                    <option value="">Todas</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}"
                            {{ isset($sucursalUsuario) && $sucursalUsuario == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
                <br>

                <table id="inventario" class="table table-striped " style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Sucursal</th>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>ST</th> <!-- Stock -->
                            <th>A</th> <!-- Apartado -->
                            <th>S</th> <!-- Servicio -->
                            <th>G</th> <!-- Garantias -->
                            <th>P.Costo</th>
                            <th>P.Público</th>
                            <th>P.Medio Mayoreo</th>
                            <th>P.Mayoreo</th>
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
            let table = productos();

            function productos() {
                const postData = {
                    //_token: $('input[name=_token]').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    origen: 'productos.inventario',
                };
                var editUrl = "{{ route('admin.inventario.edit', ':id') }}";

                // Inicializar DataTable
                return $('#inventario').DataTable({
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
                            d.sucursal_id = $('#sucursal_filter').val();
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
                            data: 'sucursal_nombre',
                            name: 'sucursal_nombre'

                        },
                        {
                            data: 'producto_nombre',
                            name: 'producto_nombre'
                        },
                        {
                            data: 'codigo_barra',
                            name: 'codigo_barra'
                        },
                        {
                            data: 'cantidad',
                            defaultContent: 'SIN INVENTARIO'
                        },
                        {
                            data: 'producto_apartado',
                            defaultContent: 'Apartado'
                        },
                        {
                            data: 'producto_servicio',
                            defaultContent: 'Servicio'
                        },
                        {
                            data: 'producto_garantia',
                            defaultContent: 'Garantia'
                        },
                        {
                            data: 'precio_costo',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        },
                        {
                            data: 'precio_publico',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        },
                        {
                            data: 'precio_medio_mayoreo',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        },
                        {
                            data: 'precio_mayoreo',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
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

                                return `
                                    <a href="${editLink}"
                                        data-id="${data}"
                                        data-popover-target="editar${data}" data-popover-placement="left"
                                        class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                        </svg>
                                        <span class="sr-only">Editar</span>
                                    </a>
                                    <div id="tooltip-editar-${data}" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-2 space-y-2">
                                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                        </div>
                                    </div>
                                `;
                                /*return `
                                <a href="${editLink}"
                                    data-popover-target="editar${data}"
                                    data-popover-placement="left"
                                    class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>
                                    </svg>
                                    <span class="sr-only">Editar</span>
                                </a>
                                <div id="editar${data}" role="tooltip"
                                    class="popover-content absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold text-gray-900 dark:text-black">&nbsp; Editar</h6>
                                    </div>
                                    <div data-popper-arrow></div>
                                </div>
                                `;*/

                            }
                        }
                    ],
                    drawCallback: function(settings) {
                        // Agregar eventos de hover para mostrar y ocultar el tooltip
                        $('[data-id]').each(function() {
                            const triggerEl = $(this);
                            const tooltipId = `#tooltip-editar-${triggerEl.data('id')}`;
                            const tooltipEl = $(tooltipId);

                            const tooltip = $('#tooltip-editar-' + $(this).data('id'));
                            tooltip.addClass('tooltip-content');

                            // Mostrar tooltip al pasar el cursor
                            triggerEl.hover(
                                function() {
                                    tooltipEl.removeClass('invisible opacity-0');
                                    tooltipEl.addClass('visible opacity-100');
                                },
                                function() {
                                    tooltipEl.removeClass('visible opacity-100');
                                    tooltipEl.addClass('invisible opacity-0');
                                }
                            );
                        });
                    }
                });
            }

            // RECARGAR TABLA
            $('#reloadTable').on('click', function() {
                table.ajax.reload();
            });

            // 🔹 Refrescar tabla al cambiar filtro
            $('#sucursal_filter').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@stop

@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Cotizaciones',
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
        <a href="{{ route('admin.cotizacion.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Nuevo
        </a>
    </div>
@endsection

<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <table id="cotizaciones" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <button id="seleccionarTodo" type="button" data-popover-target="selectAll" data-popover-placement="right"
                                class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                </svg>                                                                    
                                <span class="sr-only">Seleccionar</span>
                            </button>

                            <button id="eliminaSeleccion" type="button" data-popover-target="deleteAll" data-popover-placement="right"
                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium text-center inline-flex items-center rounded-lg text-xs px-3 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                  </svg>                                  
                                  <span class="sr-only">Eliminar selección</span>
                            </button>

                            <div id="selectAll" role="tooltip"
                                class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-2 space-y-2">
                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Selecciona todos los elementos activos de la tabla.</h6>
                                </div>
                            </div>
                            <div id="deleteAll" role="tooltip"
                                class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                <div class="p-2 space-y-2">
                                    <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Elimina los elementos seleccionados.</h6>
                                </div>
                            </div>
                        </th>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cotizaciones as $item)
                        <tr>
                            <td>
                                @if ($item->activo == 1)
                                    <input type="checkbox" name="check{{ $item->id }}"
                                        class="checkbox_check w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        value="{{ $item->id }}">
                                @endif
                            </td>
                            <td> {{ $item->id }} </td>
                            <td> {{ $item->cliente ? $item->cliente : optional($item->clienteDocumento)->full_name }}
                                {{-- $item->clienteDocumento->full_name --}} </td>
                            <td> {{ Carbon::parse($item->fecha)->format('d/m/Y H:i:s') }} </td>
                            <td> {{ '$' . number_format($item->total, 2, '.', ',') }} </td>
                            <td>
                                @if ($item->estado == 'CREADO')
                                    <span
                                        class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">CREADO</span>
                                @endif
                                @if ($item->estado == 'LISTO')
                                    <span
                                        class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">LISTO</span>
                                @endif
                            </td>
                            {{--
                            <td>
                                @if ($item->activo == 0)
                                    <span
                                        class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>
                                @endif
                                @if ($item->activo == 1)
                                    <span
                                        class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                                @endif
                            </td>
                            --}}
                            <td>
                                <a href="{{ route('admin.cotizacion.show', $item->id) }}"
                                    data-popover-target="detalles{{ $item->id }}" data-popover-placement="bottom"
                                    class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                                        <path stroke="currentColor" stroke-width="2"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="sr-only">Detalles</span>
                                </a>
                                @if ($item->estado == 'CREADO' && $item->activo == 1)
                                    <a href="#" data-popover-target="listo{{ $item->id }}"
                                        data-popover-placement="bottom" data-id="{{ $item->id }}"
                                        class="listo-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                        </svg>
                                        <span class="sr-only">Listo</span>
                                    </a>
                                    
                                    <a href="{{ route('admin.cotizacion.edit', $item->id) }}"
                                        data-popover-target="editar{{ $item->id }}" data-popover-placement="bottom"
                                        class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                        </svg>
                                        <span class="sr-only">Editar</span>
                                    </a>
                                @endif
                                <a href="{{ route('admin.cotizacion.destroy', $item->id) }}"
                                    data-popover-target="eliminar{{ $item->id }}" data-popover-placement="bottom"
                                    data-id="{{ $item->id }}"
                                    class="delete-item mb-1  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span class="sr-only">Eliminar</span>
                                </a>

                                <div id="listo{{ $item->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cotización lista</h6>
                                    </div>
                                </div>
                                <div id="detalles{{ $item->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Detalles</h6>
                                    </div>
                                </div>
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('js')
<script>
    $(document).ready(function() {
        var cotizacionTable = new DataTable('#cotizaciones', {
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
        $('#cotizaciones').on('change', '.checkbox_check', function() {
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
            var checkboxInputs = $('#cotizaciones tbody tr:visible input[type="checkbox"]');

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
        $('#cotizaciones').on('click', '.delete-item', function(e) {
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

    });
</script>
@stop

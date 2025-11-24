
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Asignar gasto'
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
        <a href="{{ route('admin.asignar.gasto.create') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
    @endsection
    <?php
        $fechaActual = date('Y-m-d');
    ?>
    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <form id="filtroForm" name="filtroForm" action="{{ route('admin.asignar.gasto.index') }}">
                    <input type="hidden" name="mes_hidden" id="mes_hidden">
                    <input type="hidden" name="rango" id="rango">
                    <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filtro por mes</label>
                            <input type="month" name="mes" id="mes" step="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{ isset($mes) ? $mes : $now->format('Y-m') }}" onchange="updateHiddenValue()">
                        </div>
                        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha inicio</label>
                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="fechaInicio" id="fechaInicio"
                                value="{{ $fechaActual }}">
                        </div>
                        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha fin</label>
                            <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="fechaFin" id="fechaFin"
                                value="{{ $fechaActual }}">
                        </div>
                        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
                            <button id="btn-filtro" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                                onclick="filtrarFormulario()">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                <table id="asignar_gasto" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Gasto</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Forma de pago</th>
                            <th>Nota</th>
                            <th>Estatus</th>
                            <th>Opciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asignarGastos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->fecha }}</td>
                                <td>{{ $item->gasto->gasto }}</td>
                                <td>{{ $item->gasto->tipoGasto->tipo_gasto }}</td>
                                <td>{{ $item->monto }}</td>
                                <td>{{ $item->formaPago->forma_pago }}</td>
                                <td>{!! $item->nota !!}</td>
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
                                        {{--
                                        <a href="{{ route('admin.clientes.edit', $item->id) }}"
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
                                        --}}
                                        <a href="{{ route('admin.asignar.gasto.destroy', $item->id) }}"
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
                                        {{--
                                        <div id="editar{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                            </div>
                                        </div>
                                        --}}
                                        <div id="eliminar{{ $item->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                            <div class="p-2 space-y-2">
                                                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
                                            </div>
                                        </div>
                                    @else
                                        <a href="#"
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
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script>
        // Cambio input si es por mes
        function updateHiddenValue() {
            // Obtener el valor del input de tipo month
            const selectedMonth = document.getElementById('mes').value;

            // Actualizar el valor del input oculto
            document.getElementById('mes_hidden').value = 'MES';
            document.getElementById('rango').value = '';

            // Enviar el formulario
            document.getElementById('filtroForm').submit();
        }

        function filtrarFormulario() {
            // Modificar el valor del input 'rango'
            const rangoInput = document.getElementById('rango');
            document.getElementById('mes_hidden').value = '';
            rangoInput.value = 'RANGO'; // Puedes cambiar 'Tu nuevo valor aquí' por el valor que desees

            // Enviar el formulario
            document.getElementById('rangoConsulta').submit();
        }
        
        $(document).ready(function() {
            var tblAsignarGasto = new DataTable('#asignar_gasto', {
                responsive: true,
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
                "order": [[0, "desc"]]
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

            // Manejar el clic en la opción "Eliminar"
            $('#asignar_gasto').on('click', '.delete-item', function(e) {
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
                        var showUrl = "{{ route('admin.asignar.gasto.destroy', ':id') }}";
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
            $('#asignar_gasto').on('click', '.activa-item', function(e) {
                e.preventDefault();
                var id = $(this).data('id');

                // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
                Swal.fire({
                    title: 'Asignar gasto está deshabilitada',
                    text: '¿Está seguro de activar asignar gasto?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, activar'
                }).then((result) => {
                    if (result.value) {
                        console.log(id);
                        // Solicitud AJAX para eliminar el elemento
                        var showUrl = "{{ route('admin.asignar.gasto.update', ':id') }}";
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

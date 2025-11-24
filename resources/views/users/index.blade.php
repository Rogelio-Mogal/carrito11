@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Usuarios',
        ],
    ],
])

@section('css')

@stop

@section('content')
@section('action')
    <a href="{{ route('admin.users.create') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
@endsection
<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <table id="users" class="table table-striped" style="width:100%">
                <thead>
                    <tr>

                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Sucursal</th>
                        <th>Reparador</th>
                        <th>Externo</th>
                        <th>Estatus</th>
                        <th>Opciones</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->last_name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->sucursal ? $item->sucursal->nombre : 'Sin asignar' }}</td>

                            {{-- ✅ Checkbox reparador --}}
                            <td>
                                <input type="checkbox" class="toggle-reparador"
                                    data-id="{{ $item->id }}"
                                    {{ $item->es_reparador ? 'checked' : '' }}>
                            </td>

                            {{-- ✅ Checkbox externo (deshabilitado si no es reparador) --}}
                            <td>
                                <input type="checkbox" class="toggle-externo"
                                    data-id="{{ $item->id }}"
                                    {{ $item->es_externo ? 'checked' : '' }}
                                    {{ !$item->es_reparador ? 'disabled' : '' }}>
                            </td>

                            {{--
                                <td>
                                    @if ($item->tipo_usuario == 'punto_de_venta')
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Punto de venta</span>
                                    @endif
                                    @if ($item->tipo_usuario == 'catalogo')
                                        <span class="bg-blue-100 text-blue-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Usuario catálogo</span>
                                    @endif
                                </td>
                                --}}
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
                            <td>
                                @if ($item->activo == 1)
                                    <a href="{{ route('admin.users.edit', $item->id) }}" data-id="{{ $item->id }}"
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
                                    <a href="{{ route('admin.users.destroy', $item->id) }}"
                                        data-popover-target="eliminar{{ $item->id }}"
                                        data-popover-placement="bottom" data-id="{{ $item->id }}"
                                        class="delete-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
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
                                    <a href="#" data-popover-target="activar{{ $item->id }}"
                                        data-popover-placement="bottom" data-id="{{ $item->id }}"
                                        class="activa-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3" />
                                        </svg>
                                        <span class="sr-only">Activar</span>
                                    </a>
                                    <div id="activar{{ $item->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                        <div class="p-2 space-y-2">
                                            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cambiar a
                                                activo</h6>
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
    /* window.addEventListener('phx:page-loading-stop', (event) => {
                    // trigger flowbite events
                    window.document.dispatchEvent(new Event("DOMContentLoaded", {
                        bubbles: true,
                        cancelable: true
                    }));
                });
            */

    $(document).ready(function() {
        var usersTable = new DataTable('#users', {
            responsive: true,
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
        });

        // Manejar el clic en la opción "Eliminar"
        $('#users').on('click', '.delete-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
            Swal.fire({
                title: '¿Estás seguro de eliminar el registro?',
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
                        url: "{{ route('admin.users.destroy', ':id') }}"
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
        $('#users').on('click', '.activa-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
            Swal.fire({
                title: 'La cuenta esta deshabilitada',
                text: '¿Está seguro de activar la ceunta?',
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
                        url: "{{ route('admin.users.update', ':id') }}"
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

        // Cambiar reparador
        // Cuando cambie el checkbox reparador
        $(document).on('change', '.toggle-reparador', function() {
            let userId = $(this).data('id');
            let isChecked = $(this).is(':checked');
            let externoCheckbox = $(this).closest('tr').find('.toggle-externo');

            if (!isChecked) {
                // 🔒 Si no es reparador, forzar externo a OFF y deshabilitarlo
                externoCheckbox.prop('checked', false).prop('disabled', true);
            } else {
                // 🔓 Si es reparador, habilitar externo
                externoCheckbox.prop('disabled', false);
            }

            $.post("{{ route('users.toggleReparador') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: userId
            }, function(res) {
                Swal.fire({
                    icon: "success",
                    title: "Actualizado",
                    text: "Estado de reparador cambiado.",
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    },
                    buttonsStyling: false
                });
            });
        });

        // Cuando intenten cambiar externo
        $(document).on('change', '.toggle-externo', function() {
            let userId = $(this).data('id');
            let reparadorCheckbox = $(this).closest('tr').find('.toggle-reparador');

            if (!reparadorCheckbox.is(':checked')) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Debes marcar como reparador antes de asignar externo.",
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    },
                    buttonsStyling: false
                });
                $(this).prop('checked', false); // 🔄 Revertir
                return;
            }

            $.post("{{ route('users.toggleExterno') }}", {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: userId
            }, function(res) {
                Swal.fire({
                    icon: "success",
                    title: "Actualizado",
                    text: "Estado de externo cambiado.",
                    confirmButtonText: "OK",
                    customClass: {
                        confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                    },
                    buttonsStyling: false
                });
            });
        });

    });
</script>
@stop

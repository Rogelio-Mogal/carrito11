@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Compras',
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
        <a href="{{ url('compras/create?compra=1') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Nuevo
        </a>
        {{--
        <a href="{{ url('compras/create?compra=2') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Compra web
        </a>
        --}}
    </div>
@endsection
<?php
    $fechaActual = date('Y-m-d');
?>

<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <form id="filtroForm" name="filtroForm" action="{{ route('admin.compras.index') }}">
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
            <table id="compras" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Factura</th>
                        <th>Usuario</th>
                        <th>Proveedor</th>
                        <th>Fecha captura</th>
                        <th>Fecha compra</th>
                        {{--<th>Tipo</th>--}}
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compras as $item)
                        <tr>
                            <td> {{ $item->id }} </td>
                            <td> {{ $item->num_factura }} </td>
                            <td> {{ $item->usuario_nombre }} </td>
                            <td> {{ $item->proveedor->proveedor }} </td>
                            <td> {{ Carbon::parse($item->fecha_captura)->format('d/m/Y H:i:s') }} </td>
                            <td> {{ Carbon::parse($item->fecha_compra)->format('d/m/Y H:i:s') }} </td>
                            {{--<td> {{ $item->tipo }} </td>--}}
                            <td> {{ '$' . number_format($item->total, 2, '.', ',') }} </td>
                            <td>
                                @if( $item->activo == 0 )
                                    <span class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Eliminado</span>
                                @endif
                                @if( $item->activo == 1 )
                                    <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">Activo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.compras.show', $item->id) }}"
                                    data-id="{{ $item->id }}"
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
                                @if ($item->activo == 1)
                                    <a href="{{ route('admin.compras.destroy', $item->id) }}"
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
                                @endif
                                <div id="detalles{{ $item->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Detalles</h6>
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
        var compraTable = new DataTable('#compras', {
            responsive: true,
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            "order": [[0, "desc"]]
        });

        // Manejar el clic en la opción "Eliminar"
        $('#compras').on('click', '.delete-item', function(e) {
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
                    var showUrl = "{{ route('admin.compras.destroy', ':id') }}";
                    var showLink = showUrl.replace(':id', id);
                    console.log('showLink: '+showLink);
                    $.ajax({
                        url: showLink, //"{{ route('admin.compras.destroy', ':id') }}".replace(':id', id),
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

        $('.btn-submit').on('click', async function(e) {
            console.log('submit');
            var id = $(this).data('id');
            $('#form-' + id).submit();
        });
    });
</script>
@stop

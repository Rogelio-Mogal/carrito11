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
            <form id="filtroForm">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <label class="block mb-2 text-sm font-medium text-gray-900">
                            Tipo de filtro
                        </label>

                        <div class="flex flex-wrap items-center gap-6">

                            <!-- NINGUNO -->
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tipoFiltro" value="NINGUNO"
                                    id="radioNinguno" class="w-4 h-4" checked>
                                <span>Ninguno</span>
                            </label>

                            <!-- POR MES -->
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tipoFiltro" value="MES"
                                    id="radioMes" class="w-4 h-4">
                                <span>Por mes</span>
                            </label>

                            <!-- INPUT MES -->
                            <div id="filtroMes" class="hidden">
                                <input
                                    type="month"
                                    id="mes"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5"
                                    value="{{ isset($mes) ? $mes : $now->format('Y-m') }}">
                            </div>

                            <!-- POR RANGO -->
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tipoFiltro" value="RANGO"
                                    id="radioRango" class="w-4 h-4">
                                <span>Por rango</span>
                            </label>

                            <!-- RANGO DE FECHAS -->
                            <div id="filtroRango" class="hidden flex gap-2">
                                <input
                                    type="date"
                                    id="fechaInicio"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5"
                                    value="{{ $fechaActual }}">

                                <input
                                    type="date"
                                    id="fechaFin"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5"
                                    value="{{ $fechaActual }}">
                            </div>

                            <!-- BOTONES -->
                            <div class="flex gap-3 ml-auto">

                                <button
                                    type="button"
                                    id="btnFiltrar"
                                    class="text-white bg-green-600 hover:bg-green-700 px-5 py-2 rounded-lg">
                                    Filtrar
                                </button>

                                <button
                                    type="button"
                                    id="reloadTable"
                                    class="text-white bg-blue-500 hover:bg-blue-600 px-5 py-2 rounded-lg">
                                    Recargar
                                </button>
                            </div>
                        </div>
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
                    {{--
                    @foreach ($compras as $item)
                        <tr>
                            <td> {{ $item->id }} </td>
                            <td> {{ $item->num_factura }} </td>
                            <td> {{ $item->usuario_nombre }} </td>
                            <td> {{ $item->proveedor->proveedor }} </td>
                            <td> {{ Carbon::parse($item->fecha_captura)->format('d/m/Y H:i:s') }} </td>
                            <td> {{ Carbon::parse($item->fecha_compra)->format('d/m/Y H:i:s') }} </td>
                            <!--<td> {{ $item->tipo }} </td>-->
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
                    --}}
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('js')
<script>
     const hoy = new Date();

    const fechaActual = hoy.toISOString().split('T')[0]; // 2026-01-30
    const mesActual = hoy.toISOString().slice(0, 7);     // 2026-01

    $(document).ready(function() {
        let tblGastos;
        cargarAsignaGasto();

        function cargarAsignaGasto() {

            if ($.fn.DataTable.isDataTable('#compras')) {
                $('#compras').DataTable().clear().destroy();
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

            tblGastos = $('#compras').DataTable({
                processing: true,
                serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                responsive: true,
                order: [], // evita que intente ordenar automático
                ajax: {
                    url: "{{ route('compra.index.ajax') }}",
                    type: "POST",
                    data: function(d){
                        return $.extend(d, {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            origen: "compras.index",
                        });
                    }
                },
                columns: [
                    { data: 'id'},
                    { data: 'num_factura'},
                    { data: 'usuario_nombre' },
                    { data: 'proveedor' },
                    { data: 'fecha_captura' },
                    { data: 'fecha_compra' },
                    { data: 'total' },
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
            $// Dejar seleccionado NINGUNO
            $("#radioNinguno").prop("checked", true);

            // Ocultar ambos filtros
            $("#filtroMes").addClass("hidden");
            $("#filtroRango").addClass("hidden");

            // Asignar valores por defecto
            $("#mes").val(mesActual);
            $("#fechaInicio").val(fechaActual);
            $("#fechaFin").val(fechaActual);

            cargarAsignaGasto();
        });

        // Mostrar u ocultar filtros según selección
        $("input[name='tipoFiltro']").on("change", function () {

            let tipo = $(this).val();

            if (tipo === "MES") {
                $("#filtroMes").removeClass("hidden");
                $("#filtroRango").addClass("hidden");
            } else if (tipo === "RANGO") {
                $("#filtroRango").removeClass("hidden");
                $("#filtroMes").addClass("hidden");
            }else if (tipo === "NINGUNO") {
                $("#filtroMes").addClass("hidden");
                $("#filtroRango").addClass("hidden");
            }
        });

        // FILTRAR (envío AJAX al DataTable)
        $("#btnFiltrar").on("click", function () {

            let tipo = $("input[name='tipoFiltro']:checked").val();

            let postData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                origen: "compras.index",
            };

            if (tipo === "MES") {
                postData.mes_hidden = "MES";
                postData.mes = $("#mes").val();
            }

            if (tipo === "RANGO") {
                postData.rango = "RANGO";
                postData.fechaInicio = $("#fechaInicio").val();
                postData.fechaFin = $("#fechaFin").val();
            }

            if (tipo === "NINGUNO") {
                postData.filtro = "NINGUNO";
            }

            // Ahora SÍ enviamos los datos correctamente al DataTable
            tblGastos.ajax.reload(null, false);
            tblGastos.ajax.params = postData;

            tblGastos.settings()[0].ajax.data = function(d){
                return $.extend(d, postData);
            };

            tblGastos.ajax.reload();
        });

        // Manejar el clic en la opción "Eliminar"
        $('#compras').on('click', '.delete-item', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            // Utilizar SweetAlert2 para mostrar un mensaje de confirmación
            Swal.fire({
                title: '¿Estás seguro de eliminar el registro?',
                text: 'No podrás revertir esto',
                icon: 'info',
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

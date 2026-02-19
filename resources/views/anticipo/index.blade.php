@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Anticipo',
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
            visibility: hidden;
            /* Esto es lo que asegura que esté oculto */
            opacity: 0;
        }

        .tooltip-visible {
            visibility: visible;
            /* Esto lo hará visible */
            opacity: 1;
        }
    </style>

@stop

<?php
    $fechaActual = date('Y-m-d');
?>

@section('content')
@section('action')
    <a href="{{ route('admin.anticipo.create') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
@endsection
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
            <table id="anticipo" class="table table-striped " style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Saldo actual</th>
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
    const hoy = new Date();

    const fechaActual = hoy.toISOString().split('T')[0]; // 2026-01-30
    const mesActual = hoy.toISOString().slice(0, 7);     // 2026-01
    let filtros = {};

    var editUrl = "{{ route('admin.anticipo.show', ':id') }}";
    $(document).ready(function() {
        let table;
        // Inicializar DataTable
        anticipo();

        // 🔹 Refrescar tabla al cambiar filtro
        $('#sucursal_filter').on('change', function() {
            table.ajax.reload();
        });

        function anticipo() {
            const postData = {
                _token: $('input[name=_token]').val(),
                origen: 'anticipo.apartado.index',
            };

            if ($.fn.DataTable.isDataTable('#anticipo')) {
                $('#anticipo').DataTable().clear().destroy();
            }

            // Inicializar DataTable
            //return $('#anticipo').DataTable({
            table = $('#anticipo').DataTable({
                "language": {
                    "url": "{{ asset('/json/i18n/es_es.json') }}"
                },
                responsive: true,
                retrieve: true,
                processing: true,
                ajax: {
                    url: "{{ route('anticipo.apartado.index.ajax') }}",
                    type: "POST",
                    data: function (d) {
                        return $.extend(d, {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            origen: "anticipo.apartado.index",
                            ...filtros
                        });
                    }

                    //'data': function(d) {
                    //    d._token = "{{ csrf_token() }}";
                    //    d.origen = postData.origen;
                    //}
                },
                'columns': [{
                        data: 'cliente_id',
                        visible: false,
                        searchable: false
                    },
                    {
                        data: 'cliente',
                        name: 'cliente',
                        defaultContent: 'Sin cliente'
                    },
                    {
                        data: 'total_credito',
                        render: function(data) {
                            return '$ ' + $.fn.dataTable.render.number(',', '.', 2).display(data);
                        },
                        defaultContent: '$0.00'
                    },
                    {
                        data: 'total_saldo',
                        render: function(data) {
                            return '$ ' + $.fn.dataTable.render.number(',', '.', 2).display(data);
                        },
                        defaultContent: '$0.00'
                    },
                    {
                        data: 'estatus',
                        name: 'estatus',
                        render: function(data, type, row) {
                            let color = 'gray';
                            if (data === 'ACTIVO') color = 'green';
                            else if (data === 'PASO_A_VENTA') color = 'blue';
                            else if (data === 'CANCELADO') color = 'red';
                            else if (data === 'LIQUIDADO') color = 'purple';
                            return `<span class="px-2 py-1 rounded text-white bg-${color}-600">${data}</span>`;
                        },
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'cliente_id',
                        render: function(data, type, row) {
                            var editLink = editUrl.replace(':id', data);

                            return `
                                <a href="${editLink}"
                                    data-id="${data}"
                                    data-popover-target="tooltip-editar-${data}" data-popover-placement="left"
                                    class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 18h14M5 18v3h14v-3M5 18l1-9h12l1 9M16 6v3m-4-3v3m-2-6h8v3h-8V3Zm-1 9h.01v.01H9V12Zm3 0h.01v.01H12V12Zm3 0h.01v.01H15V12Zm-6 3h.01v.01H9V15Zm3 0h.01v.01H12V15Zm3 0h.01v.01H15V15Z"/>
                                    </svg>
                                    <span class="sr-only">Abonar</span>
                                </a>
                                <div id="tooltip-editar-${data}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Abonar</h6>
                                    </div>
                                </div>
                            `;
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

            //  Re-inicializa Flowbite cada vez que DataTables repinta
            table.on('draw', function () {
                if (typeof window.initFlowbite === "function") {
                    window.initFlowbite();
                }
            });
        }

        // 🔄 Botón de recargar
            $("#reloadTable").on("click", function() {
                $("#radioNinguno").prop("checked", true);
                $("#filtroMes, #filtroRango").addClass("hidden");

                $("#mes").val(mesActual);
                $("#fechaInicio").val(fechaActual);
                $("#fechaFin").val(fechaActual);

                filtros = {};
                table.ajax.reload();
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

                filtros = {};

                if (tipo === "MES") {
                    filtros.filtro = "MES";
                    filtros.mes = $("#mes").val();
                }

                if (tipo === "RANGO") {
                    filtros.filtro = "RANGO";
                    filtros.fechaInicio = $("#fechaInicio").val();
                    filtros.fechaFin = $("#fechaFin").val();
                }

                if (tipo === "NINGUNO") {
                    filtros.filtro = null;
                }

                table.ajax.reload();
            });
    });
</script>
@if (Session::has('id'))
    <script type="text/javascript">
        var id = {{ session('id') }};
        setTimeout(function() {
            window.open("{{ url('/ticket-anticipo') }}/" + id, '_blank');
        }, 200);
        <?php Session::forget('id'); ?>
    </script>
@endif
@stop

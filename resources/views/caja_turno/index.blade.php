@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Caja-Turno',
        ],
    ],
])

@section('css')

@stop

@section('content')
@section('action')
    <a href="{{ route('admin.caja.turno.create') }}"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Nuevo</a>
@endsection
<?php
$fechaActual = date('Y-m-d');
?>
<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
             <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
            <button id="reloadTable"
                class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Recargar Tabla
            </button>
        </div>

        <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
            <form id="filtroForm" name="filtroForm" action="{{ route('admin.caja.turno.index') }}" method="GET">
                <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filtro por
                            mes</label>
                        <input type="month" name="mes" id="mes" step="1"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            value="{{ isset($mes) ? $mes : $now->format('Y-m') }}" onchange="this.form.submit()">
                    </div>
                </div>
            </form>
        </div>



        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">

            <table id="caja-turno" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Turno</th>
                        <th>Efectivo Inicial</th>
                        <th>Efectivo Calculado</th>
                        <th>Efectivo Real</th>
                        <th>Diferencia</th>
                        <th>Fecha Apertura</th>
                        <th>Fecha Cierre</th>
                        <th>Estado</th>
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
        var table = $('#caja-turno').DataTable({
            responsive: true,
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            "order": [
                [0, "desc"]
            ],
            "ajax": {
                "url": "{{ route('admin.caja.turno.index') }}",
                "data": function(d) {
                    d.mes = $('#mes').val(); // enviar el mes seleccionado
                },
                "dataSrc": ""
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "usuario"
                },
                {
                    "data": "turno"
                },
                {
                    "data": "efectivo_inicial"
                },
                {
                    "data": "efectivo_calculado"
                },
                {
                    "data": "efectivo_real"
                },
                {
                    "data": "diferencia"
                },
                {
                    "data": "fecha_apertura"
                },
                {
                    "data": "fecha_cierre"
                },
                {
                    "data": "estado",
                    "render": function(data) {
                        if (data === 'abierto') {
                            return '<span class="bg-green-100 text-green-800 px-2 py-0.5 rounded">Abierto</span>';
                        }
                        return '<span class="bg-red-100 text-red-800 px-2 py-0.5 rounded">Cerrado</span>';
                    }
                }
            ]
        });

        // Recargar tabla
        $('#reloadTable').on('click', function() {
            console.log('recarga?');
            table.ajax.reload();
        });

        // Cambiar mes también recarga tabla
        $('#mes').on('change', function() {
            table.ajax.reload();
        });

    });
</script>
@stop

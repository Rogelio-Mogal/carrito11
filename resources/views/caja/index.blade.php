@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Caja',
        ],
    ],
])

@section('css')

@stop

@section('content')
@section('action')
    <a href="{{ route('admin.caja.movimiento.create') }}"
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
            <table id="caja" class="table table-striped" style="width:100%">
                <thead>
                    <tr>

                        <th>ID</th>
                        <th>Origen</th>
                        <th>Monto</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Fecha</th>
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
        var table = caja();

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });

    });

    function caja() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'caja.index',
        };

        // Inicializar DataTable
        return $('#caja').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: true,
            retrieve: true,
            processing: true,
            order: [[5, 'desc']],
            ajax: {
                url: "{{ route('caja.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.origen = postData.origen;
                }
            },
            'columns': [{
                    data: 'id'
                },
                { data: 'origen', name: 'origen' },
                {
                    data: 'monto',
                    render: $.fn.dataTable.render.number(',', '.', 2, '$')
                },
                {
                    data: 'tipo'
                },
                {
                    data: 'motivo'
                },
                {
                    data: 'fecha'
                },
                {
                    data: 'activo',
                    render: function(data) {
                        let color = data === 'ACTIVO' ? 'green' : 'red';
                        return `<span class="px-2 py-1 rounded text-white bg-${color}-600">${data}</span>`;
                    }
                }
            ],
        });
    }
</script>
@stop

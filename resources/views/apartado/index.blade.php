@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Apartado',
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

@section('content')
@section('action')
    <a href="{{ route('admin.apartado.create') }}"
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

            {{--
                <label for="sucursal_filter">Sucursal:</label>
                <select id="sucursal_filter" class="form-select">
                    <option value="">Todas</option>
                    @foreach ($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}" 
                            {{ isset($sucursalUsuario) && $sucursalUsuario == $sucursal->id ? 'selected' : '' }}>
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
                <br>
                --}}

            <table id="apartado" class="table table-striped " style="width:100%">
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
    var editUrl = "{{ route('admin.apartado.show', ':id') }}";
    $(document).ready(function() {
        // Inicializar DataTable
        var table = apartado();

        // RECARGAR TABLA
        $('#reloadTable').on('click', function() {
            $('#loadingOverlay').removeClass('hidden'); // Mostrar overlay
            table.ajax.reload(function() {
                $('#loadingOverlay').addClass('hidden'); // Ocultar overlay después de recargar
            });
        });

        // 🔹 Refrescar tabla al cambiar filtro
        $('#sucursal_filter').on('change', function() {
            table.ajax.reload();
        });

    });

    function apartado() {
        const postData = {
            _token: $('input[name=_token]').val(),
            origen: 'apartado.index',
        };

        // Inicializar DataTable
        return $('#apartado').DataTable({
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
            responsive: true,
            retrieve: true,
            processing: true,
            ajax: {
                url: "{{ route('apartado.index.ajax') }}",
                type: "POST",
                'data': function(d) {
                    d._token = "{{ csrf_token() }}";
                    d.origen = postData.origen;
                }
            },
            'columns': [
                {
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
                        if(data === 'ACTIVO') color = 'green';
                        else if(data === 'PASO_A_VENTA') color = 'blue';
                        else if(data === 'CANCELADO') color = 'red';
                        else if(data === 'LIQUIDADO') color = 'purple';
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
                                 data-popover-target="editar${data}" data-popover-placement="left"
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
    }
</script>

@if (Session::has('id'))
    <script type="text/javascript">
        var id = {{ session('id') }};
        setTimeout(function() {
            window.open("{{ url('/ticket-apartado') }}/" + id, '_blank');
        }, 200);
        <?php Session::forget('id'); ?>
    </script>
@endif

@stop

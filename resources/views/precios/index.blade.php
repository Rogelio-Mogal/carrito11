@extends('layouts.app', [
    'breadcrumb' => [
        [
            'name' => 'Home',
            'url' => route('dashboard'),
        ],
        [
            'name' => 'Precios',
        ],
    ],
])

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
    <div class="flex justify-start space-x-2">
        <a href="{{ url('precios/create?precio=1') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Precio general
        </a>

        <a href="{{ url('precios/create?precio=2') }}"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Precio específico
        </a>
    </div>
@endsection

<div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
    <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <table id="precios" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Público</th>
                        <th>Medio mayoreo</th>
                        <th>Mayoreo</th>
                        <th>Subfamilia</th>
                        <th>Tipo</th>
                        <th>Precio</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($precios as $item)
                        <tr>
                            <td> {{ $item->id }} </td>
                            <td> {{ '$' . number_format($item->desde, 2, '.', ',') }} </td>
                            <td> {{ '$' . number_format($item->hasta, 2, '.', ',') }} </td>
                            @if ($item->tipo_precio == 1)
                                <td> {{ $item->porcentaje_publico }} %</td>
                                <td> {{ $item->porcentaje_medio }} %</td>
                                <td> {{ $item->porcentaje_mayoreo }} %</td>
                            @endif
                            @if ($item->tipo_precio == 2)
                                <td> {{ '$' . number_format($item->especifico_publico, 2, '.', ',') }} </td>
                                <td> {{ '$' . number_format($item->especifico_medio, 2, '.', ',') }} </td>
                                <td> {{ '$' . number_format($item->especifico_mayoreo, 2, '.', ',') }} </td>
                            @endif
                            <td> {{ $item->productoCaracteristica->id == 3 ? '' : $item->productoCaracteristica->nombre }}
                            </td>
                            @if ($item->tipo_precio == 1)
                                <td> General </td>
                            @endif
                            @if ($item->tipo_precio == 2)
                                <td> Especifico </td>
                            @endif
                            <td> {{ $item->precio }} </td>
                            <td>
                                @if ($item->activo == 1)
                                    <a href="#"
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
                                @endif
                                <div id="editar{{ $item->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
                                    <div class="p-2 space-y-2">
                                        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include('precios._modal_edita_precios')
        </div>
    </div>

</div>


@endsection

@section('js')
<script>
    $(document).ready(function() {
        var preciosTable = new DataTable('#precios', {
            responsive: true,
            "language": {
                "url": "{{ asset('/json/i18n/es_es.json') }}"
            },
        });

        // Manejar el clic en el botón para mostrar el modal
        $('#precios tbody').on('click', '.open-modal', function() {
            var id = $(this).data('id');
            showModal(id);
        });

        // Función para mostrar el modal
        function showModal(id) {
            var modal = $('#edit-modal');
            var updateUrl = "{{ route('admin.precios.update', ':id') }}".replace(':id', id);
            modal.find('form').attr('action', updateUrl);
            modal.show();
            precios(id)
        }

        // Manejar el clic en el botón para cerrar el modal
        $('.close-modal').on('click', function() {
            $(this).closest('.modal').hide();
        });

        // Listener for details control
        preciosTable.on('responsive-display', function(e, datatable, row, showHide, update) {
            var rowData = row.data();
            if (showHide) {
                var id = rowData[0];
                $('.open-modal', row.node()).on('click', function() {
                    showModal(id);
                });
            }
        });

        // FUNCION MONEDA
        function formatCurrency(amount) {
            return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
        }

        // FUNCION PARA TRAER EL PRECIO Y GENERAR EL MODAL
        function precios(id) {
            const ajaxData = {
                "_token": "{{ csrf_token() }}",
                id: id,
            };
            var showUrl = "{{ route('admin.precios.show', ':id') }}";
            var showLink = showUrl.replace(':id', id);

            $.ajax({
                url: showLink,
                type: "GET",
                dataType: 'json',
                data: ajaxData,
                success: function(response) {
                    if (response.data) {
                        // Genearar el modal de edición
                        let modal = $('#edit-modal'); // + id);

                        // Cambiar valores del formulario en el modal
                        var updateUrl = "{{ route('admin.precios.update', ':id') }}".replace(':id',
                            id);
                        modal.find('form').attr('action', updateUrl);
                        modal.find('#tipo_precio').val(response.data.tipo_precio);
                        modal.find('#porcentaje_publico').val(response.data.porcentaje_publico);
                        modal.find('#porcentaje_medio').val(response.data.porcentaje_medio);
                        modal.find('#porcentaje_mayoreo').val(response.data.porcentaje_mayoreo);
                        modal.find('#especifico_publico').val(response.data.especifico_publico);
                        modal.find('#especifico_medio').val(response.data.especifico_medio);
                        modal.find('#especifico_mayoreo').val(response.data.especifico_mayoreo);

                        var desdeFormatted = formatCurrency(response.data.desde);
                        var hastaFormatted = formatCurrency(response.data.hasta);

                        // Añadir los valores de "Desde" y "Hasta" en el encabezado del modal
                        modal.find('#desde').text('$'+desdeFormatted);
                        modal.find('#hasta').text('$'+hastaFormatted);

                        // Mostrar el modal
                        modal.removeClass('hidden');
                    } else {
                        // No hay datos, manejar el caso donde no se encuentra el registro
                        console.log('No se encontro el Id')
                    }
                },
                error: function(response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Advertencia',
                        text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                },
            });
        }
    });
</script>
@stop

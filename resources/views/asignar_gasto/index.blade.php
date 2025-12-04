
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
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">

                <form id="filtroForm">
                    <div class="grid grid-cols-12 gap-3">
                        <!-- TIPO DE FILTRO -->
                        <div class="col-span-12">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Tipo de filtro</label>
                            <div class="flex gap-6 items-center">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tipoFiltro" value="NINGUNO" id="radioNinguno" class="w-4 h-4" checked>
                                    <span>Ninguno</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tipoFiltro" value="MES" id="radioMes" class="w-4 h-4">
                                    <span>Por mes</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="tipoFiltro" value="RANGO" id="radioRango" class="w-4 h-4">
                                    <span>Por rango de fechas</span>
                                </label>
                            </div>
                        </div>
                        <!-- FILTRO POR MES -->
                        <div id="filtroMes" class="col-span-4 hidden">
                            <label class="block mb-2 text-sm font-medium text-gray-900">Mes</label>
                            <input type="month" id="mes" class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                            value="{{ isset($mes) ? $mes : $now->format('Y-m') }}" onchange="updateHiddenValue()">
                        </div>
                        <!-- RANGO DE FECHAS -->
                        <div id="filtroRango" class="col-span-8 hidden grid grid-cols-8 gap-3">
                            <div class="col-span-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Fecha inicio</label>
                                <input type="date" id="fechaInicio"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                                  value="{{ $fechaActual }}">
                            </div>
                            <div class="col-span-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900">Fecha fin</label>
                                <input type="date" id="fechaFin"
                                    class="bg-gray-50 border border-gray-300 rounded-lg p-2.5 w-full"
                                  value="{{ $fechaActual }}">
                            </div>
                        </div>
                        <!-- BOTONES -->
                        <div class="col-span-12 flex gap-3 mt-2">
                            <button type="button" id="btnFiltrar"
                                class="text-white bg-green-600 hover:bg-green-700 px-5 py-2 rounded-lg">
                                Filtrar
                            </button>
                            <button type="button" id="reloadTable"
                                class="text-white bg-blue-500 hover:bg-blue-600 px-5 py-2 rounded-lg">
                                Recargar tabla
                            </button>
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
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let tblGastos;
            cargarAsignaGasto();

            function cargarAsignaGasto() {

                if ($.fn.DataTable.isDataTable('#asignar_gasto')) {
                    $('#asignar_gasto').DataTable().clear().destroy();
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

                tblGastos = $('#asignar_gasto').DataTable({
                    processing: true,
                    serverSide: false, // cambiar a true si quieres paginación del lado del servidor
                    responsive: true,
                    order: [], // evita que intente ordenar automático
                    ajax: {
                        url: "{{ route('asignar.gasto.index.ajax') }}",
                        type: "POST",
                        data: function(d){
                            return $.extend(d, {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                origen: "asignar.gasto.index",
                            });
                        }
                    },
                    columns: [
                        { data: 'id'},
                        { data: 'fecha'},
                        { data: 'gasto' },
                        { data: 'tipo' },
                        { data: 'monto' },
                        { data: 'forma_pago' },
                        { data: 'nota' },
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

                // Limpiar valores
                $("#mes").val("");
                $("#fechaInicio").val("");
                $("#fechaFin").val("");

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
                    origen: "asignar.gasto.index",
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

                // 🔥 Ahora SÍ enviamos los datos correctamente al DataTable
                tblGastos.ajax.reload(null, false);
                tblGastos.ajax.params = postData;

                tblGastos.settings()[0].ajax.data = function(d){
                    return $.extend(d, postData);
                };

                tblGastos.ajax.reload();
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
                    confirmButtonText: 'Sí, activar',
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

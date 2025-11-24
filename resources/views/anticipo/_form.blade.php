<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <label for="btn-client" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Clientes
        </label>
        <button data-modal-target="cliente-modal" data-modal-toggle="cliente-modal" id="btn-client"
            class="block w-full text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:yellow-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:focus:ring-yellow-900"
            type="button">
            Buscar
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <label for="btn-client" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Nuevo
        </label>
        <button data-modal-target="nuevo-cliente-modal" data-modal-toggle="nuevo-cliente-modal"
            class="block w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm py-2.5 text-center"
            type="button">
            Nuevo Cliente
        </button>
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
        <input type="text" id="cliente" name="cliente" required
            class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cliente" value="{{ old('cliente', $anticipoApartado->cliente?->full_name) }}" readonly />
        <input type="hidden" name="cliente_id" id="cliente_id"
            value="{{ old('cliente_id', $anticipoApartado->cliente_id) }}">
    </div>

    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="cantidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
        <input type="number" id="cantidad" name="cantidad" min="1" step="1"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cantidad" value="{{ old('cliente', $anticipoApartado->cantidad) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total</label>
        <input type="number" id="total" name="total" min="0" step="0.01"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Total" value="{{ old('cliente', $anticipoApartado->total) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="producto_comun"
            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción</label>
        <textarea id="producto_comun" name="producto_comun" rows="4"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Descripción">{{ old('producto_comun', $anticipoApartado->producto_comun) }}</textarea>
        <input type="hidden" name="producto_id" id="producto_id" value="1">
    </div>

    <!-- Formas de pago -->
    <div class="sm:col-span-12 md:col-span-12 lg:col-span-12">
        <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
        <div class="grid grid-cols-2 gap-4">
            @php
                $metodos = ['Efectivo', 'TDD', 'TDC', 'Transferencia'];
            @endphp
            @foreach ($metodos as $index => $meto)
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">{{ $meto }}</label>
                    <input type="hidden" name="formas_pago[{{ $index }}][metodo]" value="{{ $meto }}">
                    <input type="number" name="formas_pago[{{ $index }}][monto]" step="any"
                        class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    @include('garantias.partials._modal_clientes')

    <br />
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR ANTICIPO
            @elseif($metodo == 'edit')
                EDITAR ANTICIPO
            @elseif($metodo == 'solucion')
                AGREGAR SOLUCIÓN
            @endif
        </button>
    </div>
</div>
{{-- </div> --}}

@section('js')
    <script>
        let tableClientes;

        $(document).ready(function() {
            clientes();

            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-client').click(async function() {
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargaClientes();
            });

            // SELECCIONO EL CLIENTE DEL DATATABLES
            $('#clientes tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (tableClientes) {
                    var data = tableClientes.row(this).data();

                    if (data) {
                        var idCliente = data['id'];
                        var nombreCliente = data['full_name'];
                        var tipoPrecio = data['tipo_cliente'];
                        var direccionCliente = data['direccion'];
                        $('#tel1').val(data['telefono']);

                        // Cambiamos los valores de los input
                        $('#cliente_id').val(idCliente);
                        $('#cliente').val(nombreCliente).trigger('change');
                        $('input[name="tipo_precio"][value="' + tipoPrecio + '"]').prop('checked', true)
                            .trigger('change');
                        $('#direccion').val(direccionCliente);

                        $('#cliente').prop("readonly", true);
                        $('#direccion').prop("readonly", true);
                        $('#name_personalizado').val(0).trigger('change');

                        // Deja que Flowbite lo cierre automáticamente
                        document.querySelector('[data-modal-toggle="cliente-modal"]').click();

                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                }
            });

            // === REGISTRAR NUEVO CLIENTE (AJAX) ===
            $(document).on("submit", "#form-nuevo-cliente", function(e) {
                e.preventDefault();

                // Obtener valores de los inputs del modal
                let NombreCli = $("#name_modal").val();
                let ApellidoCli = $("#last_name_modal").val();
                let TelCli = $("#telefono_modal").val();
                let TipoCli = $("#tipo_cliente_modal").val();
                let EjecutivoCli = $("#ejecutivo_id_modal").val();

                $.ajax({
                    url: "{{ route('clientes.store.ajax') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: NombreCli,
                        last_name: ApellidoCli,
                        telefono: TelCli,
                        tipo_cliente: TipoCli,
                        ejecutivo_id: EjecutivoCli
                    },
                    success: function(data) {
                        // cerrar modal
                        $('[data-modal-hide="nuevo-cliente-modal"]').click();

                        // llenar inputs principales del formulario de garantía
                        $("#cliente_id").val(data.id);
                        $("#cliente").val(data.full_name).prop("readonly", true);
                        $("#tel1").val(data.telefono);

                        // limpiar modal
                        $("#form-nuevo-cliente")[0].reset();

                        Swal.fire({
                            icon: "success",
                            title: "Cliente registrado",
                            text: "El cliente se guardó correctamente.",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            let mensajes = Object.values(xhr.responseJSON.errors).flat().join(
                                '<br>');
                            Swal.fire({
                                icon: "error",
                                title: "Error de validación",
                                html: mensajes,
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                },
                                buttonsStyling: false
                            });
                        } else {
                            // Swal.fire("Error", "No se pudo registrar el cliente.", "error");

                            Swal.fire({
                                icon: "error",
                                title: "Error inesperado",
                                html: "No se pudo registrar el cliente.",
                                confirmButtonText: "OK",
                                customClass: {
                                    confirmButton: "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5"
                                },
                                buttonsStyling: false
                            });

                        }
                    }
                });
            });

            // PARA VALIDAR LOS NUMEROS DE SERIE
            var submitBtn = document.getElementById('btn-submit');
            var form = submitBtn.form;

            // Agrega un evento click al botón de envío
            //submitBtn.addEventListener('click', function(event) {
            $(document).on('click', '#btn-submit', function(event) {
                // Prevenir el envío del formulario por defecto
                if (form.checkValidity()) {
                    event.preventDefault();
                    var valida = 1;
                    var modoSolucion = $("#modo_solucion").val() == 1;

                    // Verificar si el cliente es "CLIENTE PÚBLICO" (ID = 1)
                    var clienteId = $("#cliente_id").val();
                    if (clienteId == 1 && !modoSolucion) {
                        Swal.fire({
                            icon: "warning",
                            title: "Cliente no permitido",
                            html: "No se puede seleccionar CLIENTE PÚBLICO para esta acción.",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                        return; // Detener envío
                    }

                    // 2. Validar cantidad
                    var cantidad = parseInt($("#cantidad").val()) || 0;
                    if (cantidad < 1) {
                        Swal.fire({
                            icon: "warning",
                            title: "Cantidad inválida",
                            text: "La cantidad debe ser al menos 1.",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                        return;
                    }

                    // 3. Validar total
                    var total = parseFloat($("#total").val()) || 0;
                    if (total <= 0) {
                        Swal.fire({
                            icon: "warning",
                            title: "Total inválido",
                            text: "El total debe ser mayor a 0.",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                        return;
                    }

                    // 4. Validar suma de formas de pago
                    var sumaPagos = 0;
                    $(".forma-pago").each(function() {
                        var monto = parseFloat($(this).val()) || 0;
                        if (monto < 0) monto = 0; // evitar negativos accidentales
                        sumaPagos += monto;
                    });

                    // === Nueva validación: la suma debe ser mayor a 0 ===
                    if (sumaPagos <= 0) {
                        Swal.fire({
                            icon: "warning",
                            title: "Sin pago registrado",
                            text: "Debes capturar al menos una forma de pago con monto mayor a $0.00.",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                        return;
                    }

                    // Validar que no exceda el total
                    if (sumaPagos > total) {
                        Swal.fire({
                            icon: "warning",
                            title: "Formas de pago inválidas",
                            text: "La suma de las formas de pago no puede ser mayor al total (" +
                                total.toFixed(2) + ").",
                            confirmButtonText: "OK",
                            customClass: {
                                confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                            },
                            buttonsStyling: false
                        });
                        return;
                    }

                    // Si pasa todas las validaciones, enviar formulario
                    $("#btn-submit").attr("disabled", true);
                    form.submit();
                } else {
                    form.reportValidity();
                }
            });

            // Evitar el envío del formulario al presionar Enter
            $(document).on('keypress', 'input, select', function(e) {
                if (e.which == 13) {
                    e.preventDefault(); // bloquea enter en inputs y selects
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

            // Función para recargar o inicializar la tabla Clientes
            async function recargaClientes() {
                if ($.fn.DataTable.isDataTable('#clientes')) {
                    // Recargar los datos sin redibujar la tabla
                    await tableClientes.ajax.reload(null, false);
                    tableClientes.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await clientes();
                }
            }

            // OBTEMGO LOS CLIENTES POR AJAX
            async function clientes() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'clientes.cotizaciones',
                };

                if ($.fn.DataTable.isDataTable('#clientes')) {
                    $('#clientes').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                tableClientes = $('#clientes').DataTable({
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                    responsive: true,
                    retrieve: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('clientes.index.ajax') }}",
                        type: "POST",
                        'data': function(d) {
                            d._token = postData._token;
                            d.origen = postData.origen;
                        }
                    },
                    'columns': [{
                            data: 'id',
                            name: 'id',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'tipo_cliente',
                            name: 'tipo_cliente'
                        },
                        {
                            data: 'direccion',
                            name: 'direccion',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'telefono',
                            name: 'telefono',
                            visible: false
                        },
                    ],
                });
            }

            // FORMATO MONEDA
            function formatToFloat(data) {
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2
                }).format(data);
            };

        });
    </script>
@stop

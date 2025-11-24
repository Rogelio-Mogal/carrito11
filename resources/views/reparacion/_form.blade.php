<x-validation-errors class="mb-4" />
@php
    // Detectar si estamos en modo 'solución' o no
    $modoSolucion = isset($metodo) && $metodo === 'solucion';
@endphp

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

    <input type="hidden" id="modo_solucion" value="{{ $modoSolucion ? 1 : 0 }}">
    {{-- BLOQUE SOLO LECTURA --}}
    @if ($modoSolucion)
        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
            <p class="text-xl text-gray-900 dark:text-white">{{ $reparacion->cliente?->full_name }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
            <p class="text-xl text-gray-900 dark:text-white">{{ $reparacion->tel1 }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono 2</label>
            <p class="text-xl text-gray-900 dark:text-white">{{ $reparacion->tel2 }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Equipo</label>
            <p class="text-xl text-gray-900 dark:text-white">
                {{ $reparacion->equipo }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del fallo</label>
            <p class="text-xl text-gray-900 dark:text-gray-400">{{ $reparacion->fallo }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Información adicional</label>
            <p class="text-xl text-gray-900 dark:text-gray-400">{{ $reparacion->nota_adicional }}</p>
        </div>
        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="solucion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Solución</label>
            <textarea id="solucion" name="solucion" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Descripción de la solución">{{ old('solucion', $reparacion->solucion) }}</textarea>
        </div>

        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="recomendaciones"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Recomendación</label>
            <textarea id="recomendaciones" name="recomendaciones" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Descripción de la recomendación">{{ old('recomendaciones', $reparacion->recomendaciones) }}</textarea>
        </div>

        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="nota_general" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nota
                general</label>
            <textarea id="nota_general" name="nota_general" rows="4"
                class="block  w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Nota general">{{ old('nota_general', $reparacion->nota_general) }}</textarea>
        </div>



        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
            <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTO EN REPARACIÓN</h4>
        </div>
        <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
            <label for="btn-product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Busqueda
            </label>
            <button data-modal-target="producto-modal" data-modal-toggle="producto-modal" id="btn-product"
                data-target-table="item_table_0" data-index="0"
                class="btn-product block w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-blue-600 dark:hover:bg-purple-700 dark:focus:ring-blue-800"
                type="button">
                Productos
            </button>
        </div>

        <!-- ##### MODULO DE PRODUCTOS-PONCHADOS  #########   -->

        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <div class="col-span-12 grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
                <div
                    class="bg-white shadow-md rounded-xl p-3 border border-gray-200 fondo-item grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[400px] overflow-y-auto">
                            <table id="item_table_0"
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-blue-700 uppercase bg-blue-50 dark:bg-blue-700 dark:text-blue-400">

                                    <tr>
                                        <th scope="col" class="px-6 py-3">Cant.</th>
                                        <th scope="col" class="px-6 py-3">Producto</th>
                                        <th scope="col" class="px-6 py-3">Serie</th>
                                        <th scope="col" class="px-6 py-3">P.U.</th>
                                        <th scope="col" class="px-6 py-3">Importe</th>
                                        <th scope="col" class="px-6 py-3">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalle as $item)
                                        <tr data-idponchadoServicio="{{ $item['producto_id'] }}"
                                            class="odd:bg-white odd:dark:bg-gray-500 even:bg-gray-50 even:dark:bg-gray-400 border-b border-gray-100 dark:border-gray-400">
                                            <td>
                                                <input type="number" name="detalles[{{ $loop->index }}][cantidad]"
                                                    min="1" value="{{ $item['cantidad'] }}"
                                                    class="cantVenta w-16 text-center border rounded" />
                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $item['name_producto'] }}
                                                <input type="hidden"
                                                    name="detalles[{{ $loop->index }}][name_producto]"
                                                    value="{{ $item['name_producto'] }}">
                                                <input type="hidden" name="detalles[{{ $loop->index }}][tipo_item]"
                                                    value="{{ $item['tipo_item'] }}">
                                                <input type="hidden" name="detalles[{{ $loop->index }}][producto_id]"
                                                    value="{{ $item['producto_id'] }}" />
                                            </td>

                                            <td>
                                                <textarea name="detalles[{{ $loop->index }}][series]" 
                                                    class="serie border rounded w-full p-1"
                                                    placeholder="Escanee los números de serie (Enter para separar)">{{ $item['series'] }}</textarea>
                                            </td>


                                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu"
                                                data-precio="{{ $item['precio'] }}">
                                                {{ number_format($item['precio'], 2, '.', ',') }} {{-- puedes agregar $ si quieres --}}
                                                <input type="hidden" name="detalles[{{ $loop->index }}][precio]"
                                                    value="{{ $item['precio'] }}">
                                            </td>

                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                                                {{ number_format($item['total'], 2, '.', ',') }}
                                                <input type="hidden" name="detalles[{{ $loop->index }}][total]"
                                                    value="{{ $item['total'] }}" class="total_pp">
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button"
                                                    class="remove font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- ##### FIN MODULO DE PRODUCTOS-PONCHADOS  #########   -->
    @else
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

        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
            <label for="cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
            <input type="text" id="cliente" name="cliente" required
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cliente" value="{{ old('cliente', $reparacion->cliente?->full_name) }}" readonly />
            <input type="hidden" name="cliente_id" id="cliente_id" class="infoCot"
                value="{{ old('cliente_id', $reparacion->cliente_id) }}">
        </div>
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="tel1"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
            <input type="text" id="tel1" name="tel1"
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Teléfono" value="{{ old('tel1', $reparacion->tel1) }}" readonly />
        </div>
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="tel2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono 2
                (opcional)</label>
            <input type="text" id="tel2" name="tel2"
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Teléfono 2 (opcional)" value="{{ old('tel2', $reparacion->tel2) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
            <label for="equipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Equipo
            </label>
            <input type="text" id="equipo" name="equipo"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Equipo" value="{{ old('equipo', $reparacion->equipo) }}">
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label for="fallo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del
                fallo</label>
            <textarea id="fallo" name="fallo" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Descripción del fallo">{{ old('fallo', $reparacion->fallo) }}</textarea>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label for="nota_adicional"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Información adicional</label>
            <textarea id="nota_adicional" name="nota_adicional" rows="4"
                class="block  w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Información adicional">{{ old('nota_adicional', $reparacion->nota_adicional) }}</textarea>
        </div>

        {{--
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTO EN GARANTÍA</h4>
        </div>

        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table id="item_table_1"
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400 ">
                        <tr class="text-center">
                            <th scope="col" class="px-6 py-3">Cantidad</th>
                            <th scope="col" class="px-6 py-3">Producto</th>
                            <th scope="col" class="px-6 py-3">Precio</th>
                            <th scope="col" class="px-6 py-3">Importe</th>
                            <th scope="col" class="px-6 py-3">Opciones</th>
                        </tr>
                    </thead>
                    <tbody id="body_details_table">
                    </tbody>
                </table>
            </div>
        </div>
        --}}
    @endif




    <!-- Modal -->
    @include('ventas.partials._modal_productos')
    @include('garantias.partials._modal_clientes')


    <br />
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR REPARACIÓN
            @elseif($metodo == 'edit')
                EDITAR REPARACIÓN
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
        const urlClientesAjax = "{{ route('clientes.store.ajax') }}";

        $(document).ready(function() {
            let tableP = null; //de producto
            clientes();

            // MOSTRAMOS LA LISTA DE LOS DETALLES DE LA COTIZACIÓN, -EDITAR-
            /*let detalle = @json($detalle);

            if (detalle.length > 0) {
                detalle.forEach(item => {
                    agregarProductoGarantia(
                        item.producto_id,
                        item.nombre,
                        item.precio
                    );

                    // Ajustar cantidad e importe
                    let row = $("#body_details_table tr:last");
                    row.find(".cantidad").val(item.cantidad);
                    row.find(".importe").text("$" + parseFloat(item.importe).toFixed(2));
                    row.find(".importe_valor").val(parseFloat(item.importe).toFixed(2));
                });
            }
            */

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

            // AGREGAR PRODUCTO EN COMÚN
            $(document).on("click", "#btn-add-comun", function() {
                let nombreComun = $("#nombre").val().trim();
                if (nombreComun === "") {
                    Swal.fire({
                        icon: "warning",
                        title: "Producto en común",
                        text: "Debes escribir un nombre antes de agregarlo."
                    });
                    return;
                }

                // Usamos el id fijo = 1 para el producto genérico
                let idProducto = 1;
                let precio = 0;

                // Insertar en la tabla dinámica
                agregarProductoGarantia(idProducto, nombreComun, precio);

                // Guardar también en el hidden para el backend
                $("input[name='producto_personalizado']").val(nombreComun);

                // Limpiar input si quieres que quede vacío después
                $("#nombre").val('');
            });

            // Recalcular importe cuando cambie cantidad o precio
            $(document).on("input", ".cantidad, .precio_producto", function() {
                let row = $(this).closest("tr");
                let cantidad = parseFloat(row.find(".cantidad").val()) || 0;
                let precio = parseFloat(row.find(".precio_producto").val()) || 0;
                let importe = cantidad * precio;

                // Actualizar importe visual y oculto
                row.find(".importe").text("$" + importe.toFixed(2));
                row.find(".importe_valor").val(importe.toFixed(2));
            });

            // ELIMINA PRODUCTO
            $(document).on("click", ".btnEliminar", function() {
                $(this).closest("tr").remove();
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

                    /*if (!modoSolucion) {
                        // Verifico si hay elementos en la tabla
                        var numeroDeRegistros = $("#item_table_1 tr").length - 1;
                        //console.log('numeroDeRegistros: ' + numeroDeRegistros);
                        if (numeroDeRegistros <= 0) {
                            console.log('No ha agregado productos, por favor verifique la información.');
                            Swal.fire({
                                icon: "warning",
                                title: 'No ha agregado productos',
                                html: 'Por favor verifique la información.',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                },
                                buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                            });
                            valida = 0;
                        }
                    }*/

                    if (valida === 1) {
                        console.log('AQUI SE ENVIA EL FORMULARIO');
                        $("#btn-submit").attr("disabled", true);
                        form.submit();
                    }
                } else {
                    form.reportValidity();
                }
            });

            // Evitar el envío del formulario al presionar Enter
            $(document).on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            });

            // Esto lo colocas solo una vez al inicio
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                recalcularTotalTabla('item_table_0');
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

            // Cerrar al hacer clic en el fondo
            $(document).on('click', '.producto-modal', function(e) {
                if ($(e.target).is('.producto-modal')) {
                    $(this).addClass('hidden');
                }
            });

            // Cerrar con el botón de la X
            $(document).on('click', '.close-modal', function() {
                $('.producto-modal').addClass('hidden');
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

            // selecciona el producto del datatable/ modal
            //const $targetTable = $(`#${currentTargetTable}`);
            //let index =  $targetTable.find('tbody tr').length;
            $('#productos tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!tableP) {
                    console.error("La tabla no está inicializada correctamente.");
                    return;
                }

                var data = tableP.row(this).data();
                if (!data) {
                    console.error("No se pudo obtener los datos de la fila.");
                    return;
                }

                var idproducto = data['id'];
                var nombre = data['nombre'];

                var inventario = data.inventario_data || {};
                var requiereSerie = data['serie'];


                var precio_publico = tipoItem === 'PRODUCTO' ?
                    parseFloat(inventario.precio_publico || 0) :
                    parseFloat(inventario.precio_publico || 0);

                var tipoItem = data['tipo'];


                var stock = tipoItem === 'SERVICIO' ?
                    inventario.cantidad :
                    parseFloat(inventario.cantidad || 0);


                // Obtener botón que abrió el modal
                const $botonActivo = $('.producto-modal').data('botonActivo');
                const currentTargetTable = $botonActivo?.attr('data-target-table');
                if (!currentTargetTable) {
                    alert("No se pudo determinar la tabla destino.");
                    return;
                }

                const $targetTable = $(`#${currentTargetTable}`);
                if ($targetTable.length === 0) {
                    alert("No se encontró la tabla destino.");
                    return;
                }

                // Calcular el índice en base a las filas actuales
                let index = $targetTable.find('tbody tr').length;

                // Buscar si ya existe el producto en la tabla por data-idproducto
                var $existingRow = $targetTable.find(`tbody tr[data-idproducto="${idproducto}"]`);
                if ($existingRow.length > 0) {
                    // Si existe, aumentar cantidad y recalcular importe
                    var $inputCant = $existingRow.find('.cantVenta');
                    var cantActual = parseInt($inputCant.val()) || 1;
                    var nuevaCant = cantActual + 1;
                    $inputCant.val(nuevaCant);

                    const importe = (nuevaCant * precio_publico).toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    });

                    //$existingRow.find('.cantVenta').text(importe);
                    $existingRow.find('.cantVenta').attr('max', stock);

                    // Actualizar solo el texto visible del importe, sin borrar el input hidden
                    $existingRow.find('.importe').contents().filter(function() {
                        return this.nodeType === 3; // nodo de texto
                    }).first().replaceWith(importe);

                    // Actualizar el input hidden con el nuevo valor total
                    const totalOculto = nuevaCant * precio_publico;
                    $existingRow.find('input.total_pp').val(totalOculto.toFixed(2));

                } else {
                    // Si no existe, agregar fila nueva con cant=1

                    var html = '';

                    html +=
                        `<tr data-idproducto="${idproducto}" data-inventario='${JSON.stringify(inventario).replace(/'/g,"&apos;")}' class="odd:bg-white odd:dark:bg-gray-500 even:bg-gray-50 even:dark:bg-gray-400 border-b border-gray-100 dark:border-gray-400">`;

                    // Cantidad
                    html += `<td>
                              <input type="number" name="detalles[${index}][cantidad]" min="1" max="${stock}" value="1" class="cantVenta w-16 text-center border rounded"/>
                            </td>`;

                    // Producto (nombre mostrado)
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        ${nombre}
                        <input type="hidden" name="detalles[${index}][name_producto]" value="${nombre}" />
                        <input type="hidden" name="detalles[${index}][producto_id]" value="${idproducto}" />
                        <input type="hidden" name="detalles[${index}][tipo_item]" value="${tipoItem}" />
                    </td>`;

                    // Campo Números de serie
                    if (requiereSerie == 1) {
                        html += `<td class="px-6 py-4">
                            <textarea name="detalles[${index}][series]" 
                                    class="serie w-40 border rounded p-1" 
                                    rows="1" required></textarea>
                        </td>`;
                    } else {
                        html += `<td class="px-6 py-4">
                            <textarea name="detalles[${index}][series]" 
                                    class="serie w-40 border rounded p-1" 
                                    rows="1"></textarea>
                        </td>`;
                    }

                    // Precio Unitario
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu" data-precio="${precio_publico}">
                        ${precio_publico.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}
                        <input type="hidden" name="detalles[${index}][precio]" value="${precio_publico}" />
                    </td>`;

                    // Importe
                    const total = precio_publico * 1;
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                        ${total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}
                        <input type="hidden" name="detalles[${index}][total]" value="${total}" class="total_pp"/>
                    </td>`;

                    // Botón eliminar
                    html += `<td class="px-6 py-4">
                        <button type="button" class="remove font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                    </td>`;

                    html += `</tr>`;

                    $targetTable.find('tbody').append(html);

                    // INCREMENTAR el índice después de usarlo
                    index++;
                }

                // Ocultar modal y limpiar overlays
                $(".producto-modal").addClass('hidden');
                $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove();

                // Recalcular el total de la tabla completa
                recalcularTotalTabla('item_table_0');
            });
            //VERIFICAMOS NUMEROS DE SERIES PARA NO REPETIRLAS
            $(document).on('keydown', '.serie', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    var textarea = $(this);
                    var row = textarea.closest("tr");
                    var cantidad = parseInt(row.find(".cantVenta").val());

                    // Solo validar si el producto requiere número de serie
                    var requiereSerie = row.find('textarea.serie').prop('required');

                    // Separar y limpiar espacios
                    var series = textarea.val().split('|').map(s => s.trim()).filter(Boolean);

                    // 🔹 Revisar duplicado del último ingresado
                    var latestValue = series[series.length - 1];
                    var allSeries = [];
                    $(".serie").each(function() {
                        var values = $(this).val().split('|').map(s => s.trim()).filter(Boolean);
                        allSeries = allSeries.concat(values);
                    });

                    var countLatest = allSeries.filter(s => s === latestValue).length;
                    if (countLatest > 1) {
                        Swal.fire({
                            icon: "warning",
                            title: "Número de serie duplicado",
                            html: "El número de serie <b>" + latestValue + "</b> ya fue capturado."
                        });
                        // Eliminar solo el último
                        series.pop();
                    }

                    // 🔹 Validar que no se exceda la cantidad
                    if (series.length > cantidad) {
                        Swal.fire({
                            icon: "warning",
                            title: "Número de series excede la cantidad",
                            html: "Se eliminará el último número para coincidir con la cantidad: " +
                                cantidad
                        });
                        series.pop();
                    }

                    // 🔹 Validar que coincida cantidad si requiere serie
                    if (requiereSerie && series.length < cantidad) {
                        Swal.fire({
                            icon: "warning",
                            title: "Número de series insuficientes",
                            html: "Se requieren exactamente <b>" + cantidad +
                                "</b> números de serie."
                        });
                    }

                    // Eliminar duplicados internos pero mantener orden
                    series = series.filter((s, i) => series.indexOf(s) === i);

                    // Actualizar campo
                    textarea.val(series.join('|') + (series.length ? '|' : ''));

                    // Colocar cursor al final
                    textarea.focus();
                    textarea[0].setSelectionRange(textarea.val().length, textarea.val().length);

                    // Habilitar botón de envío solo si la cantidad coincide (cuando requiere serie)
                    if (requiereSerie) {
                        $("#btn-submit").toggle(series.length === cantidad);
                    } else {
                        $("#btn-submit").prop('disabled', false);
                    }
                }
            });

            $(document).on('click', '.btn-product', async function() {
                const $button = $(this);

                // Guardar el botón directamente en el modal
                $('.producto-modal').data('botonActivo', $button);

                currentTargetTable = $button.data('target-table');

                if ($(".producto-modal").hasClass('hidden')) {
                    $(".producto-modal").removeClass('hidden');
                }

                await recargaProductoTabla();
            });

            // RECALCULA EL TOTAL POR CAMBIO DE PIEZAS
            // Detectar cambios en la cantidad
            $(document).on('input', '.cantVenta', function() {
                const $input = $(this);
                const $row = $input.closest('tr');

                const cantidad = parseFloat($input.val()) || 0;
                const precio = parseFloat($row.find('.pu').attr('data-precio')) || 0;

                const nuevoImporte = cantidad * precio;

                // Actualizar solo el nodo de texto de la celda .importe (sin borrar el input hidden)
                $row.find('.importe').contents().filter(function() {
                    return this.nodeType === 3; // nodo de texto
                }).first().replaceWith(nuevoImporte.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                // Actualizar el valor del input hidden con class="total_pp"
                $row.find('input.total_pp').val(nuevoImporte.toFixed(2));

                // Recalcular el total de la tabla completa
                recalcularTotalTabla('item_table_0');
            });

            // Función para recargar o inicializar la tabla PRODUCTOS
            async function recargaProductoTabla() {
                if ($.fn.DataTable.isDataTable('#productos')) {
                    // Recargar los datos sin redibujar la tabla
                    await tableP.ajax.reload(null, false);
                    //tableP.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await productos();
                }
            }

            // OBTENGO LOS PRODUCTOS POR AJAX
            async function productos() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'productos.ventas',
                };

                if ($.fn.DataTable.isDataTable('#productos')) {
                    $('#productos').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                tableP = $('#productos').DataTable({
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                    responsive: true,
                    retrieve: true,
                    processing: true,
                    ajax: {
                        url: "{{ route('productos.index.ajax') }}",
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
                            data: 'image',
                            render: function(data, type, row) {
                                return '<img class="h-auto max-w-20 sm:max-w-20 md:max-w-40 lg:max-w-40 object-cover object-center" src="' +
                                    data + '" alt="">';
                            }
                        },
                        /*
                        {
                            data: null,
                            render: function(data, type, row) {
                                if (row.tipo === 'PRODUCTO') {
                                    return row.inventario_usuario && row.inventario_usuario.precio_publico !== undefined
                                        ? row.inventario_usuario.precio_publico
                                        : 'N/D';
                                } else if (row.tipo === 'SERVICIO') {
                                    return row.precio !== undefined ? row.precio : 'N/D';
                                }
                                return 'N/D';
                            },
                            name: 'precio_publico'
                        },
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: null,
                             render: function(data, type, row) {
                                return row.inventario_usuario && row.inventario_usuario.cantidad !== undefined
                                    ? row.inventario_usuario.cantidad
                                    : 0; // o puedes mostrar "Sin stock", "-"
                            },
                            name: 'stock'
                        },
                        */
                        {
                            data: null,
                            render: function(data, type, row) {
                                // Tomamos siempre el inventario_data
                                let inventario = row.inventario_data || {};

                                // Precio público según inventario_data
                                let precio = inventario.precio_publico !== undefined ?
                                    inventario.precio_publico : 'N/D';
                                return typeof precio === 'number' ? precio.toLocaleString(
                                    'es-MX', {
                                        style: 'currency',
                                        currency: 'MXN'
                                    }) : precio;
                            },
                            name: 'precio_publico'
                        },
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                // Stock unificado
                                let inventario = row.inventario_data || {};
                                let stock = inventario.cantidad !== undefined ? inventario
                                    .cantidad : 0;
                                return stock;
                            },
                            name: 'stock'
                        },

                        {
                            data: 'codigo_barra',
                            name: 'codigo_barra'
                        },
                        {
                            data: 'tipo',
                            name: 'tipo'
                        },
                        {
                            data: 'serie',
                            render: function(data) {
                                return data == 1 ?
                                    '<span class="text-green-600 font-bold">Sí</span>' :
                                    '<span class="text-gray-500">No</span>';
                            },
                            name: 'serie'
                        }
                    ],
                });
            }

            // calcula el total de la venta
            function recalcularTotalTabla(targetTableId) {
                let total = 0;

                $(`#${targetTableId} tbody tr`).each(function() {
                    const $row = $(this);
                    const precio = parseFloat($row.find('.pu').attr('data-precio')) || 0;
                    const cantidad = parseFloat($row.find('.cantVenta').val()) || 0;
                    let subtotal = cantidad * precio;
                    total += subtotal;

                    // Actualizar el input hidden del total por producto sin reemplazarlo
                    const $inputTotal = $row.find('input.total_pp');
                    $inputTotal.val(subtotal.toFixed(2));

                    // Solo actualizar el texto visible del subtotal en la celda .importe
                    $row.find('.importe').contents().filter(function() {
                        return this.nodeType === 3; // solo el nodo de texto visible
                    }).first().replaceWith(subtotal.toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    }));
                });

                // Actualizar visualmente el total
                const totalFormateado = total.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                });
                $('span[data-total]').text(totalFormateado);
                $('#total_mostrado').text(totalFormateado);
                $('#total_venta').val(total.toFixed(2));
            }


        });
    </script>
    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-reparacion') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop

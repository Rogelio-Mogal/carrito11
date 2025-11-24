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
            <p class="text-xl text-gray-900 dark:text-white">{{ $garantia->cliente?->full_name }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
            <p class="text-xl text-gray-900 dark:text-white">{{ $garantia->tel1 }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono 2</label>
            <p class="text-xl text-gray-900 dark:text-white">{{ $garantia->tel2 }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Folio de la Venta</label>
            <p class="text-xl text-gray-900 dark:text-white">
                {{ $garantia->venta?->folio ?? $garantia->folio_venta_text }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del fallo</label>
            <p class="text-xl text-gray-900 dark:text-gray-400">{{ $garantia->descripcion_fallo }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Información adicional</label>
            <p class="text-xl text-gray-900 dark:text-gray-400">{{ $garantia->informacion_adicional }}</p>
        </div>

        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTO EN GARANTÍA</h4>
        </div>

        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="text-center">
                            <th class="px-6 py-3">Cantidad</th>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3">Precio</th>
                            <th class="px-6 py-3">Importe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalle as $item)
                            <tr class="text-center text-gray-900 dark:text-gray-400">
                                <td>{{ $item['cantidad'] }}</td>
                                <td>{{ $item['nombre'] }}</td>
                                <td>{{ $item['precio'] }}</td>
                                <td>{{ $item['importe'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- BLOQUE EDITABLE PARA AGREGAR LA SOLUCIÓN --}}
        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4 mt-2">
            <label for="solucion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Solución de la garantía
            </label>
            <select id="solucion" name="solucion"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                required>
                <option value="" {{ old('solucion', $garantia->solucion) == '' ? 'selected' : '' }}>Seleccione
                    una opción</option>
                <option value="Nota de crédito"
                    {{ old('solucion', $garantia->solucion) == 'Nota de crédito' ? 'selected' : '' }}>Nota de crédito
                </option>
                <option value="Cambio físico"
                    {{ old('solucion', $garantia->solucion) == 'Cambio físico' ? 'selected' : '' }}>Cambio físico
                </option>
                <option value="No procede"
                    {{ old('solucion', $garantia->solucion) == 'No procede' ? 'selected' : '' }}>No procede</option>
            </select>
        </div>

        <div class="sm:col-span-12 lg:col-span-8 md:col-span-8 mt-2">
            <label for="nota_solucion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Detalle / Nota de la solución
            </label>
            <textarea id="nota_solucion" name="nota_solucion" rows="3"
                class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                placeholder="Escribe el detalle de la solución" required>{{ old('nota_solucion', $garantia->nota_solucion) }}</textarea>
        </div>

        {{-- BLOQUE CAMBIO DE PIEZA --}}
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 mt-4" id="nota_credito_container"
            style="display:none;">
            <h4 class="text-xl font-bold dark:text-white text-center">Producto para cambio físico</h4>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-2">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400">
                        <tr class="text-center">
                            <th class="px-6 py-3">Cantidad</th>
                            <th class="px-6 py-3">Producto</th>
                            <th class="px-6 py-3">Precio</th>
                            <th class="px-6 py-3">Importe</th>
                        </tr>
                    </thead>
                    <tbody id="body_nota_credito">
                        <!-- Aquí se inyecta el producto desde JS -->
                    </tbody>
                </table>
            </div>
        </div>
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

        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
            <input type="text" id="cliente" name="cliente" required
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cliente" value="{{ old('cliente', $garantia->cliente?->full_name) }}" readonly />
            <input type="hidden" name="cliente_id" id="cliente_id" class="infoCot"
                value="{{ old('cliente_id', $garantia->cliente_id) }}">
            <input type="hidden" name="name_personalizado" class="infoCot" id="name_personalizado" value="0">
            <input type="hidden" name="cotizacionId" id="cotizacionId"
                value="0{{ old('cotizacionId', $garantia->id) }}">
        </div>
        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
            <label for="tel1"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
            <input type="text" id="tel1" name="tel1"
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Teléfono" value="{{ old('tel1', $garantia->tel1) }}" readonly />
        </div>
        <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
            <label for="tel2" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono 2
                (opcional)</label>
            <input type="text" id="tel2" name="tel2"
                class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Teléfono 2 (opcional)" value="{{ old('tel2', $garantia->tel2) }}" />
        </div>

        <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
            <label for="btn-product" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Productos
            </label>
            <button data-modal-target="producto-modal" data-modal-toggle="producto-modal" id="btn-product"
                class="block w-full text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                type="button">
                Buscar
            </button>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="codigo_barra_p" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código de
                barra</label>
            <input type="text" id="codigo_barra_p" name="codigo_barra_p"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ingrese código de barra" value="" />
        </div>

        <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
            <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Producto en común
            </label>
            <div class="flex space-x-2">
                <input type="text" id="nombre" name="producto_personalizado"
                    class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Producto en común"
                    value="{{ old('producto_personalizado', $garantia->producto_personalizado) }}" />

                <button type="button" id="btn-add-comun"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2">
                    Agregar
                </button>
            </div>
        </div>

        <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="folio_venta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Folio de la Venta (opcional)
            </label>
            <input type="text" id="folio_venta" name="folio_venta"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Escribe el folio de la venta"
                value="{{ old('folio_venta', $garantia->venta?->folio ?? $garantia->folio_venta_text) }}">
            <input type="hidden" id="venta_id" name="venta_id"
                value="{{ old('venta_id', $garantia->venta_id) }}">
            <p class="text-xs text-gray-500 mt-1">Si no conoces el folio, puedes dejarlo vacío.</p>
            <!-- Mensaje dinámico -->
            <p id="mensaje_venta" class="text-sm mt-1"></p>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label for="descripcion_fallo"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del fallo</label>
            <textarea id="descripcion_fallo" name="descripcion_fallo" rows="4"
                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Descripción del fallo">{{ old('descripcion_fallo', $garantia->descripcion_fallo) }}</textarea>
        </div>

        <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
            <label for="informacion_adicional"
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Información adicional</label>
            <textarea id="informacion_adicional" name="informacion_adicional" rows="4"
                class="block  w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Información adicional">{{ old('informacion_adicional', $garantia->informacion_adicional) }}</textarea>
        </div>

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
    @endif




    <!-- Modal -->
    @include('producto-servicio._modal_productos')
    @include('garantias.partials._modal_clientes')


    <br />
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR GARANTÍA
            @elseif($metodo == 'edit')
                EDITAR GARANTÍA
            @elseif($metodo == 'solucion')
                AGREGAR SOLUCIÓN
            @endif
        </button>
    </div>
</div>
{{-- </div> --}}

@section('js')
    <script>
        let tableProductos;
        let tableClientes;
        const urlClientesAjax = "{{ route('clientes.store.ajax') }}";

        $(document).ready(function() {
            productos(); // Llama a la función para inicializar DataTable
            clientes();

            // MOSTRAMOS LA LISTA DE LOS DETALLES DE LA COTIZACIÓN, -EDITAR-
            let detalle = @json($detalle);

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

            // MUESTRA EL MODAL DE LOS PRODUCTOS
            $('#btn-product').click(async function() {
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargaProductos();
            });

            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-client').click(async function() {
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargaClientes();
            });

           

            // FUNCIÓ PARA INSERTAR PRODUCTO EN TABLA DINAMICA:
            function agregarProductoGarantia(idProducto, nombreProducto, precio) {
                // limpiar la tabla (permitir solo 1 producto)
                $("#body_details_table").empty();

                // fila html
                let row = `
                    <tr class="text-center">
                        <!-- Cantidad -->
                        <td>
                            <input type="number" name="cantidad" value="1" min="1" 
                                class="w-20 text-center border rounded cantidad" />
                        </td>

                        <!-- Producto -->
                        <td>
                            ${nombreProducto}
                            <input type="hidden" name="producto_id" value="${idProducto}" class="producto_id">
                        </td>

                        <!-- Precio editable -->
                        <td>
                            <input type="number" name="precio_producto" 
                                value="${parseFloat(precio).toFixed(2)}"
                                step="0.01" min="0" 
                                class="w-28 text-right border rounded precio_producto" />
                        </td>

                        <!-- Importe (calculado) -->
                        <td>
                            <span class="importe">$${(1 * parseFloat(precio)).toFixed(2)}</span>
                            <input type="hidden" name="importe" value="${(1 * parseFloat(precio)).toFixed(2)}" class="importe_valor">
                        </td>

                        <!-- Opciones -->
                        <td>
                            <button type="button" class="btnEliminar bg-red-600 text-white px-2 py-1 rounded">
                                Quitar
                            </button>
                        </td>
                    </tr>
                `;

                // insertar
                $("#body_details_table").append(row);
            }

            // SELECCIONO EL PRODUCTO DEL DATATABLES
            $('#productos tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                var data = tableProductos.row(this).data();
                if (!data) return;

                let idProducto = data['id'];
                let nombreProducto = data['nombre'];
                let precio = data['inventario'] ? data['inventario']['precio_publico'] : 0;


                // Ocultar modal y limpiar overlays
                //$("#producto-modal").addClass('hidden');
                //$('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove();

                // agregar a tabla dinámica
                agregarProductoGarantia(idProducto, nombreProducto, precio);

                // 👇 Deja que Flowbite lo cierre automáticamente
                document.querySelector('[data-modal-toggle="producto-modal"]').click();



            });

            //BUSCAMOS POR CÓDIGO DE BARRAS
            $('#codigo_barra_p').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    let $input = $(this);
                    let codigo = $(this).val().trim();
                    if (codigo === '') return;
                    console.log('codigo: '+codigo);
                    let tipoBusqueda = 'garantias'; // o 'cotizaciones', 'apartados', etc.

                    $.ajax({
                        url: '/productos/buscar-para-tabla',
                        method: 'GET',
                        data: { 
                            codigo, 
                            tipo_busqueda: tipoBusqueda,
                            _token: $('meta[name="csrf-token"]').attr('content') 
                        },
                        success: function(res) {
                            if (res.data.length > 0) {
                                // Llamar a tu función para insertar en la tabla dinámica
                                let producto = res.data[0];
                                agregarProductoGarantia(
                                    producto.id,
                                    producto.nombre,
                                    producto.precio_publico // o el campo que uses como precio
                                );
                            } else {
                                alert('Producto no encontrado');
                            }
                            $input.val(''); // 🔹 limpiar el campo al final
                            $input.focus();
                        }
                    });
                }
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

            //BUSCAMOS EL FOLIO DE LA VENTA
            $('#folio_venta').on('keydown', function(e) {
                if (e.which === 13) { // Enter
                    e.preventDefault(); // Evita enviar el formulario
                    let folio = $(this).val().trim();
                    let mensaje = $('#mensaje_venta');

                    if (!folio) {
                        mensaje.text('').removeClass('text-green-600 text-red-600');
                        $('#venta_id').val('');
                        return;
                    }

                    $.ajax({
                        url: '/buscar-venta',
                        type: 'GET',
                        data: {
                            folio: folio
                        },
                        success: function(response) {
                            if (response.venta) {
                                $('#venta_id').val(response.venta.id);
                                mensaje
                                    .text(
                                        `Venta encontrada: ${response.venta.cliente} - $${response.venta.total}`
                                    )
                                    .removeClass('text-red-600')
                                    .addClass('text-green-600');
                            } else {
                                $('#venta_id').val('');
                                mensaje
                                    .text('No se encontró la venta')
                                    .removeClass('text-green-600')
                                    .addClass('text-red-600');
                            }
                        },
                        error: function() {
                            $('#venta_id').val('');
                            mensaje
                                .text('Error al buscar la venta')
                                .removeClass('text-green-600')
                                .addClass('text-red-600');
                        }
                    });
                }
            });

            // Cuando cambia la solución de la garantía
            $('#solucion').on('change', function() {
                let solucion = $(this).val();
                let detalle = @json($detalle);

                if (solucion === 'Cambio físico') {
                    if (detalle.length > 0 && detalle[0].cantidad > 0) {
                        let item = detalle[0];

                        $.ajax({
                            url: "{{ route('garantia.verificar-cambio') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                producto_id: item.producto_id,
                                cantidad: item.cantidad
                            },
                            success: function(res) {
                                if (res.success) {
                                    // Limpiamos antes de agregar
                                    $('#body_nota_credito').empty();

                                    // Agregamos fila
                                    let row = `
                                        <tr class="text-center text-gray-900 dark:text-gray-400">
                                            <td>${item.cantidad}</td>
                                            <td>${item.nombre}</td>
                                            <td>${parseFloat(item.precio).toFixed(2)}</td>
                                            <td>${parseFloat(item.importe).toFixed(2)}</td>
                                        </tr>
                                    `;
                                    $('#body_nota_credito').append(row);

                                    $('#nota_credito_container').show();
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Sin inventario suficiente',
                                        text: res.mensaje,
                                        showCancelButton: false,
                                        confirmButtonText: 'OK'
                                    });
                                    $('#solucion').val('').trigger('change');
                                    $('#nota_credito_container').hide();
                                }
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Producto no válido',
                            text: 'No se puede realizar el cambio físico.',
                            showCancelButton: false,
                            confirmButtonText: 'OK'
                        });
                        $('#solucion').val('').trigger('change');
                        $('#nota_credito_container').hide();
                    }
                } else {
                    $('#nota_credito_container').hide();
                    $('#body_nota_credito').empty();
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
                            let mensajes = Object.values(xhr.responseJSON.errors).flat().join('<br>');
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

                    if (!modoSolucion) {
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
                    }

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

            // Función para recargar o inicializar la tabla Productos
            async function recargaProductos() {
                if ($.fn.DataTable.isDataTable('#productos')) {
                    // Recargar los datos sin redibujar la tabla
                    await tableProductos.ajax.reload(null, false);
                    tableProductos.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await productos();
                }
            }

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

            // OBTEMGO LOS PRODUCTOS POR AJAX
            async function productos() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'productos.compras',
                };

                if ($.fn.DataTable.isDataTable('#productos')) {
                    $('#productos').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                tableProductos = $('#productos').DataTable({
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
                        {
                            data: 'nombre',
                            name: 'nombre'
                        },
                        {
                            data: 'inventario.cantidad',
                            defaultContent: 'SIN INVENTARIO'
                        },
                        {
                            data: 'codigo_barra',
                            name: 'codigo_barra',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'serie',
                            name: 'serie',
                            visible: false,
                            searchable: false
                        },
                        {
                            data: 'inventario.precio_publico',
                            name: 'precio_publico',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00'; // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2)
                                    .display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        },
                        {
                            data: 'inventario.precio_medio_mayoreo',
                            name: 'precio_medio_mayoreo',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00'; // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2)
                                    .display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        },
                        {
                            data: 'inventario.precio_mayoreo',
                            name: 'precio_mayoreo',
                            render: function(data, type, row) {
                                // Verificar si el dato es nulo, indefinido o vacío
                                if (data === null || data === undefined || data === '') {
                                    return '$0.00'; // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2)
                                    .display(data);
                                // Agregar el símbolo de pesos al valor formateado
                                return '$ ' + formattedNumber;
                            },
                            defaultContent: '$0.00'
                        }
                    ],
                });
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

<x-validation-errors class="mb-4" />

<div class="grid grid-cols-12 gap-4">
    <!-- Panel principal (productos y controles) -->
    <div class="col-span-12 lg:col-span-9 space-y-2">
        <!-- Aquí van tus controles y la tabla -->
        <h3 class="font-bold text-purple-600 border-b pb-1 mb-3">Apartados</h3>
        <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

            <input type="hidden" id="cliente_autorizado" value="1">
            <input type="hidden" id="cliente_limite_credito" value="0">
            <input type="hidden" id="cliente_monto_pendiente" value="0">

            <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
                <label for="btn-cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Busqueda
                </label>
                <button data-modal-target="cliente-modal" data-modal-toggle="cliente-modal" id="btn-cliente"
                    class="block w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-blue-600 dark:hover:bg-purple-700 dark:focus:ring-blue-800"
                    type="button">
                    Cliente
                </button>
            </div>

            <input type="hidden" id="cliente_id" name="cliente_id"
                value="{{ old('cliente_id', $apartado->cliente_id ?? 1) }}">

            <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
                <label for="nombre_cliente"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Nombre"
                    value="{{ old('nombre_cliente', $apartado->nombre_cliente ?? 'CLIENTE PÚBLICO') }}" readonly />
            </div>


            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label for="tipo_cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Tipo de cliente
                </label>
                <select id="tipo_cliente" name="tipo_cliente"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                        focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5
                        dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                        dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="CLIENTE PÚBLICO">CLIENTE PÚBLICO</option>
                    <option value="CLIENTE MEDIO MAYOREO">CLIENTE MEDIO MAYOREO</option>
                    <option value="CLIENTE MAYOREO">CLIENTE MAYOREO</option>
                </select>
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

            <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                <label for="codigo_barra_p" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código de
                    barra</label>
                <input type="text" id="codigo_barra_p" name="codigo_barra_p"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Ingrese código de barra" value="" />
            </div>

            <input type="hidden" id="ponchado_id" name="ponchado_id"
                value="{{ old('ponchado_id', $apartado->ponchado_id) }}">
            <input type="hidden" id="producto_id" name="producto_id" value="2">


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
                                                    <input type="number"
                                                        name="detalles[{{ $loop->index }}][cantidad]" min="1"
                                                        value="{{ $item['cantidad'] }}"
                                                        class="cantVenta w-16 text-center border rounded" />
                                                </td>
                                                <td
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $item['name_producto'] }}
                                                    <input type="hidden"
                                                        name="detalles[{{ $loop->index }}][name_producto]"
                                                        value="{{ $item['name_producto'] }}">
                                                    <input type="hidden"
                                                        name="detalles[{{ $loop->index }}][servicio_ponchado_id]"
                                                        value="{{ $item['producto_id'] }}">
                                                    <input type="hidden"
                                                        name="detalles[{{ $loop->index }}][tipo_item]"
                                                        value="PONCHADO">
                                                </td>

                                                <td>
                                                    <textarea name="detalles[{{ $loop->index }}][series]" class="serie border rounded w-full p-1"
                                                        placeholder="Escanee los números de serie (Enter para separar)" required>
                                                    </textarea>
                                                </td>


                                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu"
                                                    data-precio="{{ $item['precio'] }}">
                                                    {{ number_format($item['precio'], 2) }}
                                                    <input type="hidden"
                                                        name="detalles[{{ $loop->index }}][precio]"
                                                        value="{{ $item['precio'] }}">
                                                </td>
                                                <td
                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                                                    {{ number_format($item['total'], 2) }}
                                                    <input type="hidden" name="detalles[{{ $loop->index }}][total]"
                                                        value="{{ $item['total'] }}">
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

            <!-- Modal -->
            @include('clientes._modal_clientes')
            @include('ventas.partials._modal_productos')
        </div>
    </div>

    <!-- Panel lateral (formas de pago) -->
    <div class="col-span-12 lg:col-span-3 space-y-2">
        <!-- Paneles de forma de pago y total -->
        <div class="bg-white rounded-xl shadow p-4 space-y-6">

            <!-- Formas de pago -->
            <div>
                <h3 class="font-bold text-green-600 border-b pb-1 mb-3">Forma de pago</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Efectivo</label>
                        <input type="hidden" name="formas_pago[0][metodo]" value="Efectivo">
                        <input type="number" name="formas_pago[0][monto]" id="efectivo" step="any"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>T. Débito</label>
                        <input type="hidden" name="formas_pago[1][metodo]" value="TDD">
                        <input type="number" name="formas_pago[1][monto]" id="debito" step="any"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>T. Crédito</label>
                        <input type="hidden" name="formas_pago[2][metodo]" value="TDC">
                        <input type="number" name="formas_pago[2][monto]" id="credito" step="any"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label>Transferencia</label>
                        <input type="hidden" name="formas_pago[3][metodo]" value="Transferencia">
                        <input type="number" name="formas_pago[3][monto]" id="transferencia" step="any"
                            class="forma-pago w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div id="monto_credito_container" class="hidden sm:col-span-12 lg:col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">Monto a
                            crédito</label>
                        <input type="number" name="monto_credito" id="monto_credito" step="any"
                            class="forma-pago bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                            min="0">
                    </div>
                </div>
            </div>

            <!-- NOTA DE CRÉDITO -->
            <div id="notaCreditoContainer" class="mt-4 hidden">
                <h4 class="font-bold text-purple-600 border-b pb-1 mb-3">Nota de Crédito aplicada</h4>
                <div class="flex justify-between items-center bg-purple-50 border border-purple-300 rounded-lg p-2">
                    <span id="notaCreditoTexto" class="font-bold text-purple-700"></span>
                    <button type="button" id="btnLimpiarNota"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                        Quitar
                    </button>
                </div>
                <!-- 👇 IDs de las notas seleccionadas -->
                <input type="hidden" name="nota_credito_ids" id="nota_credito_ids">
                <!-- 👇 Monto de la nota aplicada -->
                <input type="hidden" name="nota_credito_monto" id="nota_credito_monto">
            </div>

            <!-- ANTICIPO / APARTADO -->
            <div id="anticipoApartadoContainer" class="mt-4 hidden">
                <h4 class="font-bold text-green-600 border-b pb-1 mb-3">
                    Anticipo / Apartado aplicado
                </h4>
                <div class="flex justify-between items-center bg-green-50 border border-green-300 rounded-lg p-2">
                    <span id="anticipoApartadoTexto" class="font-bold text-green-700"></span>
                    <button type="button" id="btnLimpiarAnticipo"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                        Quitar
                    </button>
                </div>
                <!-- 👇 IDs de los anticipos seleccionados -->
                <input type="hidden" name="anticipo_apartado_ids" id="anticipo_apartado_ids">
                <!-- 👇 Monto del anticipo aplicado -->
                <input type="hidden" name="anticipo_apartado_monto" id="anticipo_apartado_monto">
            </div>

            <!-- Totales -->
            <div>
                <h3 class="font-bold text-blue-600 border-b pb-1 mb-3">Total / Cambio</h3>
                <div class="space-y-3">
                    <div>
                        <label for="total" class="block mb-1 text-sm font-medium text-gray-700">Total</label>
                        <span id="total_mostrado" class="font-bold text-xl text-black-500 dark:text-black-400">$
                            0.0</span>
                        <input type="hidden" id="total_venta" name="total_venta">
                    </div>
                    <div>
                        <label class="block text-xl font-medium text-gray-900">Adelanto</label>
                        <span id="adelanto_texto" class="text-green-600 font-bold">

                        </span>
                        <input type="hidden" id="adelanto" name="adelanto" value="0">
                    </div>
                    <div class="sm:col-span-12 lg:col-span-3">
                        <label class="block text-xl font-medium text-gray-900">Faltante</label>
                        <span id="faltante_texto" class="text-red-600 font-bold">$0.00</span>
                        <input type="hidden" id="total_faltante" name="total_faltante">


                    </div>
                    <div class="sm:col-span-12 lg:col-span-3">
                        <label class="block text-xl font-medium text-gray-900">Cambio</label>
                        <span id="cambio_texto" class="text-green-600 font-bold">$0.00</span>
                        <input type="hidden" id="total_cambio" name="total_cambio">
                    </div>

                    <button
                        class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded">
                        Pagar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>




@section('js')
    <script>

        // GENERO LA TABLA DINAMICA, HUBO ERRORES
        window.addEventListener('DOMContentLoaded', function() {
            let detalles = @json(old('detalles', []));

            if (detalles.length > 0) {
                detalles.forEach((item, index) => {
                    if (item.tipo_item === 'PRODUCTO') {
                        agregarProductoDesdeOld(item, index);
                    } else if (item.tipo_item === 'PONCHADO') {
                        agregarPonchadoDesdeOld(item, index);
                    }
                });

                recalcularTotalTabla('item_table_0');
            }
        });

        function agregarProductoDesdeOld(detalle, index) {
            let total = parseFloat(detalle.total).toFixed(2);
            let precio = parseFloat(detalle.precio).toFixed(2);

            let html = `
            <tr data-idproducto="${detalle.producto_id}">
                <td><input type="number" name="detalles[${index}][cantidad]" min="1" value="${detalle.cantidad}" class="cantVenta w-16 text-center border rounded" /></td>
                <td>${detalle.name_producto || ''}<input type="hidden" name="detalles[${index}][name_producto]" value="${detalle.name_producto}" /> <input type="hidden" name="detalles[${index}][producto_id]" value="${detalle.producto_id}" />
                    <input type="hidden" name="detalles[${index}][tipo_item]" value="PRODUCTO" /></td>
                <td class="pu" data-precio="${precio}">${precio} <input type="hidden" name="detalles[${index}][precio]" value="${precio}" /></td>
                <td class="importe">${total} <input type="hidden" name="detalles[${index}][total]" value="${total}" class="total_pp" /></td>
                <td><button type="button" class="remove text-red-600">Eliminar</button></td>
            </tr>
            `;

            $('#item_table_0 tbody').append(html);
        }

        function agregarPonchadoDesdeOld(detalle, index) {
            let total = parseFloat(detalle.total).toFixed(2);
            let precio = parseFloat(detalle.precio).toFixed(2);

            let html = `
            <tr data-idponchadoServicio="${detalle.servicio_ponchado_id}">
                <td><input type="number" name="detalles[${index}][cantidad]" min="1" value="${detalle.cantidad}" class="cantVenta w-16 text-center border rounded" readonly /></td>
                <td>${detalle.name_producto || ''}<input type="hidden" name="detalles[${index}][name_producto]" value="${detalle.name_producto}" /> <input type="hidden" name="detalles[${index}][servicio_ponchado_id]" value="${detalle.servicio_ponchado_id}" />
                    <input type="hidden" name="detalles[${index}][tipo_item]" value="PONCHADO" /></td>
                <td class="pu" data-precio="${precio}">${precio} <input type="hidden" name="detalles[${index}][precio]" value="${precio}" /></td>
                <td class="importe">${total} <input type="hidden" name="detalles[${index}][total]" value="${total}" /></td>
                <td><button type="button" class="remove text-red-600">Eliminar</button></td>
            </tr>
            `;

            $('#item_table_0 tbody').append(html);
        }

        // CLONA LAS FORMAS DE PAGO
        inicializarSelect2();
        //let indexFormaPago = 1;
        let indexFormaPago = {{ count($formasPago) - 1 }};

        function agregarFormaPago() {
            const container = document.getElementById('formasPagoContainer');
            const original = container.querySelector('.formas-pago-group');
            const clone = original.cloneNode(true); // <--- aquí debe ir
            //const clone = original.cloneNode(true);
            indexFormaPago++;

            // Limpiar los valores antes de hacer el conteo
            clone.querySelectorAll('select, input, p').forEach((el) => {
                if (el.name && el.name.includes('[0]')) {
                    el.name = el.name.replace('[0]', `[${indexFormaPago}]`);
                }

                if (el.id) {
                    //const base = el.id.split('_')[0];
                    //el.id = `${base}_${indexFormaPago}`;
                    el.id = el.id.replace(/\d+$/, indexFormaPago);
                }

                if (el.tagName === 'INPUT') {
                    el.value = '';
                }

                if (el.tagName === 'SELECT') {
                    el.selectedIndex = -1; // Limpia la selección
                }

                if (el.tagName === 'P') {
                    el.classList.add('hidden');
                }
            });

            // Ahora sí: contar selects ya en el DOM (sin considerar el clon aún)
            //const selects = document.querySelectorAll('.select2');
            const selects = document.querySelectorAll('.formas-pago-group select[name^="formas_pago"]');
            const seleccionadas = Array.from(selects).map(sel => sel.value).filter(val => val !== '');

            console.log("seleccionadas.length: " + seleccionadas.length)
            if (seleccionadas.length >= 5) {
                alert("Ya se han agregado todas las formas de pago disponibles.");
                return;
            }

            // Eliminar el Select2 viejo
            $(clone).find('.select2').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy'); // Destruye la instancia vieja
                }

                $(this).next('.select2-container').remove();
                $(this).removeClass("select2-hidden-accessible").removeAttr("data-select2-id tabindex aria-hidden");
            });

            // Reemplazar botón "+ forma de pago" con botón eliminar
            const btnContainer = clone.querySelector('div.sm\\:col-span-12:last-child'); // el contenedor del botón
            btnContainer.innerHTML = `
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
                <button type="button" onclick="eliminarFormaPago(this)" class="bg-red-600 text-white px-4 py-2 rounded">Eliminar</button>
            `;

            container.appendChild(clone);

            // Inicializar Select2 solo en el nuevo clon
            $(clone).find('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%'
            }).on('change', function() {
                actualizarOpcionesFormasPago();
            });

            // Muy importante: actualizar las opciones una vez añadido y cargado el nuevo select2
            actualizarOpcionesFormasPago();

            //indexFormaPago++;
            //const clone = original.cloneNode(true);
        }

        // Actualiza las opciones de forma de pago
        function actualizarOpcionesFormasPago() {
            //const selects = document.querySelectorAll('.select2');
            const selects = document.querySelectorAll('.formas-pago-group select[name^="formas_pago"]');
            const seleccionadas = Array.from(selects).map(sel => sel.value).filter(val => val !== '');

            selects.forEach(select => {
                const valorActual = select.value;

                select.querySelectorAll('option').forEach(option => {
                    // Permitir el valor actual y deshabilitar los que ya estén seleccionados en otros select
                    if (option.value === valorActual || !seleccionadas.includes(option.value)) {
                        option.disabled = false;
                    } else {
                        //option.disabled = seleccionadas.includes(option.value);
                        option.disabled = true;
                    }
                });

                // Refrescar el Select2 con los cambios
                //$(select).select2();
                $(select).trigger('change.select2');
            });
        }

        // Elimina el bloque de forma de pago
        function eliminarFormaPago(btn) {
            const group = btn.closest('.formas-pago-group');
            group.remove();

            // Llamar para actualizar las opciones disponibles tras la eliminación
            actualizarOpcionesFormasPago();
        }

        // INICIALILZA EL SELECT FORMA DE PAGO
        function inicializarSelect2() {
            $('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%' // Asegura que se mantenga el ancho
            }).on('change', function() {
                actualizarOpcionesFormasPago();
            });

            // Llamar inmediatamente para reflejar el estado actual
            actualizarOpcionesFormasPago();
        }

        $(document).ready(function() {
            recalcularTotalTabla('item_table_0');

            // Variable global para la tabla
            let table = null; //de clientes
            let tableP = null; //de producto

            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            // Ajusta la altura del select2
            $('.select2-selection--single').css({
                'height': '2.5rem', // Ajusta la altura según sea necesario
                'display': 'flex',
                'align-items': 'center'
            });

            $('.select2-selection__rendered').css({
                'line-height': '2.5rem', // Asegúrate de que coincida con la altura del input
                'padding-left': '0.5rem', // Ajusta el padding según sea necesario
                'color': '#374151' // Asegúrate de que coincida con el texto del input
            });

            $('.select2-selection__arrow').css({
                'height': '2.5rem', // Ajusta la altura según sea necesario
                'top': '50%',
                'transform': 'translateY(-50%)'
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

            // Cerrar al hacer clic en el fondo
            $(document).on('click', '.producto-modal', function(e) {
                if ($(e.target).is('.producto-modal')) {
                    $(this).addClass('hidden');
                }
            });

            $(document).on('click', '.ponchado-modal', function(e) {
                if ($(e.target).is('.ponchado-modal')) {
                    $(this).addClass('hidden');
                }
            });

            // Cerrar con el botón de la X
            $(document).on('click', '.close-modal', function() {
                $('.producto-modal').addClass('hidden');
                $('#cliente-modal').addClass('hidden');
                cerrarModalNotas();
            });

            // Esto lo colocas solo una vez al inicio
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                recalcularTotalTabla('item_table_0');
            });

            // selecciona el producto del datatable/ modal
            let index = 0;
            function insertarProductoEnTabla(data, $targetTable) {
                if (!$targetTable || $targetTable.length === 0) return;

                const idproducto = data.id;
                const nombre = data.nombre;
                const inventario = data.inventario_data || {};
                const requiereSerie = data.serie;
                const tipoItem = data.tipo;
                const precio_publico = parseFloat(inventario.precio_publico || 0);
                const stock = tipoItem === 'SERVICIO' ? inventario.cantidad : parseFloat(inventario.cantidad || 0);

                // Verificar si el producto ya existe en la tabla
                const $existingRow = $targetTable.find(`tbody tr[data-idproducto="${idproducto}"]`);
                if ($existingRow.length > 0) {
                    let $inputCant = $existingRow.find('.cantVenta');
                    let cantActual = parseInt($inputCant.val()) || 1;
                    let nuevaCant = cantActual + 1;
                    $inputCant.val(nuevaCant);

                    const total = nuevaCant * precio_publico;

                    // Actualizar texto visible del importe
                    $existingRow.find('.importe').contents().filter(n => n.nodeType===3).first()
                        .replaceWith(total.toLocaleString('es-MX', { style:'currency', currency:'MXN' }));

                    // Actualizar input hidden
                    $existingRow.find('input.total_pp').val(total.toFixed(2));
                    $inputCant.attr('max', stock);
                } else {
                    // Crear nueva fila
                    let html = `<tr data-idproducto="${idproducto}" data-inventario='${JSON.stringify(inventario).replace(/'/g,"&apos;")}' class="odd:bg-white odd:dark:bg-gray-500 even:bg-gray-50 even:dark:bg-gray-400 border-b border-gray-100 dark:border-gray-400">`;

                    // Cantidad
                    html += `<td>
                                <input type="number" name="detalles[${index}][cantidad]" min="1" max="${stock}" value="1" class="cantVenta w-16 text-center border rounded"/>
                            </td>`;

                    // Nombre producto y hidden inputs
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ${nombre}
                                <input type="hidden" name="detalles[${index}][name_producto]" value="${nombre}" />
                                <input type="hidden" name="detalles[${index}][producto_id]" value="${idproducto}" />
                                <input type="hidden" name="detalles[${index}][tipo_item]" value="${tipoItem}" />
                            </td>`;

                    // Serie
                    html += `<td class="px-6 py-4">
                                <textarea name="detalles[${index}][series]" class="serie w-40 border rounded p-1" rows="1" ${requiereSerie==1 ? 'required' : ''}></textarea>
                            </td>`;

                    // Precio
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white pu" data-precio="${precio_publico}">
                                ${precio_publico.toLocaleString('es-MX',{style:'currency',currency:'MXN'})}
                                <input type="hidden" name="detalles[${index}][precio]" value="${precio_publico}" />
                            </td>`;

                    // Importe
                    html += `<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white importe">
                                ${precio_publico.toLocaleString('es-MX',{style:'currency',currency:'MXN'})}
                                <input type="hidden" name="detalles[${index}][total]" value="${precio_publico}" class="total_pp"/>
                            </td>`;

                    // Botón eliminar
                    html += `<td class="px-6 py-4">
                                <button type="button" class="remove font-medium text-red-600 dark:text-red-400 hover:underline">Eliminar</button>
                            </td>`;

                    html += `</tr>`;

                    $targetTable.find('tbody').append(html);
                    index++;
                }

                // Recalcular total
                recalcularTotalTabla($targetTable.attr('id'));
            }

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

            //BUSCAMOS POR CÓDIGO DE BARRAS
            $('#codigo_barra_p').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    let $input = $(this);
                    let codigo = $(this).val().trim();
                    if (codigo === '') return;
                    console.log('codigo: '+codigo);
                    let tipoBusqueda = 'apartados'; // o 'cotizaciones', 'apartados', etc.

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
                                const data = res.data[0];
                                const $targetTable = $('#item_table_0'); // tabla destino por defecto
                                insertarProductoEnTabla(data, $targetTable);
                            } else {
                                alert('Producto no encontrado');
                            }

                            $input.val('').focus();
                        }
                    });
                }
            });

            // selecciona el cliente del datatable/ modal
            $('#clientes tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (table) {
                    var data = table.row(this).data();

                    if (data) {
                        var cliente_id = data['id'];
                        var nombre_cliente = data['full_name'];
                        var tipo_cliente = data['tipo_cliente'] || 'CLIENTE PÚBLICO';

                        $('#cliente_id').val(cliente_id);
                        $('#nombre_cliente').val(nombre_cliente);

                        // llenamos el select
                        $('#tipo_cliente').val(tipo_cliente.toUpperCase());

                        // Guardamos extras ocultos, para validar los créditos
                        $('#cliente_autorizado').val(data['autorizado'] ? 1 : 0);
                        $('#cliente_limite_credito').val(data['limite_credito'] || 0);
                        $('#cliente_monto_pendiente').val(data['monto_pendiente'] || 0);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#cliente-modal").hasClass('hidden')) {
                            $("#cliente-modal").removeClass('hidden');
                        }
                        $("#cliente-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80')
                            .remove(); // Elimina el fondo oscuro del modal

                        // Simular un segundo clic después de mostrar el modal
                        setTimeout(function() {
                            // Forzar el clic en el botón de mostrar modal si es necesario
                            $('#btn-cliente').trigger('click');
                        }, 100); // Ajusta el retraso según sea necesario

                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                }
            });

            //ACTUALIZA EL PRECIO
            $('#tipo_cliente_NO').on('change', function() {
                const tipo = $(this).val(); // PUBLICO, MEDIO_MAYOREO, MAYOREO

                $('#item_table_0 tbody tr').each(function(rowIndex) {
                    const $row = $(this);

                    // Buscar precios del inventario desde un atributo oculto
                    const inventario = $row.data('inventario'); // 👈 lo guardamos al crear fila

                    if (inventario) {
                        let nuevoPrecio = 0;

                        switch (tipo) {
                            case 'CLIENTE PÚBLICO':
                                nuevoPrecio = parseFloat(inventario.precio_publico);
                                break;
                            case 'CLIENTE MEDIO MAYOREO':
                                nuevoPrecio = parseFloat(inventario.precio_medio_mayoreo);
                                break;
                            case 'CLIENTE MAYOREO':
                                nuevoPrecio = parseFloat(inventario.precio_mayoreo);
                                break;
                            default:
                                nuevoPrecio = parseFloat(inventario.precio_publico);
                        }

                        // recalcular importe
                        const cantidad = parseFloat($row.find('.cantVenta').val()) || 1;
                        const subtotal = cantidad * nuevoPrecio;

                        // actualizar precio unitario
                        $row.find('.pu').attr('data-precio', nuevoPrecio)
                            .find('input').val(nuevoPrecio);
                        $row.find('.pu').contents().filter(function() {
                            return this.nodeType === 3; // texto
                        }).first().replaceWith(nuevoPrecio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));

                        // actualizar importe sin reemplazar el input hidden
                        $row.find('.importe').contents().filter(function() {
                            return this.nodeType === 3; // solo el texto visible
                        }).first().replaceWith(subtotal.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
                        $row.find('input.total_pp').val(subtotal.toFixed(2));

                        console.log('subtotal: '+subtotal);
                    }
                });

                // Recalcular total general
                recalcularTotalTabla('item_table_0');
            });

            $('#tipo_cliente').on('change', function() {
                const tipo = $(this).val(); // PUBLICO, MEDIO_MAYOREO, MAYOREO

                $('#item_table_0 tbody tr').each(function() {
                    const $row = $(this);
                    const inventarioAttr = $row.attr('data-inventario');
                    const inventario = inventarioAttr ? JSON.parse(inventarioAttr.replace(/&quot;/g,'"')) : null;

                    if (!inventario) return;

                    let nuevoPrecio = 0;
                     switch(tipo) {
                        case 'CLIENTE PÚBLICO': nuevoPrecio = parseFloat(inventario.precio_publico); break;
                        case 'CLIENTE MEDIO MAYOREO': nuevoPrecio = parseFloat(inventario.precio_medio_mayoreo); break;
                        case 'CLIENTE MAYOREO': nuevoPrecio = parseFloat(inventario.precio_mayoreo); break;
                        default: nuevoPrecio = parseFloat(inventario.precio_publico);
                    }

                    // Recalcular importe
                    const cantidad = parseFloat($row.find('.cantVenta').val()) || 0;
                    const nuevoImporte = cantidad * nuevoPrecio;

                    // Actualizar precio unitario
                    $row.find('.pu').attr('data-precio', nuevoPrecio);
                    $row.find('.pu input').val(nuevoPrecio);
                    $row.find('.pu').text(nuevoPrecio.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));

                    $row.find('.importe input.total_pp').val(nuevoImporte.toFixed(2));
                    $row.find('.importe').contents().filter(function() {
                        return this.nodeType === 3; // solo nodo de texto
                    }).remove(); // eliminar cualquier nodo de texto existente
                    $row.find('.importe').prepend(nuevoImporte.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
                });

                // Recalcular total general
                recalcularTotalTabla('item_table_0');
            });


            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-cliente').click(async function() {
                // Mostrar el modal primero si está oculto
                if ($("#cliente-modal").hasClass('hidden')) {
                    $("#cliente-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargarTablaCliente();
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

            // cambio de forma de pago 
            $(document).on('input', '.forma-pago', function() {
                recalcularFaltanteCambio();
            });

            // Evitar el envío del formulario al presionar Enter, excepto en textarea
            $(document).on('keypress', function(e) {
                if (e.which == 13 && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });

            let formSubmitting = false;
            $('#formulario-apartado').on('submit', function(e) {
                if (formSubmitting) {
                    e.preventDefault();
                    return false;
                }

                let totalVenta = parseFloat($('#total_venta').val()) || 0;
                let efectivo = parseFloat($('#efectivo').val()) || 0;
                let debito = parseFloat($('#debito').val()) || 0;
                let credito = parseFloat($('#credito').val()) || 0;
                let transferencia = parseFloat($('#transferencia').val()) || 0;
                let adelanto     = parseFloat($('#adelanto').val()) || 0;

                // 1. Validar cliente ≠ CLIENTE PÚBLICO
                let clienteNombre = $('#nombre_cliente').val();
                if (clienteNombre === 'CLIENTE PÚBLICO') {
                    e.preventDefault();
                    Swal.fire({
                        icon: "info",
                        title: "Cliente",
                        html: "Debes seleccionar un cliente distinto a CLIENTE PÚBLICO para apartados.",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        },
                        buttonsStyling: false
                    });
                    return false;
                }

                // 2. Debe haber al menos un producto
                if ($('#item_table_0 tbody tr').length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "info",
                        title: "Productos",
                        html: "Agrega al menos un producto antes de guardar los datos.",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        },
                        buttonsStyling: false
                    });
                    return false;
                }

                // 3. Calcular total de anticipos ingresados (formas de pago + notas crédito)
                let totalPagado = efectivo + debito + credito + transferencia + adelanto;

                // 4. Validar que haya anticipo > 0 (no importa si es menor al totalVenta)
                if (totalPagado <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "info",
                        title: "Anticipo",
                        html: "Debes registrar un anticipo mayor a $0.00 para guardar el apartado.",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        },
                        buttonsStyling: false
                    });
                    return false;
                }

                // 5. Validar que no se exceda el total de la venta
                if (totalPagado > totalVenta) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "info",
                        title: "Anticipo",
                        html: "El anticipo no puede ser mayor al total de la venta.",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5"
                        },
                        buttonsStyling: false
                    });
                    return false;
                }

                // Todo válido
                formSubmitting = true;
                return true;
            });

            // Prevenir envío por Enter (solo en inputs tipo number o text)
            $('#formulario-apartado').on('keypress', 'input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            //Recalcula el faltante y cambio
            function recalcularFaltanteCambio() {
                const totalVenta = parseFloat($('#total_venta').val()) || 0;

                let efectivo = parseFloat($('#efectivo').val()) || 0;
                let debito = parseFloat($('#debito').val()) || 0;
                let credito = parseFloat($('#credito').val()) || 0;
                let transferencia = parseFloat($('#transferencia').val()) || 0;
                let adelanto = parseFloat($("#adelanto").val()) || 0;

                // Total abonado
                const totalPagos = efectivo + debito + credito + transferencia + adelanto;

                // Faltante = total venta - totalPagos (mínimo 0)
                let faltante = totalVenta - totalPagos;
                if (faltante < 0) faltante = 0;

                // Mostrar resultados
                $('#faltante_texto').text(faltante.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                $('#total_faltante').val(faltante);

                // Como ya no manejas cambio, siempre lo dejas en 0
                $('#cambio_texto').text("$0.00");
                $('#total_cambio').val(0);
            }

            // Función para recargar o inicializar la tabla CLIENTES
            async function recargarTablaCliente() {
                if ($.fn.DataTable.isDataTable('#clientes')) {
                    // Recargar los datos sin redibujar la tabla
                    await table.ajax.reload(null, false);
                    //table.ajax.reload(null, false);
                } else {
                    // Inicializar la tabla si aún no está inicializada
                    await clientes();
                }
            }

            // OBTENGO LOS CLIENTES POR AJAX
            async function clientes() {
                const postData = {
                    _token: $('input[name=_token]').val(),
                    origen: 'clientes.pedidos',
                };

                if ($.fn.DataTable.isDataTable('#clientes')) {
                    $('#clientes').DataTable().clear().destroy();
                }

                // Inicializar DataTable
                table = $('#clientes').DataTable({
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
                            name: 'tipo_cliente',
                            defaultContent: 'POR DEFINIR'
                        },
                        {
                            data: 'ventas_credito',
                            name: 'ventas_credito',
                            render: function(data) {
                                return data > 0 ? data : '-';
                            }
                        },
                        {
                            data: 'monto_pendiente',
                            name: 'monto_pendiente',
                            render: function(data) {
                                return '$' + parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: 'limite_credito',
                            name: 'limite_credito',
                            render: function(data) {
                                return '$' + parseFloat(data).toLocaleString();
                            }
                        },
                        {
                            data: 'dias_credito',
                            name: 'dias_credito'
                        },
                        {
                            data: 'autorizado',
                            name: 'autorizado',
                            render: function(data) {
                                return data ?
                                    '<span class="text-green-600 font-bold">Autorizado</span>' :
                                    '<span class="text-red-600 font-bold">Bloqueado</span>';
                            }
                        }
                    ],
                });
            }

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
                    }).first().replaceWith(subtotal.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' }));
                });

                // Actualizar visualmente el total
                const totalFormateado = total.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
                $('span[data-total]').text(totalFormateado);
                $('#total_mostrado').text(totalFormateado);
                $('#total_venta').val(total.toFixed(2));
            }

            function recalcularTotalTabla_no_este_no(targetTableId) {
                let total = 0;

                $(`#${targetTableId} tbody tr`).each(function() {
                    const $row = $(this);
                    const precio = parseFloat($row.find('.pu').data('precio')) || 0;
                    const cantidad = parseFloat($row.find('.cantVenta').val()) || 0;
                    let subtotal = 0;

                    if ($row.attr('data-idproducto')) {
                        // Producto: se multiplica
                        subtotal = cantidad * precio;
                        total += subtotal;
                    } else if ($row.attr('data-idponchadoServicio')) {
                        // el precio ya es el total (no se multiplica por cantidad)
                        subtotal = cantidad * precio;
                        total += subtotal;
                    }

                    // Actualizar el input hidden del total por producto
                    const $inputTotal = $row.find('input.total_pp');
                    $inputTotal.val(subtotal.toFixed(2));

                    // Mantener el name del input original al actualizar el HTML de la celda
                    const name = $inputTotal.attr('name');
                    $row.find('.importe').html(`
                        ${subtotal.toLocaleString('es-MX', { style: 'currency', currency: 'MXN' })}
                        <input type="hidden" class="total_pp" name="${name}" value="${subtotal.toFixed(2)}" />
                    `);
                    console.log('subtotal: '+subtotal);
                });

                // Actualizar visualmente
                $('span[data-total]').text(total.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }));

                // Formateo a moneda
                const totalFormateado = total.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                });

                // Actualizar input hidden
                $('#total_mostrado').text(totalFormateado);
                $('#total_venta').val(total.toFixed(2));

                //recalcularFaltanteCambio();
            }

            $('.select2').select2({
                placeholder: "-- Seleccione --",
                allowClear: false,
                width: '100%'
            });

            actualizarOpcionesFormasPago(); // para actualizar si hay formas prellenadas
        });
    </script>
    @if (Session::has('id'))
        <script type="text/javascript">
            var id = {{ session('id') }};
            setTimeout(function() {
                window.open("{{ url('/ticket-venta') }}/" + id, '_blank');
            }, 200);
            <?php Session::forget('id'); ?>
        </script>
    @endif
@stop

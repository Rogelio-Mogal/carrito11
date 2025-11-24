<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
        <input type="date" id="fecha" name="fecha" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            value="{{ old('fecha', \Carbon\Carbon::parse($cotizacion->fecha)->format('Y-m-d')) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="btn-client" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Clientes
        </label>
        <button data-modal-target="cliente-modal" data-modal-toggle="cliente-modal" id="btn-client"
            class="block w-full text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:outline-none focus:yellow-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:focus:ring-yellow-900"
            type="button">
            Buscar
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cliente</label>
        <input type="text" id="cliente" name="cliente" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cliente" 
            value="{{ old('cliente', $cotizacion->cliente ? $cotizacion->cliente : optional($cotizacion->clienteDocumento)->full_name) }}" 
            readonly/>
        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ old('cliente_id', $cotizacion->cliente_id) }}">
        <input type="hidden" name="tipo" id="tipo" value="COTIZACIÓN">
        <input type="hidden" name="name_personalizado" id="name_personalizado" value="0">
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
        <input type="text" id="direccion" name="direccion"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Dirección" 
            value="{{ old('direccion', $cotizacion->direccion ? $cotizacion->direccion : optional($cotizacion->clienteDocumento)->direccion) }}"  
            readonly />
    </div>
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <label for="clearCliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Limpiar</label>
        <button type="button" id="clearCliente"
            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
            <svg fill="#ffffff" class="w-6 h-6 text-white dark:text-white" aria-hidden="true" fill="#000000" height="200px"
                width="200px" version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                fill="none" viewBox="0 0 297 297" xml:space="preserve" stroke="#ffffff">
                <path
                    d="M287.55,260.218H149.47l131.846-131.846c10.437-10.437,10.437-27.419,0-37.856l-64.808-64.808 c-10.437-10.437-27.419-10.436-37.856,0L11.788,192.573c-5.055,5.056-7.84,11.778-7.84,18.928c0,7.15,2.785,13.872,7.84,18.928 l29.79,29.79H9.45c-5.218,0-9.45,4.231-9.45,9.45c0,5.219,4.231,9.45,9.45,9.45h278.1c5.218,0,9.45-4.231,9.45-9.45 C297,264.45,292.769,260.218,287.55,260.218z M192.016,39.072c3.069-3.069,8.063-3.067,11.128,0l64.808,64.808 c1.487,1.486,2.305,3.462,2.305,5.565c0,2.101-0.819,4.078-2.305,5.564L159.309,223.651l-75.936-75.936L192.016,39.072z M122.742,260.219H68.306l-43.154-43.155c-3.068-3.067-3.068-8.06,0-11.127l44.858-44.858l75.936,75.936L122.742,260.219z">
                </path>
            </svg>
            <span class="sr-only">Limpiar</span>
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
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
        <label for="btn-product-comun" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Producto en común
        </label>
        <button data-modal-target="producto-comun-modal" data-modal-toggle="producto-comun-modal" id="btn-product-comun"
            class="block w-full text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
            type="button">
            Agregar
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="codigo_barra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código de
            barra</label>
        <input type="text" id="codigo_barra" name="codigo_barra"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Código de barra" value="" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="tipo_precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de precio</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_precio" id="inlineRadio1" value="CLIENTE PÚBLICO"  {{ (old('tipo_precio', $cotizacion->tipo_precio) == 'CLIENTE PÚBLICO' || (!$cotizacion->tipo_precio && !old('tipo_precio'))) ? 'checked' : '' }}> 
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">P. PÚBLICO</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_precio" id="inlineRadio2" value="CLIENTE MEDIO MAYOREO" {{ (old('tipo_precio', $cotizacion->tipo_precio) == 'CLIENTE MEDIO MAYOREO') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">P. MEDIO MAYOREO</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_precio" id="inlineRadio3" value="CLIENTE MAYOREO" {{ (old('tipo_precio', $cotizacion->tipo_precio) == 'CLIENTE MAYOREO') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio3">P. MAYOREO</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTOS AGREGADOS</h4>
    </div>
</div>

<div class="grid-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="item_table_1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">Cantidad</th>
                        <th scope="col" class="px-6 py-3">Producto</th>
                        <th scope="col" class="px-6 py-3">Precio</th>
                        <th scope="col" class="px-6 py-3">Importe</th>
                        <th scope="col" class="px-6 py-3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot class="text-xs text-gray-900 uppercase bg-indigo-100 dark:bg-gray-600 dark:text-gray-400">
                    <tr>
                        <td colspan="3" class="px-6 py-3 text-right font-bold">Total:</td>
                        <td class="px-6 py-3 font-bold">0.00</td>
                        <td class="px-6 py-3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @include('producto-servicio._modal_producto_comun')
    @include('producto-servicio._modal_productos')
    @include('clientes._modal_clientes')

    <br/>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR COTIZACIÓN
            @elseif($metodo == 'edit')
                EDITAR COTIZACIÓN
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        let tableProductos;
        let tableClientes;
        $(document).ready(function() {
            
            productos(); // Llama a la función para inicializar DataTable
            clientes();

            // GENERO LA TABLA DINAMICA, HUBO ERRORES
            var oldValues = @json(old()); // Obtén los valores old en formato JSON

            if (Object.keys(oldValues).length > 0) {
                // Itera sobre los valores old
                $.each(oldValues['cantVenta'], function(index, value) {
                    var cant = value || '';
                    var id = oldValues['idproducto'][index] || '';
                    var producto = oldValues['producto'][index] || '';
                    var serie = oldValues['serie'][index] || '';
                    var price = oldValues['pu'][index] || '';
                    var newCosto = oldValues['pc'][index] || '';
                    var pM = oldValues['pm'][index] || '';
                    var pMM = oldValues['pmm'][index] || '';
                    var pP = oldValues['pp'][index] || '';
                    var subtotal = oldValues['ImporteSalida'][index] || '';
                    var is_serie = oldValues['check_serie'] && oldValues['check_serie'][index] !== undefined ? oldValues['check_serie'][index] : 0;
                    var codProveedro = oldValues['codigo_proveedor'][index] || '';
                    var html = '';

                    var requerido = '';
                    var check = '';
                    if (is_serie == 1) {
                        requerido = 'required';
                        var check = 'checked';
                    }

                    html += '<tr>';
                    html += '<td style="width:120px;">';
                    html += '<input type="hidden" name="check_serie[]" value="'+is_serie+'" class="check_serie is_serie" />';
                    html += '<input type="text" name="cantVenta[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 cantVenta" value="' +
                        cant + '" readonly/>';
                    html += '<input type="hidden" name="idproducto[]" value="' +
                        id + '" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 idp" />';
                    html += '</td>';
                    html += '<td>';
                    html += producto +
                        ' <input type="hidden" name="producto[]" value="' +
                        producto + '" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly />';
                    html += '</td>';
                    html += '<td style="width:200px;">';
                    html +=
                        '<textarea name="serie[]" rows="1" cols="40" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 serie uppercase" ' +
                        requerido + ' >' + serie + '</textarea>';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="text" name="codigo_proveedor[]" class="codigo_proveedor bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' + codProveedro + '"/>';
                    html += '</td>';
                    html += '<td style="width:135px;">';
                    html +=
                        '<input type="text" name="pu[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pu" con-iva="' +
                                    price + '" sin-iva="'+parseFloat(price / 1.16).toFixed(2)+'" value="' +
                        price + '" readonly/>';
                    html += '</td>';
                    html += '<td style="width:135px;">' +
                        '<input type="text" name="pc[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pc" ' +
                        'con-iva="' +
                                    price + '" sin-iva="'+parseFloat(price / 1.16).toFixed(2)+'" value="' + newCosto + '" readonly/>';
                    html += '</td>';
                    html += '<td style="width:135px;">';
                    html +=
                        '<input type="number" name="pm[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pm campo-requerido" min="" value="' +
                        Math.ceil(pM) +
                        '" step="any" required/>';
                    html += '</td>';
                    html += '<td style="width:135px;">';
                    html +=
                        '<input type="number" name="pmm[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pmm campo-requerido" min="" value="' +
                        Math.ceil(pMM) +
                        '" step="any" required/>';
                    html += '</td>';
                    html += '<td style="width:135px;">';
                    html +=
                        '<input type="number" name="pp[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pp campo-requerido" min="" value="' +
                        Math.ceil(pP) +
                        '" step="any" required/>';
                    html += '</td>';
                    html += '<td style="width:150px;">';
                    html +=
                        '<input type="number" name="ImporteSalida[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 importe" value="' +
                        subtotal + '" readonly/>';
                    html += '</td>';
                    html += '<td>';
                    html +=
                        '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                    html +=
                        '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                    html +=
                        '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                    html += '       </svg>                          ';
                    html += '       <span class="sr-only">Quitar</span>';
                    html += '   </button>';
                    html += '</td>';
                    html += '</tr>';
                    $('#item_table_1').append(html);
                });
            } else {
                console.log('No hay valores old disponibles');
            }          

            // selecciona el producto del datatable/ modal
            $('#productos tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (tableProductos) {
                    var data = tableProductos.row(this).data();

                    if (data) {
                        var producto = data['nombre'];
                        var idproducto = data['id'];
                        var pPublico = data['inventario'] ? data['inventario']['precio_publico'] : '0';
                        var pMedio = data['inventario'] ? data['inventario']['precio_medio_mayoreo'] : '0';
                        var pMayoreo = data['inventario'] ? data['inventario']['precio_mayoreo'] : '0';
                        var subtotal = 0;
                        var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                        var html = '';
                        console.log('tipoPrecio: '+tipoPrecio);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#producto-modal").hasClass('hidden')) {
                            $("#producto-modal").removeClass('hidden');
                        }
                        $("#producto-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove(); // Elimina el fondo oscuro del modal

                         // Simular un segundo clic después de mostrar el modal
                        setTimeout(function() {
                            // Forzar el clic en el botón de mostrar modal si es necesario
                            $('#btn-product').trigger('click');
                        }, 100); // Ajusta el retraso según sea necesario

                        html += '<tr class="ml-5">';
                        html += '<td style="width:120px;">';
                        html += '<input type="hidden" name="producto_id[]" value="'+idproducto+'" class="producto_id" />';
                        html +=
                            '<input type="number" name="cantidad[]" min="1" step="1" class="cantidad bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="1" />';
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="hidden" name="is_producto_comun[]" value="0" class="is_producto_comun" />';
                        html += '<input type="hidden" name="product_name[]" value="'+producto+'" class="product_name" />';
                        html += producto ;
                        html += '</td>';
                        html += '<td>';
                        html += '<input type="hidden" name="precio_publico[]" value="'+pPublico+'" class="precio_publico" />';
                        html += '<input type="hidden" name="precio_medio_mayoreo[]" value="'+pMedio+'" class="precio_medio_mayoreo" />';
                        html += '<input type="hidden" name="precio_mayoreo[]" value="'+pMayoreo+'" class="precio_mayoreo" />';
                        html +=
                            '<input type="number" name="precio[]" class="precio campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                            Math.ceil(pMayoreo) + '" value="' + Math.ceil(pPublico) +
                            '" step="any" required/>';
                        html += '</td>';
                        html += '<td style="width:150px;">';
                        html +=
                            '<input type="number" name="importe[]" class="importe bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' +
                            subtotal + '" readonly/>';
                        html += '</td>';
                        html += '<td class="px-6 py-4 text-center">';
                        html +=
                            '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                        html +=
                            '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                        html +=
                            '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                        html += '       </svg>                          ';
                        html += '       <span class="sr-only">Quitar</span>';
                        html += '   </button>';
                        html += '</td>';
                        html += '</tr>';

                        // ## quita los repetidos y los suma ## //
                        var rowCount = $('#item_table_1 tr').length;
                        if (rowCount == 1) {
                            $('#item_table_1').append(html);
                            suma();
                        } else {
                            var repetido = 0;
                            $('#item_table_1 tr .producto_id').each(function() {
                                var $row = $(this).closest("tr");
                                productId = $row.find(".producto_id").val();
                                if (idproducto == productId) {

                                    Swal.fire({
                                        icon: "warning",
                                        title: producto,
                                        html: 'Ya se encuentra agregado. <br/> Por favor verifique la información.',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                        },
                                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                                    });
                                    repetido = 1;
                                }
                            });
                        }
                        if (repetido == 0) {
                            $('#item_table_1').append(html);
                            suma();
                        }
                        // ## fin quita los repetidos ## //

                        $('.remove').off().click(function(e) {
                            $(this).parent('td').parent('tr').remove();
                            suma();
                        });
                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                } 
            });

            // CAMBIAMOS EL CLIENTE CON EL MODAL
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

                        // Cambiamos los valores de los input
                        $('#cliente_id').val(idCliente);
                        $('#cliente').val(nombreCliente);
                        $('input[name="tipo_precio"][value="' + tipoPrecio + '"]').prop('checked', true);
                        $('#direccion').val(direccionCliente);

                        $('#cliente').prop("readonly", true);
                        $('#direccion').prop("readonly", true);
                        $('#name_personalizado').val(0);

                        // Mostrar el modal (asegurarse de que esté visible)
                        if ($("#cliente-modal").hasClass('hidden')) {
                            $("#cliente-modal").removeClass('hidden');
                        }
                        $("#cliente-modal").addClass('hidden');
                        $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove(); // Elimina el fondo oscuro del modal

                        suma();

                        // Simular un segundo clic después de mostrar el modal
                        setTimeout(function() {
                            // Forzar el clic en el botón de mostrar modal si es necesario
                            $('#btn-client').trigger('click');
                        }, 100); // Ajusta el retraso según sea necesario

                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
                } 
            });

            // REGISTRO DE PRODUCTO EN COMUN
            $('#btnComun').click(function(e){
                e.preventDefault();
                var producto = $('#modal_producto_comun').val();
                var cantidad = $('#modal_cantidad').val();
                var precio = $('#modal_precio').val();
                var pPublico = precio;
                var pMedio = precio;
                var pMayoreo = precio;
                var idproducto = 1;
                var subtotal = 0;
                var html = '';
                

                if( producto !='' && cantidad >= 1 && precio >= 1 ){
                    $('#item_table_1').append(html);
                    
                    //insertCotizacion(clienteId, fecha, CantInicial, idproducto, Producto, pPublico, pmMayoreo, pMayoreo, subtotal, total,tipoCliente);

                    html += '<tr class="ml-5">';
                    html += '<td style="width:120px;">';
                    html += '<input type="hidden" name="producto_id[]" value="'+idproducto+'" class="producto_id" />';
                    html +=
                        '<input type="number" name="cantidad[]" min="1" step="1" class="cantidad bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="'+cantidad+'" />';
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="hidden" name="is_producto_comun[]" value="1" class="is_producto_comun" />';
                    html += '<input type="hidden" name="product_name[]" value="'+producto+'" class="product_name" />';
                    html += producto ;
                    html += '</td>';
                    html += '<td>';
                    html += '<input type="hidden" name="precio_publico[]" value="'+pPublico+'" class="precio_publico" />';
                    html += '<input type="hidden" name="precio_medio_mayoreo[]" value="'+pMedio+'" class="precio_medio_mayoreo" />';
                    html += '<input type="hidden" name="precio_mayoreo[]" value="'+pMayoreo+'" class="precio_mayoreo" />';
                    html +=
                        '<input type="number" name="precio[]" class="precio campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                        Math.ceil(pMayoreo) + '" value="' + Math.ceil(pPublico) +
                        '" step="any" required/>';
                    html += '</td>';
                    html += '<td style="width:150px;">';
                    html +=
                        '<input type="number" name="importe[]" class="importe bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' +
                        subtotal + '" readonly/>';
                    html += '</td>';
                    html += '<td class="px-6 py-4 text-center">';
                    html +=
                        '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                    html +=
                        '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                    html +=
                        '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                    html += '       </svg>                          ';
                    html += '       <span class="sr-only">Quitar</span>';
                    html += '   </button>';
                    html += '</td>';
                    html += '</tr>';

                    // ## quita los repetidos y los suma ## //
                    var rowCount = $('#item_table_1 tr').length;
                    if (rowCount == 1) {
                        $('#item_table_1').append(html);
                        suma();
                    } else {
                        var repetido = 0;
                        $('#item_table_1 tr .producto_id').each(function() {
                            var $row = $(this).closest("tr");
                            product = $row.find(".product_name").val();
                            if (producto == product) {

                                Swal.fire({
                                    icon: "warning",
                                    title: producto,
                                    html: 'Ya se encuentra agregado. <br/> Por favor verifique la información.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                                });
                                repetido = 1;
                            }
                        });
                    }
                    if (repetido == 0) {
                        $('#item_table_1').append(html);
                        suma();
                    }

                    // Simular un segundo clic después de mostrar el modal
                    setTimeout(function() {
                        // Forzar el clic en el botón de mostrar modal si es necesario
                        $('#btn-product-comun').trigger('click');
                    }, 100); // Ajusta el retraso según sea necesario
                    // ## fin quita los repetidos ## //

                    $('.remove').off().click(function(e) {
                        $(this).parent('td').parent('tr').remove();
                        suma();
                    });
                    
                    //$("#addProductoModal").modal('hide');
                    $('#modal_cantidad').val('');
                    $('#modal_precio').val('');
                    $('#modal_producto_comun').val('');
                    $("#producto-comun-modal").addClass('hidden');
                    $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove(); // Elimina el fondo oscuro del modal
                }else{
                    Swal.fire({
                        icon: "info",
                        //title: producto,
                        html: 'Favor de verificar la información, hay datos pendientes por requisitar.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false
                    });  
                }  
            });

            // LIMPIAMOS LOS DATOS DEL CLIENTE PARA PODER 
            // INTRODUCIR UN CLIENE NO AGREGADO EN EL SISTEMA
            // EL CLIENTE SIEMPRE SERÁ PÚBLICO
            $(document).on('click','#clearCliente',function(e){
                $('#cliente').prop("readonly", false);
                $('#direccion').prop("readonly", false);
                $('#cliente_id').val(1);
                $('#cliente').val('CLIENTE PÚBLICO');
                $('input[name="tipo_precio"][value="CLIENTE PÚBLICO"]').prop('checked', true);
                $('#direccion').val('DOMICILIO CONOCIDO ');
                $('#name_personalizado').val(1);
            });

            // CAMBIO DEL TIPO DE PRECIO
            $('input[name="tipo_precio"]').change(function() {
                tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                suma();
            });

            // MUESTRA EL MODAL DE LOS PRODUCTOS
            $('#btn-product').click(async  function() {
                // Mostrar el modal primero si está oculto
                if ($("#producto-modal").hasClass('hidden')) {
                    $("#producto-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargaProductos();
            });

            // MUESTRA EL MODAL DE LOS CLIENTES
            $('#btn-client').click(async  function() {
                // Mostrar el modal primero si está oculto
                if ($("#cliente-modal").hasClass('hidden')) {
                    $("#cliente-modal").removeClass('hidden');
                }
                // Usa una función asíncrona para manejar la recarga o inicialización de DataTable
                await recargaClientes();
            });

            // CERRAR EL MODAL DE PRODUCTO EN COMUN
            $('.close-modal').on('click', function() {
                $(this).closest('.modal').hide();
                $("#producto-comun-modal").addClass('hidden');
                $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove();
                // Simular un segundo clic después de mostrar el modal
                setTimeout(function() {
                    // Forzar el clic en el botón de mostrar modal si es necesario
                    $('#btn-product-comun').trigger('click');
                }, 100);
            });

            // OBTENGO LOS PRECIOS
            function suma() {
                try {
                    var sum = 0;
                    $('#item_table_1 tr .importe').map(function() {
                        var $row = $(this).closest("tr");
                        var subVenta = parseFloat($row.find(".importe").val());
                        var cantidad = parseFloat($row.find(".cantidad").val());
                        var precio = parseFloat($row.find(".precio").val());
                        var publico = parseFloat($row.find(".precio_publico").val());
                        var medio = parseFloat($row.find(".precio_medio_mayoreo").val());
                        var mayoreo = parseFloat($row.find(".precio_mayoreo").val());
                        var tipoPrecio = $('input[name="tipo_precio"]:checked').val();

                        if(tipoPrecio == 'CLIENTE PÚBLICO'){
                            $row.find(".precio").val(publico);
                            precio = publico;
                        }else if(tipoPrecio == 'CLIENTE MEDIO MAYOREO'){
                            $row.find(".precio").val(medio);
                            precio = medio;
                        }else if(tipoPrecio == 'CLIENTE MAYOREO'){
                            $row.find(".precio").val(mayoreo);
                            precio = mayoreo;
                        }

                        var importe = cantidad * precio;
                        $row.find('.importe').val(importe);
                        
                        if (!isNaN(subVenta) && subVenta.length !== 0) {
                            sum += importe;
                        }
                    });

                    // CAMBIO TOTAL DE TFOOT
                    //$('#item_table_1 tfoot tr td').eq(1).text(sum.toFixed(2));
                    $('#item_table_1 tfoot tr td').eq(1).text(formatToFloat(sum));

                } catch (error) {
                    // Manejo de errores
                    console.error('Error al obtener el precio:', error);
                }
            }

            // ACTUALIZA EL PRECIO
            $(document).on('change', '.precio', function(e) {
                var $row = $(this).closest("tr");
                var precio = $row.find(".precio").val();
                $row.find(".precio").val(precio);
                $row.find(".precio_publico").val(precio);
                $row.find(".precio_medio_mayoreo").val(precio);
                $row.find(".precio_mayoreo").val(precio);
                suma();
            });

            // ACTUALIZA LA CANTIDAD DEL PRODUCTO
            $(document).on('change', '.cantidad',function(e) {
                var $row = $(this).closest("tr");
                var id = $row.find(".val-id").val();
                var cantidad = $row.find(".cantidad").val();
                suma();
            });

            // BUSCA POR EL CODIGO DE BARRAS
            $(document).on('keydown', '#codigo_barra', function(e) {
                if (e.which === 13) {
                    var codbar = $('#codigo_barra').val();
                    const postData = {
                        _token: $('input[name=_token]').val(),
                        origen: 'busca.producto.servicio.cotizaciones',
                        codbarra: codbar,
                    };
                    $.ajax({
                        url: "{{ route('busca.producto.servicio.codbarra') }}",
                        type: "POST",
                        data: postData,
                        cache: false,
                        success: function(response) {
                            if (response.data.length === 0) {
                                Swal.fire({
                                    icon: "info",
                                    title: 'El Código no se encuentra.',
                                    html: 'Por favor verifique la información.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false
                                });
                            } else {
                                // Asumiendo que el primer producto encontrado es el que te interesa
                                var producto = response.data[0]; // Obtener el primer producto de la lista

                                if (producto.inventario) {
                                    var pPublico = producto.inventario.precio_publico;
                                    var pMedio = producto.inventario.precio_medio_mayoreo;
                                    var pMayoreo = producto.inventario.precio_mayoreo;
                                }else{
                                    var pPublico = '0';
                                    var pMedio = '0';
                                    var pMayoreo = '0';
                                }

                                var nombreProducto  = producto.nombre;
                                var idProducto  = producto.id;
                                var subtotal = 0;
                                var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                                var html = '';

                                html += '<tr class="ml-5">';
                                html += '<td style="width:120px;">';
                                html += '<input type="hidden" name="producto_id[]" value="'+idProducto +'" class="producto_id" />';
                                html +=
                                    '<input type="number" name="cantidad[]" min="1" step="1" class="cantidad bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="1" />';
                                html += '</td>';
                                html += '<td>';
                                html += '<input type="hidden" name="is_producto_comun[]" value="0" class="is_producto_comun" />';
                                html += '<input type="hidden" name="product_name[]" value="'+nombreProducto +'" class="product_name" />';
                                html += nombreProducto  ;
                                html += '</td>';
                                html += '<td>';
                                html += '<input type="hidden" name="precio_publico[]" value="'+pPublico+'" class="precio_publico" />';
                                html += '<input type="hidden" name="precio_medio_mayoreo[]" value="'+pMedio+'" class="precio_medio_mayoreo" />';
                                html += '<input type="hidden" name="precio_mayoreo[]" value="'+pMayoreo+'" class="precio_mayoreo" />';
                                html +=
                                    '<input type="number" name="precio[]" class="precio campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                                    Math.ceil(pMayoreo) + '" value="' + Math.ceil(pPublico) +
                                    '" step="any" required/>';
                                html += '</td>';
                                html += '<td style="width:150px;">';
                                html +=
                                    '<input type="number" name="importe[]" class="importe bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' +
                                    subtotal + '" readonly/>';
                                html += '</td>';
                                html += '<td class="px-6 py-4 text-center">';
                                html +=
                                    '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                                html +=
                                    '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                                html +=
                                    '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                                html += '       </svg>                          ';
                                html += '       <span class="sr-only">Quitar</span>';
                                html += '   </button>';
                                html += '</td>';
                                html += '</tr>';

                                // ## quita los repetidos y los suma ## //
                                var rowCount = $('#item_table_1 tr').length;
                                if (rowCount == 1) {
                                    $('#item_table_1').append(html);
                                    suma();
                                } else {
                                    var repetido = 0;
                                    $('#item_table_1 tr .producto_id').each(function() {
                                        var $row = $(this).closest("tr");
                                        productId = $row.find(".producto_id").val();
                                        if (idProducto  == productId) {

                                            Swal.fire({
                                                icon: "warning",
                                                title: nombreProducto ,
                                                html: 'Ya se encuentra agregado. <br/> Por favor verifique la información.',
                                                showCancelButton: false,
                                                confirmButtonText: 'OK',
                                                customClass: {
                                                    confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                                },
                                                buttonsStyling: false
                                            });
                                            repetido = 1;
                                        }
                                    });
                                }
                                if (repetido == 0) {
                                    $('#item_table_1').append(html);
                                    suma();
                                }
                                // ## fin quita los repetidos ## //

                                $('.remove').off().click(function(e) {
                                    $(this).parent('td').parent('tr').remove();
                                    suma();
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la petición AJAX:', error);
                            Swal.fire('Hubo un error al realizar la búsqueda.');
                        }
                    });

                    // Limpiar el campo después de enviar la petición
                    $('#codigo_barra').val('');
                }
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
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
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
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
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
                                    return '$0.00';  // Valor por defecto si no hay dato
                                }
                                // Formatear el número con separadores de miles y decimales
                                var formattedNumber = $.fn.dataTable.render.number(',', '.', 2).display(data);
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
                    ],
                });
            }

            // FORMATO MONEDA
            function formatToFloat (data){
                //return data.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2
                }).format(data);
            };
        });
    </script>
@stop

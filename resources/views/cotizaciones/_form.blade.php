<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="fecha" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
        <input type="date" id="fecha" name="fecha" required
            class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
            class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cliente" 
            value="{{ old('cliente', $cotizacion->cliente ? $cotizacion->cliente : optional($cotizacion->clienteDocumento)->full_name) }}" 
            readonly/>
        <input type="hidden" name="cliente_id" id="cliente_id" class="infoCot" value="{{ old('cliente_id', $cotizacion->cliente_id) }}">
        <input type="hidden" name="tipo" id="tipo" value="COTIZACIÓN">
        <input type="hidden" name="name_personalizado" class="infoCot" id="name_personalizado" value="0">
        <input type="hidden" name="cotizacionId" id="cotizacionId" value="0{{old('cotizacionId', $cotizacion->id)}}">
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
        <input type="text" id="direccion" name="direccion"
            class="infoCot bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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

            // MOSTRAMOS LA LISTA DE LOS DETALLES DE LA COTIZACIÓN, -EDITAR-
            let detalle = {{$detalle}};
            if(detalle > 0){
                allDetalleCotizacion(detalle);
            }
            
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

                                var pPublico = '0';
                                var pMedio = '0';
                                var pMayoreo = '0';

                                if (producto.inventario) {
                                    pPublico = producto.inventario.precio_publico;
                                    pMedio = producto.inventario.precio_medio_mayoreo;
                                    pMayoreo = producto.inventario.precio_mayoreo;
                                }

                                var nombreProducto  = producto.nombre;
                                var idProducto  = producto.id;
                                var subtotal = 0;
                                var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                                var clienteId = $('#cliente_id').val();
                                var tipo = $('#tipo').val();
                                var fecha = $('#fecha').val();
                                var cantidad = 1;

                                var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                                var precio = 0;
                                if(tipoPrecio == 'CLIENTE PÚBLICO'){
                                    precio = pPublico;
                                }else if(tipoPrecio == 'CLIENTE MEDIO MAYOREO'){
                                    precio = pMedio;
                                }else if(tipoPrecio == 'CLIENTE MAYOREO'){
                                    precio = pMayoreo;
                                }
                                var importe = cantidad * precio;

                                // ## quita los repetidos y los suma ## //
                                var rowCount = $('#item_table_1 tr').length;
                                if (rowCount == 1) {
                                    insertCotizacion(clienteId, tipo, fecha, cantidad, idProducto, nombreProducto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 0);
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
                                    insertCotizacion(clienteId, tipo, fecha, cantidad, idProducto, nombreProducto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 0);
                                }
                                // ## fin quita los repetidos ## //
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

            // SELECCIONO EL PRODUCTO DEL DATATABLES
            $('#productos tbody').on('click', 'tr', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (tableProductos) {
                    var data = tableProductos.row(this).data();

                    if (data) {
                        var producto = data['nombre'];
                        var idproducto = data['id'];
                        var pPublico = data['inventario_usuario'] ? data['inventario_usuario']['precio_publico'] : '0';
                        var pMedio = data['inventario_usuario'] ? data['inventario_usuario']['precio_medio_mayoreo'] : '0';
                        var pMayoreo = data['inventario_usuario'] ? data['inventario_usuario']['precio_mayoreo'] : '0';
                        var subtotal = 0;
                        var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                        var cantidad = 1;
                        var clienteId = $('#cliente_id').val();
                        var tipo = $('#tipo').val();
                        var fecha = $('#fecha').val();
                        var precio = 0;

                        if(tipoPrecio == 'CLIENTE PÚBLICO'){
                            precio = pPublico;
                        }else if(tipoPrecio == 'CLIENTE MEDIO MAYOREO'){
                            precio = pMedio;
                        }else if(tipoPrecio == 'CLIENTE MAYOREO'){
                            precio = pMayoreo;
                        }

                        var importe = cantidad * precio;
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

                        // ## quita los repetidos y los suma ## //
                        var rowCount = $('#item_table_1 tr').length;
                        if (rowCount == 1) {
                            insertCotizacion(clienteId, tipo, fecha, cantidad, idproducto, producto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 0);
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
                            insertCotizacion(clienteId, tipo, fecha, cantidad, idproducto, producto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 0);
                        }
                        // ## fin quita los repetidos ## //
                    } else {
                        console.error("No se pudo obtener los datos de la fila.");
                    }
                } else {
                    console.error("La tabla no está inicializada correctamente.");
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

                        // Cambiamos los valores de los input
                        $('#cliente_id').val(idCliente);
                        $('#cliente').val(nombreCliente).trigger('change');
                        $('input[name="tipo_precio"][value="' + tipoPrecio + '"]').prop('checked', true).trigger('change');
                        $('#direccion').val(direccionCliente);

                        $('#cliente').prop("readonly", true);
                        $('#direccion').prop("readonly", true);
                        $('#name_personalizado').val(0).trigger('change');

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
                var tipoPrecio = $('input[name="tipo_precio"]:checked').val();
                var clienteId = $('#cliente_id').val();
                var tipo = $('#tipo').val();
                var fecha = $('#fecha').val();
                var importe = cantidad * precio;

                if( producto !='' && cantidad >= 1 && precio >= 1 ){
                    // ## quita los repetidos y los suma ## //
                    var rowCount = $('#item_table_1 tr').length;
                    if (rowCount == 1) {
                        insertCotizacion(clienteId, tipo, fecha, cantidad, idproducto, producto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 1);
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
                                    buttonsStyling: false
                                });
                                repetido = 1;
                            }
                        });
                    }
                    if (repetido == 0) {
                        insertCotizacion(clienteId, tipo, fecha, cantidad, idproducto, producto, precio, pPublico, pMedio, pMayoreo, importe, 0, tipoPrecio, 1);
                    }

                    // Simular un segundo clic después de mostrar el modal
                    setTimeout(function() {
                        // Forzar el clic en el botón de mostrar modal si es necesario
                        $('#btn-product-comun').trigger('click');
                    }, 100); // Ajusta el retraso según sea necesario
                    // ## fin quita los repetidos ## //
                    
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

            // CAMBIA EL PRECIO DE ACUERDO A PRECIO PUBLICO, MEDIO MAYOREO , MAYOREO;
            $('input[name="tipo_precio"]').change(function() {
                // Obtener el valor seleccionado
                var valorSeleccionado = $(this).val();
                tipoPrecio = valorSeleccionado;

                //CAMBIAMOS EL TIPO DEL CLIENTE
                var id = $("#cotizacionId").val();
                var tipoCliente = valorSeleccionado;

                var ruta = '{{ route("admin.cotizacion.detalles.update", ":id") }}';
                ruta = ruta.replace(':id', id);
                $.ajax({
                    url: ruta,
                    type:"PATCH",
                    dataType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id : id,
                        tipo_precio : tipoCliente,
                        tipo : 'actualiza-precios',
                    },
                    success:function(response){ 
                        allDetalleCotizacion(cotizacionId);
                    },
                    error: function(response) {
                        //Swal.fire({
                        //    icon: 'error',
                        //    title: 'Advertencia.',
                        //    text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        //});
                    },
                });
            });
            
            // ACTUALIZA LA CANTIDAD DEL PRODUCTO
            $(document).on('change', '.cantidad',function(e) {
                var $row = $(this).closest("tr");
                var id = $row.find(".val-id").val();
                var cantidad = $row.find(".cantidad").val();
                var tipoCliente = $('input[name="tipo_precio"]:checked').val();
                var ruta = '{{ route("admin.cotizacion.detalles.update", ":id") }}';
                ruta = ruta.replace(':id', id);

                $.ajax({
                    url: ruta,
                    type:"PATCH",
                    dataType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id : id,
                        cantidad : cantidad,
                        tipo_precio : tipoCliente,
                        tipo : 'actualiza-cantidad',
                    },
                    success:function(response){ 
                        //console.log('response: '+response);
                        //allDetalleCotizacion(cotizacionId);
                        allDetalleCotizacion(response.documento_id);
                    },
                    error: function(response) {
                        /*Swal.fire({
                            icon: 'error',
                            title: 'Advertencia.',
                            text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        });*/
                    },
                });
            });

            // ACTUALIZA EL PRECIO
            $(document).on('change', '.precio', function(e) {
                var $row = $(this).closest("tr");
                var id = $row.find(".val-id").val();
                var precio = $row.find(".precio").val();
                var ruta = '{{ route("admin.cotizacion.detalles.update", ":id") }}';
                ruta = ruta.replace(':id', id);
                const ajaxData = {
                    "_token": "{{ csrf_token() }}",
                    id: id,
                    precio: precio,
                    tipo: 'actualiza-precio-unitario',
                };

                $.ajax({
                    url: ruta,
                    type: "PATCH",
                    dataType: 'json',
                    data: ajaxData,
                    success: function(response) {
                        allDetalleCotizacion(cotizacionId);
                    },
                    error: function(response) {
                        console.log('response error: ' + response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Advertencia.',
                            text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        });
                    },
                });
            });

            // ACTUALIZA LOS DATOS DE LA COTIZACION (CLIENTE, FECHA)
            $(document).on('change', '.infoCot',function(e) {
                var id = $("#cotizacionId").val();
                var fecha = $("#fecha").val();
                var cliente_id = $('#cliente_id').val();
                var cliente = $('#cliente').val();
                var name_personalizado = $('#name_personalizado').val();
                var direccion = $('#direccion').val();
                var tipo_precio = $('input[name="tipo_precio"]:checked').val();

                var ruta = '{{ route("admin.cotizacion.update", ":id") }}';
                ruta = ruta.replace(':id', id);
                $.ajax({
                    url: ruta,
                    type:"PATCH",
                    dataType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id : id,
                        fecha : fecha,
                        direccion : direccion,
                        cliente_id : cliente_id,
                        cliente : cliente,
                        name_personalizado : name_personalizado,
                        direccion: direccion,
                        tipo_precio: tipo_precio,
                        origen: 'actualiza.datos.cliente',
                    },
                    success:function(response){ 
                        allDetalleCotizacion(cotizacionId);
                    },
                    error: function(response) {
                        /*Swal.fire({
                            icon: 'error',
                            title: 'Advertencia.',
                            text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        });*/
                    },
                });
            });

            //  ELIMINA PRODUCTO
            $(document).on('click', '.remove', function(e){
                e.preventDefault();
                var $row = $(this).closest("tr");
                var id = $row.find(".val-id").val();
                var cotizacionId = $('#cotizacionId').val();

                var ruta = '{{ route("admin.cotizacion.detalles.destroy", ":id") }}';
                ruta = ruta.replace(':id', id);
                $.ajax({
                    url: ruta,
                    type:"DELETE",
                    dataType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id : id,
                    },
                    success:function(response){ 
                        allDetalleCotizacion(cotizacionId);
                    },
                    error: function(response) {
                        /*Swal.fire({
                            icon: 'error',
                            title: 'Advertencia.',
                            text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        });*/
                    },
                });
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

            // LIMPIAMOS LOS DATOS DEL CLIENTE PARA PODER 
            // INTRODUCIR UN CLIENE NO AGREGADO EN EL SISTEMA
            // EL CLIENTE SIEMPRE SERÁ PÚBLICO
            $(document).on('click','#clearCliente',function(e){
                $('#cliente').prop("readonly", false);
                $('#direccion').prop("readonly", false);
                $('#cliente_id').val(1);
                $('#cliente').val('CLIENTE PÚBLICO').trigger('change');
                $('input[name="tipo_precio"][value="CLIENTE PÚBLICO"]').prop('checked', true);
                $('#direccion').val('DOMICILIO CONOCIDO ');
                $('#name_personalizado').val(1).trigger('change');
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

            // SUMA LOS IMPORTES
            function suma() {
                try {
                    var sum = 0;
                    $('#item_table_1 tr .importe').map(function() {
                        var $row = $(this).closest("tr");
                        var subVenta = parseFloat($row.find(".importe").val());
                        
                        if (!isNaN(subVenta) && subVenta.length !== 0) {
                            sum += subVenta;
                        }
                    });

                    // CAMBIO TOTAL DE TFOOT
                    $('#item_table_1 tfoot tr td').eq(1).text(formatToFloat(sum));

                } catch (error) {
                    // Manejo de errores
                    console.error('Error al obtener el precio:', error);
                }
            }

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
                    origen: 'productos.cotizaciones',
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
                            data: 'inventario_usuario.cantidad',
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
                            data: 'inventario_usuario.precio_publico',
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
                            data: 'inventario_usuario.precio_medio_mayoreo',
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
                            data: 'inventario_usuario.precio_mayoreo',
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
                return new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN',
                    minimumFractionDigits: 2
                }).format(data);
            };

            // AJAX INSERT
            let cotizacionId = $('#cotizacionId').val();
            function insertCotizacion(clienteId,tipo, fecha, cant, productoId, producto, precio,precio1, precio2, precio3, importe, total, tipoCliente, productoComun){
                var cliente = $('#cliente').val();
                var personalizado = $('#name_personalizado').val();
                //e.preventDefault();
                $('.text-danger').text('');
                cotizacionId = $('#cotizacionId').val();

                if ( cotizacionId == 0 ) {
                    const ajaxData = {
                        "_token": "{{ csrf_token() }}",
                        cliente_id : clienteId,
                        tipo: tipo,
                        direccion : $('#direccion').val(),
                        fecha : fecha,
                        total : total,
                         producto_comun:producto, 
                         producto_id : productoId,
                         documento_id : cotizacionId,
                         cantidad : cant,
                         precio : precio,
                         precio_publico : precio1,
                         precio_medio_mayoreo : precio2,
                         precio_mayoreo : precio3,
                         importe: importe,
                         tipo_precio : tipoCliente,
                        cliente : cliente,
                        name_personalizado: personalizado,
                        is_producto_comun: productoComun,
                        product_name: producto,
                    };
                    console.log('Inserta A');
                    $.ajax({
                        url: "{{route('admin.cotizacion.store')}}",
                        type:"POST",
                        dataType: 'json',
                        data:  ajaxData,
                        success:function(response){ 
                            $(response).each(function(i, v){ // indice, valor
                                $('#cotizacionId').val(v.id);
                                cotizacionId = v.id;
                            });

                            allDetalleCotizacion(cotizacionId);
                            $('#myRow').show();
                        },
                        //error: function(response) {
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            /*$('#pacienteError').text(response.responseJSON.errors.pacienteId);
                            $('#indicacionError').text(response.responseJSON.errors.inputIndicaciones);*/
                           // console.log(response);
                            Swal.fire({
                                type: 'error',
                                title: 'Advertencia.',
                                text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                            });
                        },
                    });
                }
                // EN CASO QUE EL USUARIO SEA DIFERENTE DE MERMA DE POLLOS, NO SE ACEPTA PRECIO 0
                else if ( cotizacionId > 0 ){
                    const ajaxData2 = {
                        "_token": "{{ csrf_token() }}",
                        producto_comun:producto, 
                        producto_id : productoId,
                        documento_id : cotizacionId,
                        cantidad : cant,
                        precio : precio,
                        precio_publico : precio1,
                        precio_medio_mayoreo : precio2,
                        precio_mayoreo : precio3,
                        importe: importe,
                        tipo_precio : tipoCliente,
                        is_producto_comun: productoComun,
                        product_name: producto,
                    };
                    console.log('Inserta B');
                    $.ajax({
                        url: "{{route('admin.cotizacion.detalles.store')}}",
                        type:"POST",
                        dataType: 'json',
                        data:  ajaxData2,
                        success:function(response){ 
                            //$('#inputMedicamento').val('').trigger('change')
                            //$('.medicamento').val('');
                            allDetalleCotizacion(cotizacionId);
                        },
                        error: function(xhr, status, error) {
                            console.log('Error response:'+xhr.responseText);
                            /*$('#pacienteError').text(response.responseJSON.errors.pacienteId);
                            $('#indicacionError').text(response.responseJSON.errors.inputIndicaciones);*/

                            /*Swal.fire({
                                icon: 'error',
                                title: 'Advertencia.',
                                text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                            });*/
                        },
                    });
                }     
            };

            //AJAX GET DETALLE COTIZACIÓN
            function allDetalleCotizacion(id){  
                var ruta = '{{ route("admin.cotizacion.detalles.show", ":id") }}';
                ruta = ruta.replace(':id', id);
                var html = '';
                //AJAX GET DETALLE RECETA
                $.ajax({
                    url: ruta,
                    type:"GET",
                    dataType: 'json',
                    data:{
                        "_token": "{{ csrf_token() }}",
                        id : id,
                    },
                    success:function(response){ 
                        //var html = '';
                        if (response && response.detalles_documentos && response.detalles_documentos.length > 0) {
                            // Loop through each detail and add it to the table
                            response.detalles_documentos.forEach(function(detail, index) {
                                var elimina = '{{ route("admin.cotizacion.detalles.destroy", ":quit") }}';
                                elimina = elimina.replace(':quit', `${detail.id}`);

                                var comun = `${detail.producto_comun}`;
                                var producto = '';
                                if(comun == ''){
                                    produccto = `${detail.producto_documento.nombre}`;
                                }else{
                                    produccto = `${detail.producto_comun}`;
                                }                            

                                html += `
                                    <tr class="text-center">
                                        <td style="width:20px;">
                                            <input type="hidden" name="producto_id[]" value="${detail.producto_documento.id}" class="producto_id" />
                                            <input type="hidden" name="product_name[]" value="${produccto}" class="product_name" />
                                            <input type="hidden" name="importe[]" value="${detail.importe}" class="importe" />
                                            <input type="number" name="cantidad[]" min="1" step="1" class="cantidad bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="${detail.cantidad}" />
                                        </td>
                                        <td>${produccto}</td>
                                        <td style="width:120px;">
                                            <input type="number" name="precio[]" class="precio campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                            min="${detail.precio_mayoreo}" value="${detail.precio}" step="any" required/>
                                        </td>
                                        <td>${detail.importe}</td>
                                        <td class="text-center">
                                            <form method="POST" id="form-delete" action="${elimina}" class="d-inline btn-eliminar"> 
                                                @csrf @method('DELETE')
                                                <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                    <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    <span class="sr-only">Quitar</span>
                                                </button>
                                                <input type="hidden" class="val-id" id="valId" value="${detail.id}" >
                                            </form>
                                        </td>
                                    </tr>
                                `;
                            });
                        } else {
                            html = `<tr><td colspan="5">No hay detalles disponibles</td></tr>`;
                        }

                        // Insert the generated HTML into the table body
                        $("#body_details_table").html(html);
                        suma();
                    },
                    error: function(response) {
                        /*Swal.fire({
                            icon: 'error',
                            title: 'Advertencia.',
                            text: 'Hubo un error durante el proceso, por favor intente de nuevo.',
                        });*/
                    },
                });
            }

        });
    </script>
@stop

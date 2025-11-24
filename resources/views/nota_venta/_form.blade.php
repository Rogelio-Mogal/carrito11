<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="num_factura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">N° factura</label>
        <input type="text" id="num_factura" name="num_factura" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="N° factura" value="{{ old('num_factura', $compra->num_factura) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="proveedor_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Proveedor
        </label>
        <div class="input-group">
            <select id="proveedor_id" name="proveedor_id" style="height: 400px !important;" required
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if (
                    $metodo == 'create' ||
                        old('proveedor_id', isset($compra) ? $compra->proveedor_id : '') == '') selected @endif>
                    -- PROVEEDOR --
                </option>
                @foreach ($proveedores as $value)
                    <option value="{{ $value->id }}"
                        {{ old('proveedor_id', isset($compra) ? $compra->proveedor_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->proveedor) }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="producto_caracteristicas_id" id="producto_caracteristicas_id">
            <input type="hidden" name="tipo" id="tipo" value="COMPRA_INTERNA">
        </div>
        <p class="msj-select mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span>
        </p>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="fecha_compra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha de
            compra</label>
        <input type="date" id="fecha_compra" name="fecha_compra" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            value="{{ old('fecha_compra', $compra->fecha_compra) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <div class="relative inline-block">
            <label for="iva" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                IVA
                <button data-popover-target="popover-iva" data-popover-placement="bottom-end" type="button">
                    <svg class="w-4 h-4 ms-2 text-gray-400 hover:text-gray-500" aria-hidden="true" fill="currentColor"
                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Show information</span>
                </button>
            </label>
        </div>

        <div id="popover-iva" role="tooltip"
            class="absolute z-10 invisible inline-block w-64 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
            <div class="p-3 space-y-2">
                <h5 class="font-semibold text-gray-900 dark:text-black">Casilla activada</h5>
                <p><strong>La lista de productos agregados se incluira el 16% de IVA</strong></p>
                <h5 class="font-semibold text-gray-900 dark:text-black">Casilla desactivada</h5>
                <p><strong>La lista de productos agregados NO incluira el 16% de IVA</strong></p>
            </div>
            <div data-popper-arrow></div>
        </div>

        <div class="flex items-center ps-2 py-2">
            <input checked id="iva" type="checkbox" value=""
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="iva" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sin IVA</label>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="fecha_compra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Busqueda
        </label>
        <button data-modal-target="producto-modal" data-modal-toggle="producto-modal" id="btn-product"
            class="block w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            type="button">
            Productos
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="codigo_barra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Código de
            barra</label>
        <input type="text" id="codigo_barra" name="codigo_barra"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Código de barra" value="" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="producto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
        <input type="text" id="producto" name="producto"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Producto" value="" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="cant" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cantidad</label>
        <input type="number" id="cant" name="cant" min="1" step="1"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cantidad" value="" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="precio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Precio</label>
        <input type="number" id="precio" name="precio" min="0" step="0.01"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Precio" value="" />
        <input type="hidden" name="producto_id" id="producto_id">
        <input type="hidden" name="producto_serie" id="producto_serie">
    </div>
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <label for="btn-add" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agregar</label>
        <button type="button" id="btn-add"
            class="add-producto text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6" />
            </svg>
            <span class="sr-only">Agregar</span>
        </button>
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <h4 class="text-xl font-bold dark:text-white text-center">PRODUCTOS AGREGADOS</h4>
    </div>


    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="item_table_1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">Serie</th>
                        <th scope="col" class="px-6 py-3">Cantidad</th>
                        <th scope="col" class="px-6 py-3">Producto</th>
                        <th scope="col" class="px-6 py-3">Serie</th>
                        <th scope="col" class="px-6 py-3">Código</th>
                        <th scope="col" class="px-6 py-3">P.Compra</th>
                        <th scope="col" class="px-6 py-3">P.Costo</th>
                        <th scope="col" class="px-6 py-3">P.Mayoreo</th>
                        <th scope="col" class="px-6 py-3">P.Medio Mayoreo</th>
                        <th scope="col" class="px-6 py-3">P.Público</th>
                        <th scope="col" class="px-6 py-3">Importe</th>
                        <th scope="col" class="px-6 py-3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @include('compras._modal_productos')

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR COMPRA
            @elseif($metodo == 'edit')
                EDITAR COMPRA
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {

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
                    html += '<td class="px-6 py-4 text-center">';
                    html += '<input id="is_serie" name="is_serie[]" type="checkbox" value="'+is_serie+'" '+check;
                    html += '       class="is_serie w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">';
                    html += '<input type="hidden" name="check_serie[]" value="'+is_serie+'" class="check_serie" />';
                    html += '</td>';
                    html += '<td style="width:120px;">';
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

            $('#proveedor_id').select2({
                placeholder: "-- PROVEEDOR --",
                allowClear: true
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

            // CAMBIO EL VALOR DEL CHECK NUM SERIE
            $(document).on('change', '.is_serie', function() {
                $(this).val(this.checked ? 1 : 0);
                // Buscar el textarea en la misma fila que el checkbox
                let textarea = $(this).closest('tr').find('textarea[name="serie[]"]');
                let check_serie = $(this).closest('tr').find('input[type="hidden"][name="check_serie[]"]');

                // Si el checkbox está marcado, agregar la propiedad "required" al textarea
                if (this.checked) {
                    textarea.attr('required', 'required');
                    check_serie.val(1);
                } else {
                    // Si el checkbox no está marcado, quitar la propiedad "required" del textarea
                    textarea.removeAttr('required');
                    check_serie.val(0);
                }
            });

            // MUESTRA EL MODAL DE LOS PRODUCTOS
            $('#btn-product').click(function() {
                if ($.fn.DataTable.isDataTable('#productos')) {
                    table.ajax.reload(); // Recargar los datos de la tabla
                } else {
                    productos(); // Inicializar la tabla si aún no está inicializada
                }
            });

            // selecciona el producto del datatable/ modal
            $(document).on('click', '#productos tr', function(e) {
                //var table = $('#productos').DataTable();
                var producto = table.row(this).data()['nombre'];
                var idproducto = table.row(this).data()['id'];
                var serie = table.row(this).data()['serie'];

                $('#producto').val(producto);
                $('#producto_id').val(idproducto);
                $('#producto_serie').val(serie);

                $("#producto-modal").addClass('hidden');
                $('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80').remove();
            });

            // CAMBIA LOS VALORES SI SE ACTOIVA EL CAMPO SIN IVA
            $(document).on('click', '#iva', function() {
                suma();
            });

            // OBTENGO LOS PRECIOS
            let tipo;
            let porcentaje_publico;
            let porcentaje_medio;
            let porcentaje_mayoreo;
            let especifico_publico;
            let especifico_medio;
            let especifico_mayoreo;

            function obtenerPrecio(precio, producto) {
                const ajaxData = {
                    "_token": "{{ csrf_token() }}",
                    precio: precio,
                    id: producto,
                };
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: "{{ route('obtener.precios') }}",
                        type: "GET",
                        dataType: 'json',
                        data: ajaxData,
                        success: function(data) {
                            resolve(data);
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                            console.error('Error al obtener los datos de precios:', error);
                        }
                    });
                });
            }

            async function suma() {
                try {
                    var sum = 0;
                    var promises = $('#item_table_1 tr .importe').map(async function() {
                        var $row = $(this).closest("tr");
                        var subVenta = parseFloat($row.find(".importe").val());
                        var cantidad = parseFloat($row.find(".cantVenta").val());
                        var precio = parseFloat($row.find(".pc").val()); // se cambio el valor de .pu por .pc
                        var productoId = $row.find(".idp").val();

                        if ($("#iva").is(":checked")) {
                            $row.find(".pu").val($row.find(".pu").attr("con-iva"));
                            $row.find(".pu").attr('value', $row.find(".pu").attr("con-iva"));
                            parseFloat($row.find(".pc").val($row.find(".pc").attr("con-iva")));
                            precio = parseFloat($row.find(".pc").val()); // se cambio el valor de .pu por .pc

                            parseFloat($row.find(".pm").attr("min", $row.find(".pc").attr(
                                "con-iva")));
                            parseFloat($row.find(".pmm").attr("min", $row.find(".pc").attr(
                                "con-iva")));
                            parseFloat($row.find(".pp").attr("min", $row.find(".pc").attr(
                                "con-iva")));

                            $row.find('.importe').val(cantidad * precio);

                            // OBTENGO LOS VALORES DE LA TABLA DE PRECIOS
                            try {
                                const data = await obtenerPrecio(Math.ceil(precio), productoId);
                                // Procesa los datos obtenidos aquí
                                var entero = Math.ceil(precio);
                                tipo = data.tipo;
                                porcentaje_publico = (data.precio
                                    .porcentaje_publico / 100 + 1);
                                porcentaje_medio = (data.precio
                                    .porcentaje_medio / 100 + 1);
                                porcentaje_mayoreo = (data.precio
                                    .porcentaje_mayoreo / 100 + 1);
                                especifico_publico = data.precio
                                    .especifico_publico;
                                especifico_medio = data.precio.especifico_medio;
                                especifico_mayoreo = data.precio
                                    .especifico_mayoreo;

                                if (tipo == 'general') {
                                    pP = parseFloat(entero * porcentaje_publico)
                                        .toFixed(2);
                                    pMM = parseFloat(entero * porcentaje_medio)
                                        .toFixed(2);
                                    pM = parseFloat(entero * porcentaje_mayoreo)
                                        .toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * precio) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * precio)
                                            .toFixed(2);
                                    }
                                    $row.find(".pm").val(Math.ceil(pM));
                                    $row.find(".pmm").val(Math.ceil(pMM));
                                    $row.find(".pp").val(Math.ceil(pP));
                                } else if (tipo == 'especifico') {
                                    pP = parseFloat(entero + parseFloat(
                                        especifico_publico)).toFixed(2);
                                    pMM = parseFloat(entero + parseFloat(
                                        especifico_medio)).toFixed(2);
                                    pM = parseFloat(entero + parseFloat(
                                        especifico_mayoreo)).toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * precio) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * precio)
                                            .toFixed(2);
                                    }
                                    $row.find(".pm").val(Math.ceil(pM));
                                    $row.find(".pmm").val(Math.ceil(pMM));
                                    $row.find(".pp").val(Math.ceil(pP));
                                }
                            } catch (error) {
                                console.error('Error al obtener el precio:', error);
                            }
                        } else {
                            //console.log('precio sin IVA: ');
                            $row.find(".pu").val($row.find(".pu").attr("sin-iva"));
                            parseFloat($row.find(".pc").val($row.find(".pc").attr("sin-iva")));
                            precio = parseFloat($row.find(".pc").val()); // se cambio el valor de .pu por .pc
                            //console.log('suma precio SIN: ' + precio);

                            parseFloat($row.find(".pm").attr("min", $row.find(".pc").attr(
                                "sin-iva")));
                            parseFloat($row.find(".pmm").attr("min", $row.find(".pc").attr(
                                "sin-iva")));
                            parseFloat($row.find(".pp").attr("min", $row.find(".pc").attr(
                                "sin-iva")));

                            $row.find('.importe').val(cantidad * precio);

                            // OBTENGO LOS VALORES DE LA TABLA DE PRECIOS
                            try {
                                const data = await obtenerPrecio(Math.ceil(precio), productoId);
                                // Procesa los datos obtenidos aquí
                                var entero = Math.ceil(precio);
                                tipo = data.tipo;
                                porcentaje_publico = (data.precio
                                    .porcentaje_publico / 100 + 1);
                                porcentaje_medio = (data.precio
                                    .porcentaje_medio / 100 + 1);
                                porcentaje_mayoreo = (data.precio
                                    .porcentaje_mayoreo / 100 + 1);
                                especifico_publico = data.precio
                                    .especifico_publico;
                                especifico_medio = data.precio.especifico_medio;
                                especifico_mayoreo = data.precio
                                    .especifico_mayoreo;

                                if (tipo == 'general') {
                                    pP = parseFloat(entero * porcentaje_publico)
                                        .toFixed(2);
                                    pMM = parseFloat(entero * porcentaje_medio)
                                        .toFixed(2);
                                    pM = parseFloat(entero * porcentaje_mayoreo)
                                        .toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * precio) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * precio)
                                            .toFixed(2);
                                    }
                                    $row.find(".pm").val(Math.ceil(pM));
                                    $row.find(".pmm").val(Math.ceil(pMM));
                                    $row.find(".pp").val(Math.ceil(pP));
                                } else if (tipo == 'especifico') {
                                    pP = parseFloat(entero + parseFloat(
                                        especifico_publico)).toFixed(2);
                                    pMM = parseFloat(entero + parseFloat(
                                        especifico_medio)).toFixed(2);
                                    pM = parseFloat(entero + parseFloat(
                                        especifico_mayoreo)).toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * precio) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * precio)
                                            .toFixed(2);
                                    }
                                    $row.find(".pm").val(Math.ceil(pM));
                                    $row.find(".pmm").val(Math.ceil(pMM));
                                    $row.find(".pp").val(Math.ceil(pP));
                                }
                            } catch (error) {
                                console.error('Error al obtener el precio:', error);
                            }
                        }
                        if (!isNaN(subVenta) && subVenta.length !== 0) {
                            //sum += subVenta;
                            sum += cantidad * precio;
                        }
                    }).get(); // .get() convierte el objeto jQuery en un array de promesas

                    // Espera a que todas las promesas se resuelvan
                    await Promise.all(promises);

                    // Aquí puedes continuar con el resto de la lógica después de que todas las promesas se resuelvan
                    //console.log('Todas las promesas se resolvieron.');
                    $('#tpagar').val(parseFloat(sum).toFixed(2));

                } catch (error) {
                    // Manejo de errores
                    console.error('Error al obtener el precio:', error);
                }
            }

            // INSERTAMOS EN LA TABLA DINAMICA
            $(document).on('click', '.add-producto', async function() {
                var cant = parseInt($("#cant").val());
                var id = $("#producto_id").val();
                var producto = $("#producto").val();
                var price = parseFloat($("#precio").val()).toFixed(2);

                var precioP = 0;
                var precioMM = 0;
                var precioM = 0;
                var subtotal = 0;

                var precio = 0;
                let isSerie = $('#producto_serie').val() || 0; //$('#producto_serie').val();

                // VALIDA SI HAY ELEMENTOS A AGREGAR
                if (producto === "" || isNaN(precio) || isNaN(cant)) {
                    //console.log('Hay vacio');
                    Swal.fire({
                        icon: "error",
                        title: 'Hay datos incompletos por requisitar.',
                        text: 'Por favor verifique la información.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                }

                var html = '';
                if (producto === "" || isNaN(precio) || isNaN(cant)) {
                    //console.log('Hay vacio');
                    Swal.fire({
                        icon: "error",
                        title: 'Hay datos incompletos por requisitar.',
                        text: 'Por favor verifique la información.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                } else {
                    //console.log('NO hay vacio');
                    // ### PARA MOSTRAR LOS VALORES DEL INVENTARIO ## //
                    var showUrl = "{{ route('admin.producto.servicio.show', ':id') }}";
                    var showLink = showUrl.replace(':id', id);
                    $.ajax({
                        url: showLink,
                        dataType: 'json',
                        cache: false,
                        //success: function(data) {
                        success: async function(data) {
                            // PRODUCTO SIN INVENTARIO
                            //console.log('data: '+data);
                            //console.log('data.length: '+data.length);
                            if ($("#iva").is(":checked")) {
                                precio = (price * 1.16).toFixed(2);
                            } else {
                                precio = price;
                            }

                            // OBTENGO LOS VALORES DE LA TABLA DE PRECIOS
                            const datos = await obtenerPrecio(Math.ceil(precio), id);
                            var entero = Math.ceil(precio);
                            tipo = datos.tipo;
                            porcentaje_publico = (datos.precio.porcentaje_publico / 100 + 1);
                            porcentaje_medio = (datos.precio.porcentaje_medio / 100 + 1);
                            porcentaje_mayoreo = (datos.precio.porcentaje_mayoreo / 100 + 1);
                            especifico_publico = datos.precio.especifico_publico;
                            especifico_medio = datos.precio.especifico_medio;
                            especifico_mayoreo = datos.precio.especifico_mayoreo;

                            if (tipo == 'general') {
                                //console.log('precio general');
                                precioP = parseFloat(entero * porcentaje_publico).toFixed(2);
                                precioMM = parseFloat(entero * porcentaje_medio).toFixed(2);
                                precioM = parseFloat(entero * porcentaje_mayoreo).toFixed(2);
                                if ($("#iva").is(":checked")) {
                                    subtotal = parseFloat((cant * price) * 1.16).toFixed(2);
                                } else {
                                    subtotal = parseFloat(cant * price).toFixed(2);
                                }
                            } else if (tipo == 'especifico') {
                                //console.log('precio especifico');
                                precioP = parseFloat(entero + parseFloat(especifico_publico)).toFixed(2);
                                precioMM = parseFloat(entero + parseFloat(especifico_medio)).toFixed(2);
                                precioM = parseFloat(entero + parseFloat(especifico_mayoreo)).toFixed(2);
                                if ($("#iva").is(":checked")) {
                                    subtotal = parseFloat((cant * price) * 1.16).toFixed(2);
                                } else {
                                    subtotal = parseFloat(cant * price).toFixed(2);
                                }
                            }

                            var check = 'checked';
                            if (data.length === 0) {
                                var requerido = '';
                                if (isSerie == 1) {
                                    requerido = 'required';
                                    //check = 'checked';
                                }
                                console.log('NO EXISTE EL PRODUCTO');

                                html += '<tr>';
                                html += '<td class="px-6 py-4 text-center">';
                                html += '<input id="is_serie" name="is_serie[]" type="checkbox" value="1" '+check;
                                html += '       class="is_serie w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">';
                                html += '<input type="hidden" name="check_serie[]" value="1" class="check_serie" />';
                                html += '</td>';
                                html += '<td style="width:120px;">';
                                html +=
                                    '<input type="text" name="cantVenta[]" class="cantVenta bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' +
                                    cant + '" readonly/>';
                                html += '<input type="hidden" name="idproducto[]" value="' +
                                    id +
                                    '" class="idp bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />';
                                html += '</td>';
                                html += '<td>';
                                html += producto +
                                    ' <input type="hidden" name="producto[]" value="' +
                                    producto + '" class="form-control" readonly />';
                                html += '</td>';
                                html += '<td style="width:200px;">';
                                html +=
                                    '<textarea name="serie[]" rows="1" cols="20" class="serie uppercase bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" ' +
                                    requerido + ' ></textarea>';
                                html += '<input type="hidden" name="value_serie[]" value="1" class="value_serie" required/>';
                                html += '</td>';
                                html += '<td>';
                                html += '<input type="text" name="codigo_proveedor[]" class="codigo_proveedor bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value=""/>';
                                html += '</td>';
                                html += '<td style="width:135px;">';
                                html +=
                                    '<input type="text" name="pu[]" class="pu bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" con-iva="' +
                                    parseFloat(price * 1.16).toFixed(2) + '" sin-iva="' +
                                    price +
                                    '" value="' + price + '" readonly/>';
                                html += '</td>';
                                html += '<td style="width:135px;">';
                                html +=
                                    '<input type="text" name="pc[]" class="pc bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" con-iva="' +
                                    parseFloat(price * 1.16).toFixed(2) + '" sin-iva="' +
                                    price +
                                    '" value="' + price + '" readonly/>';
                                html += '</td>';
                                html += '<td style="width:135px;">';
                                html +=
                                    '<input type="number" name="pm[]" class="pm campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                                    Math.ceil(precio) + '" value="' + Math.ceil(precioM) +
                                    '" step="any" required/>';
                                html += '</td>';
                                html += '<td style="width:135px;">';
                                html +=
                                    '<input type="number" name="pmm[]" class="pmm campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                                    Math.ceil(precio) + '" value="' + Math.ceil(precioMM) +
                                    '" step="any" required/>';
                                html += '</td>';
                                html += '<td style="width:135px;">';
                                html +=
                                    '<input type="number" name="pp[]" class="pp campo-requerido bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="' +
                                    Math.ceil(precio) + '" value="' + Math.ceil(precioP) +
                                    '" step="any" required/>';
                                html += '</td>';
                                html += '<td style="width:150px;">';
                                html +=
                                    '<input type="number" name="ImporteSalida[]" class="importe bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="' +
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
                                    $('#item_table_1 tr .idp').each(function() {
                                        var $row = $(this).closest("tr");
                                        product = $row.find(".idp").val();
                                        if (id == product) {

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
                            }

                            // PRODUCTOS CON INVENTARIO
                            $(data).each(async function(i, v) {
                                var costo = parseFloat(v.inventario.precio_costo);
                                var cantInvent = parseInt(v.inventario.cantidad);
                                var pInventario = cantInvent * costo;
                                var costoCompra = parseFloat(precio);
                                var cantCompra = parseInt(cant);
                                var pCompra = cant * precio;
                                var cantTotal = cantInvent + cantCompra;
                                var costoTotal = pInventario + pCompra;
                                var newCosto = (costoTotal / cantTotal).toFixed(
                                    2);

                                var newCostoIva = 0;
                                var newCostoSinIva = 0;
                                if ($("#iva").is(":checked")) {
                                    precio = (precio / 1.16).toFixed(2);
                                    pCompra = cant * precio;
                                    costoTotal = pInventario + pCompra;

                                    newCostoIva = newCosto;
                                    newCostoSinIva = (costoTotal / cantTotal)
                                        .toFixed(2);
                                } else {
                                    precio = (precio * 1.16).toFixed(2);
                                    pCompra = cant * precio;
                                    costoTotal = pInventario + pCompra;

                                    newCostoSinIva = newCosto;
                                    newCostoIva = (costoTotal / cantTotal)
                                        .toFixed(2);
                                }

                                var pP = 0;
                                var pMM = 0;
                                var pM = 0;

                                //console.log('newCosto: ' + newCosto);

                                console.log('EXISTE EL PRODUCTO, NUEVO COSTO');
                                

                                // OBTENGO LOS VALORES DE LA TABLA DE PRECIOS
                                const data = await obtenerPrecio(Math.ceil(
                                    newCosto), id);
                                var entero = Math.ceil(newCosto);
                                tipo = data.tipo;
                                porcentaje_publico = (data.precio
                                    .porcentaje_publico / 100 + 1);
                                porcentaje_medio = (data.precio
                                    .porcentaje_medio / 100 + 1);
                                porcentaje_mayoreo = (data.precio
                                    .porcentaje_mayoreo / 100 + 1);
                                especifico_publico = data.precio
                                    .especifico_publico;
                                especifico_medio = data.precio.especifico_medio;
                                especifico_mayoreo = data.precio
                                    .especifico_mayoreo;

                                if (tipo == 'general') {
                                    pP = parseFloat(entero * porcentaje_publico)
                                        .toFixed(2);
                                    pMM = parseFloat(entero * porcentaje_medio)
                                        .toFixed(2);
                                    pM = parseFloat(entero * porcentaje_mayoreo)
                                        .toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * price) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * price)
                                            .toFixed(2);
                                    }
                                } else if (tipo == 'especifico') {
                                    pP = parseFloat(entero + parseFloat(
                                        especifico_publico)).toFixed(2);
                                    pMM = parseFloat(entero + parseFloat(
                                        especifico_medio)).toFixed(2);
                                    pM = parseFloat(entero + parseFloat(
                                        especifico_mayoreo)).toFixed(2);
                                    if ($("#iva").is(":checked")) {
                                        subtotal = parseFloat((cant * price) *
                                            1.16).toFixed(2);
                                    } else {
                                        subtotal = parseFloat(cant * price)
                                            .toFixed(2);
                                    }
                                }

                                if (v.inventario.precio_costo == 0) {} else {
                                    //console.log('estoy en este else');
                                    var requerido = '';
                                    if (isSerie == 1) {
                                        requerido = 'required';
                                    }
                                    // ##  EN ESTA PARTE YA EXISTE EL PRODUCTO
                                    html += '<tr>';
                                    html += '<td class="px-6 py-4 text-center">';
                                    html += '<input id="is_serie" name="is_serie[]" type="checkbox" value="1" '+check;
                                    html += '       class="is_serie w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">';
                                    html += '<input type="hidden" name="check_serie[]" value="1" class="check_serie" />';
                                    html += '</td>';
                                    html += '<td style="width:120px;">';
                                    html +=
                                        '<input type="text" name="cantVenta[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 cantVenta" value="' +
                                        cant + '" readonly/>';
                                    html +=
                                        '<input type="hidden" name="idproducto[]" value="' +
                                        id + '" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 idp" />';
                                    html += '</td>';
                                    html += '<td>';
                                    html += producto +
                                        ' <input type="hidden" name="producto[]" value="' +
                                        producto +
                                        '" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly />';
                                    html += '</td>';
                                    html += '<td style="width:200px;">';
                                    html +=
                                        '<textarea name="serie[]" rows="1" cols="20" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 serie uppercase" ' +
                                        requerido + ' ></textarea>';
                                    //html += '<input type="hidden" name="is_serie[]" value="' +isSerie +'" class="is_serie" />';
                                    html += '</td>';
                                    html += '<td>';
                                    html += '<input type="text" name="codigo_proveedor[]" class="codigo_proveedor bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value=""/>';
                                    html += '</td>';
                                    html += '<td style="width:135px;">';
                                    html +=
                                        '<input type="text" name="pu[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pu" con-iva="' +
                                        parseFloat(price * 1.16).toFixed(2) +
                                        '" sin-iva="' +
                                        price + '" value="' + price +
                                        '" readonly/>';
                                    html += '</td>';
                                    html += '<td style="width:135px;">' +
                                        '<input type="text" name="pc[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pc" ' +
                                        'con-iva="' + newCostoIva +
                                        '" sin-iva="' + newCostoSinIva +
                                        '" value="' + newCosto + '" readonly/>';
                                    html += '</td>';
                                    html += '<td style="width:135px;">';
                                    html +=
                                        '<input type="number" name="pm[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pm campo-requerido" min="' +
                                        Math.ceil(newCosto) + '" value="' + Math
                                        .ceil(pM) +
                                        '" step="any" required/>';
                                    html += '</td>';
                                    html += '<td style="width:135px;">';
                                    html +=
                                        '<input type="number" name="pmm[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pmm campo-requerido" min="' +
                                        Math.ceil(newCosto) + '" value="' + Math
                                        .ceil(pMM) +
                                        '" step="any" required/>';
                                    html += '</td>';
                                    html += '<td style="width:135px;">';
                                    html +=
                                        '<input type="number" name="pp[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 pp campo-requerido" min="' +
                                        Math.ceil(newCosto) + '" value="' + Math
                                        .ceil(pP) +
                                        '" step="any" required/>';
                                    html += '</td>';
                                    html += '<td style="width:150px;">';
                                    html +=
                                        '<input type="number" name="ImporteSalida[]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 importe" value="' +
                                        subtotal + '" readonly/>';
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 text-center">';
                                    html +=
                                        '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                                    html +=
                                        '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                                    html +=
                                        '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                                    html +=
                                        '       </svg>                          ';
                                    html +=
                                        '       <span class="sr-only">Quitar</span>';
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
                                        $('#item_table_1 tr .idp').each(
                                            function() {
                                                var $row = $(this).closest(
                                                    "tr");
                                                product = $row.find(".idp")
                                                    .val();
                                                if (id == product) {
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
                                        $(this).parent('td').parent(
                                            'tr').remove();
                                        suma();
                                    });
                                }
                            });
                        },
                    });
                    $('#producto_id').val('');
                    $('#producto').val('');
                    $("#cant").val('');
                    $("#precio").val('');
                }
            });

            // BUSCAMOS SI ESTA DUPLICADO EL NUMERO DE FACTURA
            $(document).on('change', '#num_factura', function(e) {
                var factura = $('#num_factura').val();
                var showUrl = "{{ route('busca.factura.duplicada', ':id') }}";
                var showLink = showUrl.replace(':id', factura);

                $.ajax({
                    url: showLink,
                    dataType: 'json',
                    cache: false,
                    success: function(data) {
                        if (!data.exists) {
                            $('#num_factura').val(factura);
                            $("#btn-submit").show();
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: 'El número de factura: ' + factura + ' ya se encuentra registrado.',
                                html: 'Por favor verifique la información.',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                },
                                buttonsStyling: false
                            });
                            $('#num_factura').val('');
                            $("#btn-submit").hide();
                        }
                    },
                });
            });

            // BUSCAMOS SI EL NUMERO DE SERIE ESTA DUPLICADO
            $(document).on('change', '.serie', function(e) {
                var textarea = $(this);
                var allValues = textarea.val().split('|').filter(Boolean); // Dividir por `|` y filtrar valores vacíos
                var latestValue = allValues[allValues.length - 1]; // Obtener el último valor ingresado

                if (latestValue) {
                    var showUrl = "{{ route('busca.num.serie.duplicado', ':id') }}";
                    var showLink = showUrl.replace(':id', latestValue);

                    $.ajax({
                        url: showLink,
                        dataType: 'json',
                        cache: false,
                        success: function(data) {
                            if (!data.exists) {
                                //$('.serie').val(latestValue);
                                $("#btn-submit").show();
                            } else {
                                Swal.fire({
                                    icon: "warning",
                                    title: 'El número de serie: ' + latestValue + ' ya se encuentra registrado.',
                                    html: 'Por favor verifique la información.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false
                                });
                                textarea.val(textarea.val().replace(latestValue + '|', '')); // Eliminar el valor duplicado
                                $("#btn-submit").hide();
                            }
                        },
                    });
                }
            });

            // Agrega un evento keydown al campo serie[]
            $(document).on('keydown', 'textarea.serie', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    var textarea = $(this);
                    var currentValue = textarea.val();
                    
                    // Añadir un separador `|` solo si no está al final del valor actual
                    if (!currentValue.endsWith('|')) {
                        textarea.val(currentValue + '|');
                    }
                }
            });

            // PARA VALIDAR LOS NUMEROS DE SERIE
            var submitBtn = document.getElementById('btn-submit');
            var form = submitBtn.form;

            // Agrega un evento click al botón de envío
            //submitBtn.addEventListener('click', function(event) {
            $(document).on('click', '#btn-submit', function(event) {
                //console.log('validaciones');
                //console.log('IF CHECK: '+form.checkValidity());
                // Prevenir el envío del formulario por defecto
                if (form.checkValidity()) {
                    event.preventDefault();
                    var valida = 1;
                    var seriesGlobally =
                        new Set(); // Para almacenar todas las series globales y verificar duplicados

                    // Itera a través de las filas de la tabla
                    //$('#item_table_1 tr .serie:required').each(function(index, row) {
                    //$('#item_table_1 tr .is_serie:required').each(function(index, row) {
                    $('#item_table_1 tr').each(function(index, row) {
                        let isSerie = $(row).find('.is_serie').val();
                        var $row = $(this).closest("tr");
                        var cantidad = parseInt($row.find(".cantVenta").val());
                        var serie = $row.find(".serie").val();

                        if (isSerie == 1) {
                            if (serie != null) {
                                // Separa las series por '|' y elimina espacios en blanco
                                var series = serie.split('|').map(function(s) {
                                    return s.trim();
                                }).filter(function(s) {
                                    return s !== '';
                                });

                                // Verifica que el número de series sea igual a la cantidad
                                if (series.length !== cantidad) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: 'Número de series incorrecto en la fila: ' + (
                                            index ),
                                        html: 'Por favor verifique la información.',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                        },
                                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                                    });
                                    valida = 0;
                                    return false; // Detener el bucle each
                                }

                                // Verifica que no haya series duplicadas en la misma fila
                                var seriesUnicas = new Set(series);
                                if (seriesUnicas.size !== series.length) {
                                    Swal.fire({
                                        icon: "warning",
                                        title: 'Existen series duplicadas en la fila: ' + (
                                            index ),
                                        html: 'Por favor verifique la información.',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                        },
                                        buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                                    });
                                    valida = 0;
                                    return false; // Detener el bucle each
                                }

                                // Verifica que no haya series duplicadas en todo el conjunto global
                                for (let s of series) {
                                    if (seriesGlobally.has(s)) {
                                        Swal.fire({
                                            icon: "warning",
                                            title: 'Serie duplicada encontrada: ' + s,
                                            html: 'Por favor verifique la información.',
                                            showCancelButton: false,
                                            confirmButtonText: 'OK',
                                            customClass: {
                                                confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                            },
                                            buttonsStyling: false // Deshabilitar el estilo predeterminado de SweetAlert2
                                        });
                                        valida = 0;
                                        return false; // Detener el bucle each
                                    }
                                    seriesGlobally.add(s);
                                }
                            }
                        }
                    });

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
        });

        var table;
        // OBTEMGO LOS PRODUCTOS POR AJAX
        function productos() {
            const postData = {
                _token: $('input[name=_token]').val(),
                origen: 'productos.compras',
            };

            // Inicializar DataTable
            table = $('#productos').DataTable({
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
                        name: 'codigo_barra'
                    },
                    {
                        data: 'serie',
                        name: 'serie',
                        visible: false,
                        searchable: false
                    }
                ],
            });
        }
    </script>
@stop

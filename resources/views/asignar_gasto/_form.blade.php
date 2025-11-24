<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-1">
        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Fecha
        </label>
        <input type="date" id="date" name="date"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        />
    </div>

    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Monto</label>
        <input type="number" id="amount" name="amount" min="0" step="0.01"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Monto" />
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="gasto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Gastos
        </label>
        <div class="input-group">
            <select id="gasto" name="gasto" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- GASTOS --</option>
                @foreach ($gasto as $value)
                    <option value="{{ $value->id }}" data-gasto="{{ $value->gasto }}" data-tipo-gasto="{{ $value->tipoGasto->tipo_gasto }}">
                        {{ ucfirst($value->gasto) }} - {{ ucfirst($value->tipoGasto->tipo_gasto) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="forma_pago" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Forma de pago
        </label>
        <div class="input-group">
            <select id="forma_pago" name="forma_pago" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- FORMA DE PAGO --
                </option>
                @foreach ($formaPago as $value)
                    <option value="{{ $value->id }}">
                        {{ ucfirst($value->forma_pago) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="note" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nota
            adicional</label>
        <textarea id="note" name="note" rows="1"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Nota adicional"></textarea>
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
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="item_table_1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Fecha</th>
                        <th scope="col" class="px-6 py-3">Gasto</th>
                        <th scope="col" class="px-6 py-3">Tipo</th>
                        <th scope="col" class="px-6 py-3">Monto</th>
                        <th scope="col" class="px-6 py-3">Forma de pago</th>
                        <th scope="col" class="px-6 py-3">Nota</th>
                        <th scope="col" class="px-6 py-3">Opciones</th>
                    </tr>
                </thead>
                <tbody class="text-xl text-gray-900 uppercase dark:bg-gray-700 dark:text-gray-400">
                </tbody>
                <tfoot>
                    <tr class="text-xs text-gray-900 bg-indigo-200 dark:bg-gray-700 dark:text-gray-400">
                        <th colspan="7" style="text-align:right">
                            <h2 id="total_pagar" class="text-xl font-bold dark:text-white text-right mr-4"></h2>
                            <input type="hidden" id="total" name="total" step="any">
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            &nbsp;
        </label>
        <button type="submit"
            id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR GASTO
            @elseif($metodo == 'edit')
                EDITAR GASTO
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            $('#gasto').select2({
                placeholder: "-- GASTOS --",
                allowClear: true
            });

            $('#forma_pago').select2({
                placeholder: "-- FORMA DE PAGO--",
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

            // FORMATO MONEDA
            function formatToFloat(data) {
                return data.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,");
            };

            // SUMA TOTAL DE SERVICIOS
            function suma() {
                var totalSumado = 0;
                $('#item_table_1 tbody tr').slice(0).each(function() {
                    var total = parseFloat($(this).find('td:eq(3)').text().replace('$', '').replace(',', ''));
                    totalSumado += total;
                });
                $('#total_pagar').html("TOTAL: $ " + formatToFloat(totalSumado));
                $('#total').val(totalSumado);
            }

            // INSERTAMOS EN LA TABLA DINAMICA
            $(document).on('click', '.add-producto', function() {
                var fecha = $("#date").val();
                var monto = $("#amount").val();
                var montoFloat = parseFloat(monto);
                var gasto = $("#gasto :selected").data('gasto');
                var tipoGasto = $("#gasto :selected").data('tipo-gasto');
                var gastoId = $("#gasto").val();
                var formaPagoId = $("#forma_pago").val();
                var formaPago = $("#forma_pago option:selected").text();
                var nota = $('#note').val().toUpperCase();
                var subtotal = 0;

                console.log('gastoId: '+gastoId);
                console.log('formaPagoId: '+formaPagoId);

                var html = '';
                
                if (fecha === "" || monto === "" || monto <= 0 || gastoId === null || formaPagoId === null) {
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
                    /*
                    var servicioIdsAgregados = []; // Array para almacenar los servicioId agregados

                    // Obtener los servicioId que ya están en la tabla
                    $('#item_table_1').find('input[name="gasto_id[]"]').each(function() {
                        servicioIdsAgregados.push($(this).val());
                    });

                    // Verificar si el servicioId ya ha sido agregado
                    if (servicioIdsAgregados.includes(gastoId)) {
                        // Si ya ha sido agregado, omitir esta fila
                        return;
                    }

                    // Agregar el servicioId al array de servicioIds agregados
                    servicioIdsAgregados.push(gastoId);
                    */
                    html += '<tr>';
                    html +=     '<td class="text-center">';
                    html +=         '<input type="hidden" name="fecha[]" value="'+fecha+'"  />';
                    html +=         '<input type="hidden" name="monto[]" value="'+monto+'"  />';
                    html +=         '<input type="hidden" name="gasto_id[]" value="'+gastoId+'"  />';
                    html +=         '<input type="hidden" name="forma_pago_id[]" value="'+formaPagoId+'"  />';
                    html +=         '<input type="hidden" name="nota[]" value="'+nota+'"  />';
                    html +=          fecha;
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         gasto ;
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         tipoGasto ;
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         '$ '+formatToFloat(montoFloat) ;
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         formaPago ;
                    html +=     '</td>';
                    html +=     '<td>';
                    html +=         nota ;
                    html +=     '</td>';

                    html +=     '<td class="text-center">';
                    html +=         '<button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-1 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                    html +=             '<svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                    html +=                 '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                    html +=             '</svg>';
                    html +=             '<span class="sr-only">Quitar</span>';
                    html +=         '</button>';
                    html +=     '</td>';
                    html += '</tr>';


                    $('#item_table_1').append(html);
                    suma();

                    $('.remove').off().click(function(e) {
                        $(this).parent('td').parent('tr').remove();
                        suma();
                    });

                    // Limpio los campos
                    $("#date").val('');
                    $('#amount').val('');
                    $("#gasto").val('').trigger('change');
                    $("#forma_pago").val('').trigger('change');
                    $('#note').val('');
                }
                
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

            // Evitar el envío del formulario al presionar Enter, excepto en textarea
            $(document).on('keypress', function(e) {
                if (e.which == 13 && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                }
            });
            
            // PARA VALIDAR Y ENVIAR EL FORMULARIO DE GASTOS
            var submitBtn = document.getElementById('btn-submit');
            var form = submitBtn.form;


            // Agrega un evento click al botón de envío
            submitBtn.addEventListener('click', function(event) {
                console.log('1');
                // Prevenir el envío del formulario por defecto
                if (form.checkValidity()) {
                    event.preventDefault();
                    var valida = 1;

                    // Verifico si hay elementos en la tabla
                    var numeroDeRegistros = $("#item_table_1 tr").length - 1;
                    console.log('numeroDeRegistros: '+numeroDeRegistros);

                    if (numeroDeRegistros == 1) {
                        Swal.fire({
                            type: 'warning',
                            title: 'No ha agregado elementos',
                            html: 'Por favor verifique la información.',
                        });
                        valida = 0;
                    }

                    if (valida == 1) {
                        Swal.fire({
                            title: '¿Está seguro de realizar la asignación de gastos?',
                            type: 'question',
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí",
                            cancelButtonText: "No"
                        }).then((result) => {
                            if (result.value) {
                                $("#btn-submit").attr("disabled", true);
                                form.submit();
                            }else{
                                $("#pagar").removeAttr("disabled");
                            }
                        });
                    }

                } else {
                    console.log('validación nativa');
                    // Si el formulario no es válido, no prevengas el envío y deja que la validación nativa lo maneje
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

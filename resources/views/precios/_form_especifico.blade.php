<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-4">
        <label for="sub-familia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Sub Familia
        </label>
        <div class="input-group">
            <select id="sub-familia" name="sub-familia" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled
                    @if ($metodo == 'create' || old('producto_caracteristica_id', isset($precio) ? $precio->producto_caracteristica_id : '') == '') selected @endif>
                    -- SUB FAMILIA --
                </option>
                @foreach ($subfamilia as $value)
                    <option value="{{ $value->id }}"
                        {{ old('producto_caracteristica_id', isset($precio) ? $precio->producto_caracteristica_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="producto_caracteristica_id" id="producto_caracteristica_id">
        </div>
        <p class="msj-select mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-4">
        <label for="inicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Desde</label>
        <input type="number" id="inicio" name="inicio"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Desde" value="{{ old('inicio', $precio->inicio) }}" />
        <p class="msj-desde mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-4">
        <label for="fin" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hasta</label>
        <input type="number" id="fin" name="fin"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Hasta" value="{{ old('fin', $precio->fin) }}" />
        <p class="msj-hasta mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-3">
        <label for="publico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Público</label>
        <input type="text" id="publico" name="publico"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Público" value="{{ old('publico', $precio->publico) }}" />
        <p class="msj-publico mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-3">
        <label for="medio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Medio mayoreo</label>
        <input type="text" id="medio" name="medio"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Medio mayoreo" value="{{ old('medio', $precio->medio) }}" />
        <p class="msj-medio mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-3">
        <label for="mayoreo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mayoreo</label>
        <input type="text" id="mayoreo" name="mayoreo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Mayoreo" value="{{ old('mayoreo', $precio->mayoreo) }}" />
        <p class="msj-mayoreo mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Error.</span></p> 
    </div>
    <div class="sm:col-span-12 lg:col-span-1 md:col-span-3">
        <label for="btn-add" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agregar</label>
        <button type="button" id="btn-add" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
            <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 1 1 0-18c1.052 0 2.062.18 3 .512M7 9.577l3.923 3.923 8.5-8.5M17 14v6m-3-3h6"/>
            </svg>              
            <span class="sr-only">Agregar</span>
        </button>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <h4 class="text-xl font-bold dark:text-white text-center">PREVISUALIZACIÓN</h4>
    </div>
</div>

<div class="grid-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table id="item_table_1" class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-900 uppercase bg-indigo-200 dark:bg-gray-700 dark:text-gray-400 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">Desde</th>
                        <th scope="col" class="px-6 py-3">Hasta</th>
                        <th scope="col" class="px-6 py-3">Público</th>
                        <th scope="col" class="px-6 py-3">Medio mayoreo</th>
                        <th scope="col" class="px-6 py-3">Mayoreo</th>
                        <th scope="col" class="px-6 py-3">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            id="btn-submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR PRECIO
        @elseif($metodo == 'edit')
            EDITAR PRECIO
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            $('#sub-familia').select2({
                placeholder: "-- SUB FAMILIA --",
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


            // Al cambiar la opción del select
            $('#sub-familia').change(function() {
                var productoId = $("#sub-familia :selected").val();
                $('#producto_caracteristica_id').val(productoId);
            });

            // FUNCION PARA COMPARARA LOS RANGOS CON LA BD
            function preciosBd(inicio, fin, callback) {
                const ajaxData = {
                    "_token": "{{ csrf_token() }}",
                    desde: inicio,
                    hasta: fin,
                    tipo_precio: 2,
                    id: $('#producto_caracteristica_id').val(),
                };

                $.ajax({
                    url: "{{ route('compara.precios') }}",
                    type: "GET",
                    dataType: 'json',
                    data: ajaxData,
                    success: function(response) {
                        var rango = response.rango;
                        if ('error' in response) {
                            var encontrado = false;
                            $('#item_table_1 tr').each(function() {
                                var valorCelda = $(this).find('td').eq(0)
                                    .text(); // Suponiendo que el valor está en la primera celda
                                if (valorCelda.trim() === rango.toString().trim()) {
                                    encontrado = true;
                                    return false; // Salir del bucle each
                                }
                            });
                            if (encontrado) {
                                callback(1);
                            } else {
                                console.log('El valor consecutivo no está en la tabla.');
                                // Aquí puedes realizar otra acción si el valor no está en la tabla
                                Swal.fire({
                                    title: response.error,
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        } else {
                            // Procesar la respuesta exitosa
                            callback(1);
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
                            buttonsStyling: false  // Deshabilitar el estilo predeterminado de SweetAlert2
                        });
                    },
                });
            }

            // HABILITO/DESHABILITO EL BOTON ELIMINAR
            function botonEliminar() {
                // Deshabilitar todos los botones de eliminación
                $('#item_table_1 tr').each(function() {
                    $(this).find('.remove').prop('disabled', true);
                });
                // Habilitar el botón de eliminación de la última fila
                $('#item_table_1 tr:last-child').find('.remove').prop('disabled', false);

                // verifico si hay un elemento en la tabla para deshabilitar el select
                var numeroDeRegistros = $("#item_table_1 tr").length - 1;
                console.log('numeroDeRegistross: ' + numeroDeRegistros);

                if (numeroDeRegistros == 0) {
                    $('#sub-familia').prop('disabled', false);
                } else {
                    $('#sub-familia').prop('disabled', true);
                }
            }

            // COMPRUEBA SI EL CAMPO FIN ES MAYOR A INICIO
            $('#fin').on('input', function() {
                var desdeValue = $('#inicio').val();
                var hastaValue = $(this).val();

                if (parseInt(hastaValue) <= parseInt(desdeValue)) {
                    $(this).get(0).setCustomValidity('Debe ser mayor que el valor "Desde"');
                } else {
                    $(this).get(0).setCustomValidity('');
                }
            });

            // AGREGA LOS DATOS EN LA TABLA DINAMICA
            $('#btn-add').on('click', async function(e) {
                e.preventDefault();
                var html = '';
                var desde = $("#inicio").val();
                var hasta = $("#fin").val();
                var publico = $("#publico").val();
                var medio = $("#medio").val();
                var mayoreo = $("#mayoreo").val();
                var subfamilia = $("#subfamilia_id").val();
                var valida = 1;

                $('.msj-desde').hide();
                $('.msj-hasta').hide();
                $('.msj-publico').hide();
                $('.msj-medio').hide();
                $('.msj-mayoreo').hide();
                $('.msj-select').hide();

                if (desde === '' || desde <= 0) {
                    $('.msj-desde').show();
                    $('.msj-desde').html("Requisite este campo.");
                }
                if (hasta === '' || hasta <= 0) {
                    $('.msj-hasta').show();
                    $('.msj-hasta').html("Requisite este campo.");
                }
                if (publico === '' || publico <= 0) {
                    $('.msj-publico').show();
                    $('.msj-publico').html("Requisite este campo.");
                }
                if (medio === '' || medio <= 0) {
                    $('.msj-medio').show();
                    $('.msj-medio').html("Requisite este campo.");
                }
                if (mayoreo === '' || mayoreo <= 0) {
                    $('.msj-mayoreo').show();
                    $('.msj-mayoreo').html("Requisite este campo.");
                }

                if (subfamilia === '') {
                    $('.msj-select').show();
                    $('.msj-select').html("Seleccione una opción.");
                    valida = 0;
                }

                if (parseInt(hasta) <= parseInt(desde)) {
                    // El valor de "hasta" no es mayor que el valor de "desde"
                    $('.msj-hasta').show();
                    $('.msj-hasta').html('El valor de "hasta" debe ser mayor que el valor de "desde".');
                    valida = 0;
                }

                if (isNaN(desde) || isNaN(hasta) || parseInt(desde) != desde || parseInt(hasta) !=
                    hasta) {
                    // Al menos uno de los valores no es un entero
                    Swal.fire({
                        title: 'Los valores desde y hasta deben de ser enteros.',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                        },
                        buttonsStyling: false  // Deshabilitar el estilo predeterminado de SweetAlert2
                    });
                    valida = 0;
                }

                if (desde > 0 && hasta > 0 && publico > 0 && medio > 0 && mayoreo > 0 && parseInt(
                        desde) == desde && parseInt(hasta) == hasta && valida == 1) {
                    preciosBd(desde, hasta, function(noRepetido) {
                        if (noRepetido) {
                            var rangoActual = desde + '-' + hasta;
                            var duplicado = false;

                            $('#item_table_1 tr').each(function() {
                                var rangoExistente = $(this).find('td:eq(0)').text() +
                                    '-' + $(this).find('td:eq(1)').text();
                                if (rangoActual === rangoExistente) {
                                    duplicado = true;
                                    return false; // Salir del bucle each
                                }
                            });

                            if (!duplicado) {
                                // Obtener todos los rangos de la tabla dinámica y ordenarlos
                                var rangos = [];
                                $('#item_table_1 tr').each(function() {
                                    var inicio = parseInt($(this).find('td:eq(0)')
                                    .text(), 10);
                                    var fin = parseInt($(this).find('td:eq(1)').text(),
                                        10);
                                    rangos.push({
                                        inicio: inicio,
                                        fin: fin
                                    });
                                });

                                // Ordenar los rangos por el valor de inicio
                                rangos.sort(function(a, b) {
                                    return a.inicio - b.inicio;
                                });

                                // Reconstruir la representación de texto de los rangos ordenados
                                var rangosTexto = rangos.map(function(rango) {
                                    return rango.inicio + '-' + rango.fin;
                                });

                                // Verificar que el nuevo rango sea consecutivo con el último rango en la tabla dinámica
                                var ultimoRango = rangosTexto.length > 0 ? rangosTexto[
                                    rangosTexto.length - 1] : null;
                                var ultimoRangoSeparado = ultimoRango ? ultimoRango.split('-') :
                                    null;
                                var numero = parseInt(ultimoRangoSeparado[1],
                                10); // Convertir a número
                                var resultado = numero + 1; // Sumar 1
                                var inicio = parseInt(desde) + 1;

                                if (rangos.length === 1 || !ultimoRango || resultado ===
                                    parseInt(desde)) {

                                    html += '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">';
                                    html += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">';
                                    html += '<input type="hidden" name="desde[]" value="' +
                                        desde +
                                        '"  />';
                                    html += '<input type="hidden" name="hasta[]" value="' +
                                        hasta +
                                        '"  />';
                                    html +=
                                        '<input type="hidden" name="tipo_precio[]" value="2"  />';
                                    html += desde;
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">';
                                    html += hasta;
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">';
                                    html +=
                                        '<input type="number"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="1" max="100" step="any" required name="especifico_publico[]" value="' +
                                        publico + '"  />';
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">';
                                    html +=
                                        '<input type="number"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="1" max="100" step="any" required name="especifico_medio[]" value="' +
                                        medio + '"  />';
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-black">';
                                    html +=
                                        '<input type="number"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" min="1" max="100" step="any" required name="especifico_mayoreo[]" value="' +
                                        mayoreo + '"  />';
                                    html += '</td>';
                                    html += '<td class="px-6 py-4 text-center">';
                                    html += '   <button type="button" name="remove" id="remove" class="remove focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">';
                                    html += '       <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">';
                                    html += '           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>';
                                    html += '       </svg>                          ';
                                    html += '       <span class="sr-only">Quitar</span>';
                                    html += '   </button>';
                                    html += '</td>';
                                    html += '</tr>';

                                    $('#item_table_1').append(html);

                                    // limpio los campos
                                    $("#inicio").val('');
                                    $("#fin").val('');
                                    $("#publico").val('');
                                    $("#medio").val('');
                                    $("#mayoreo").val('');

                                    // Asociar el evento de eliminación de elementos al nuevo elemento
                                    $('#item_table_1').find('tr').last().find('.remove').click(
                                        function(e) {
                                            $(this).closest('tr').remove();
                                            botonEliminar();
                                        }
                                    );
                                    botonEliminar();

                                } else {
                                    // Mostrar mensaje de error si el rango no es consecutivo
                                    Swal.fire({
                                        title: 'El rango ' + rangoActual +
                                            ' no es consecutivo con el último rango en la tabla.',
                                        showCancelButton: false,
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                        },
                                        buttonsStyling: false  // Deshabilitar el estilo predeterminado de SweetAlert2
                                    });
                                }
                            } else {
                                Swal.fire({
                                    title: 'El rango ' + rangoActual +
                                        ' ya ha sido agregado.',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                                    },
                                    buttonsStyling: false  // Deshabilitar el estilo predeterminado de SweetAlert2
                                });
                            }
                        }
                    });
                }

                // Limpiar el HTML
                html = '';
            });

            // PARA VALIDAR Y ENVIAR EL FORMULARIO DE GASTOS
            var submitBtn = document.getElementById('btn-submit');
            var form = submitBtn.form;

            // Agrega un evento click al botón de envío
            submitBtn.addEventListener('click', function(event) {
                // Prevenir el envío del formulario por defecto
                if (form.checkValidity()) {
                    event.preventDefault();
                    var valida = 1;

                    // Verifico si hay elementos en la tabla
                    var numeroDeRegistros = $("#item_table_1 tr").length - 1;

                    if (numeroDeRegistros == 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No ha agregado elementos',
                            html: 'Por favor verifique la información.',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800'
                            },
                            buttonsStyling: false  // Deshabilitar el estilo predeterminado de SweetAlert2
                        });
                        valida = 0;
                    }

                    if (valida == 1) {
                        Swal.fire({
                            title: '¿Está seguro de guardar los nuevos precios?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Sí",
                            cancelButtonText: "No"
                        }).then((result) => {
                            if (result.value) {
                                $("#pagar").attr("disabled", true);
                                form.submit();
                            } else {
                                $("#pagar").removeAttr("disabled");
                            }
                        });
                    }

                } else {
                    console.log('validación nativa');
                    // Si el formulario no es válido, no prevengas el envío y deja que la validación nativa lo maneje
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
    </script>
@stop

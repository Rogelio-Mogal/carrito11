<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="name" name="name" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('name', $cliente->name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellidos</label>
        <input type="text" id="last_name" name="last_name" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese los apellidos" value="{{ old('last_name', $cliente->last_name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
        <input type="email" id="email" name="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el correo" value="{{ old('email', $cliente->email) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
        <input type="text" id="telefono" name="telefono" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el teléfono" value="{{ old('telefono', $cliente->telefono) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="tipo_cliente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de cliente</label>
        <div class="input-group">
            <select id="tipo_cliente" name="tipo_cliente" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach ($tipoValues as $value)
                    <option value="{{ $value }}"
                        {{ old('tipo_cliente', isset($cliente) ? $cliente->tipo_cliente : '') == $value ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
        <input type="text" id="direccion" name="direccion"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese la dirección" value="{{ old('direccion', $cliente->direccion) }}" />
    </div>
    
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="ejecutivo_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Ejecutivo
        </label>
        <div class="input-group">
            <select id="ejecutivo_id" name="ejecutivo_id" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled
                    @if ($metodo == 'create' || old('ejecutivo_id', isset($cliente) ? $cliente->ejecutivo_id : '') == '') selected @endif>
                    -- EJECUTIVO --
                </option>
                @foreach ($ejecutivoValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('ejecutivo_id', isset($cliente) ? $cliente->ejecutivo_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->full_name) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="dias_credito" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Días de crédito
        </label>
        <input type="number" min="0" step="any" id="dias_credito" name="dias_credito"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Días de crédito"
            value="{{ old('dias_credito', $cliente->dias_credito ?? '0') }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12 menu">
        <label for="limite_credito" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Monto límite de crédito
        </label>
        <input type="number" min="0" step="0.01" id="limite_credito" name="limite_credito"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Monto límite de crédito"
            value="{{ old('limite_credito', $cliente->limite_credito ?? '0') }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="comentario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comentarios</label>
        <textarea id="comentario" name="comentario" rows="2"
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Comentarios">{{ old('comentario', $cliente->comentario) }}</textarea>
    </div>


    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR CLIENTE
        @elseif($metodo == 'edit')
            EDITAR CLIENTE
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            $('#tipo_cliente').select2({
                //selectOnClose: true
            });
            $('#ejecutivo_id').select2({
                placeholder: "-- EJECUTIVO --",
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

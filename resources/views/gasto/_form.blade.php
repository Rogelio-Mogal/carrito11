<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-5 md:col-span-5">
        <label for="gasto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gasto</label>
        <input type="text" id="gasto" name="gasto" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el gasto" value="{{ old('gasto', $gasto->gasto) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="tipo_gasto_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo gasto</label>
        <div class="input-group">
            <select id="tipo_gasto_id" name="tipo_gasto_id" style="height: 400px !important;" required
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- TIPO GASTO --</option>
                @foreach ($tipogasto as $value)
                    <option value="{{ $value->id }}"
                        {{ old('tipo_gasto_id', isset($gasto) ? $gasto->tipo_gasto_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->tipo_gasto) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="forma_pago" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            &nbsp;
        </label>
        <button type="submit"
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

            $('#tipo_gasto_id').select2({
                placeholder: "-- TIPO GASTO --",
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

            // AUMENTA-DECREMENTA INPUT
            $('#increment-button').on('click', function() {
                let currentValue = parseInt($('#cantidad_minima').val());
                if (!isNaN(currentValue) && currentValue < 999) {
                    $('#cantidad_minima').val(currentValue - 1);
                }
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

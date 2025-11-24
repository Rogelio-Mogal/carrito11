<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

    @php
        if (isset($famAtributo->id) && $famAtributo->atributos) {
            // EDIT
            $selected = old('atributo_id', $famAtributo->atributos->pluck('id')->toArray());
        } else {
            // CREATE
            $selected = old('atributo_id', []);
        }
    @endphp
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="familia_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subfamilia</label>
        <select name="familia_id" id="familia_id"
            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Seleccione una subfamilia</option>
            @foreach($familias as $familia)
                <option value="{{ $familia->id }}"
                    {{ old('familia_id', $famAtributo->id ?? '') == $familia->id ? 'selected' : '' }}>
                    {{ $familia->nombre }}
                </option>
            @endforeach
        </select>
    </div>


    @php
        // Para create: old() devuelve array vacío si no hay error
        // Para edit: carga los IDs de los atributos de la familia
        if (isset($famAtributo->atributos)) {
            // edit
            $selected = old('atributo_id', $famAtributo->atributos->pluck('id')->toArray());
        } else {
            // create
            $selected = old('atributo_id', []);
        }
    @endphp
    <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
        <label for="atributo_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Atributo</label>
        <select name="atributo_id[]" id="atributo_id" multiple
            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            @foreach($atributos as $atributo)
                <option value="{{ $atributo->id }}" {{ in_array($atributo->id, $selected) ? 'selected' : '' }}>
                    {{ $atributo->nombre }}
                </option>
            @endforeach
        </select>
    </div>


    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR FAMILIA ATRIBUTO
        @elseif($metodo == 'edit')
            EDITAR FAMILIA ATRIBUTO
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            //Select2
            $('#familia_id').select2({
                placeholder: "-- SubFamilia --",
                allowClear: true
            });

            $('#atributo_id').select2({
                placeholder: "-- Atributo --",
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

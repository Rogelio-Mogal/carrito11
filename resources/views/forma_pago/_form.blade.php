<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-9 md:col-span-9">
        <label for="forma_pago" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Forma de pago</label>
        <input type="text" id="forma_pago" name="forma_pago" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese forma de pago" value="{{ old('forma_pago', $formapago->forma_pago) }}" />
    </div>    
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="forma_pago" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            &nbsp;
        </label>
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR FORMA DE PAGO
        @elseif($metodo == 'edit')
            EDITAR FORMA DE PAGO
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
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

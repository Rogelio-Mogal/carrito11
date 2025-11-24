<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Tipo de movimiento
        </label>

        <div class="flex">
            <!-- Entrada -->
            <div class="flex items-center me-4">
                <input id="radio-entrada" type="radio" name="tipo" value="entrada"
                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 focus:ring-green-500 
                        dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 
                        dark:bg-gray-700 dark:border-gray-600"
                    {{ old('tipo', $caja->tipo ?? '') === 'entrada' ? 'checked' : '' }}>
                <label for="radio-entrada" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Entrada
                </label>
            </div>

            <!-- Salida -->
            <div class="flex items-center me-4">
                <input id="radio-salida" type="radio" name="tipo" value="salida"
                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 
                        dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 
                        dark:bg-gray-700 dark:border-gray-600"
                    {{ old('tipo', $caja->tipo ?? '') === 'salida' ? 'checked' : '' }}>
                <label for="radio-salida" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                    Salida
                </label>
            </div>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="monto" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Monto
        </label>
        <input type="number" min="0" step="0.01" id="monto" name="monto" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Monto" value="{{ old('monto', $caja->monto ?? '') }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="motivo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Describe el
            motivo</label>
        <textarea id="motivo" name="motivo" rows="2" required
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Describe el motivo">{{ old('descripcion', $caja->motivo) }}</textarea>
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR MOVIMIENTO CAJA
            @elseif($metodo == 'edit')
                EDITAR MOVIMIENTO CAJA
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
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
                e.preventDefault(); // evitar envío inmediato

                if (formSubmitting) return;

                const form = this;

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se registrará un movimiento de caja con estos datos.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        confirmButton: 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800',
                        cancelButton: 'text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 focus:outline-none dark:focus:ring-gray-600'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        formSubmitting = true;
                        form.submit();
                    }
                });
            });
        });
    </script>
@stop

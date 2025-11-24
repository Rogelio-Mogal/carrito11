<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">

    @if ($metodo === 'apertura')
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Apertura de Caja</h2>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="efectivo_inicial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Efectivo inicial
            </label>
            <input type="number" step="0.01" name="efectivo_inicial" id="efectivo_inicial"
                value="{{ old('efectivo_inicial') }}" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                       focus:ring-green-500 focus:border-green-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                       dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500" />
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                &nbsp;
            </label>
            <button type="submit"
                class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:outline-none 
                    focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Abrir Turno
            </button>
        </div>
    @else
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                Cierre de Caja (Turno {{ $caja->turno }})
            </h2>
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="efectivo_real" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Efectivo sistema
            </label>
            <p
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                {{ '$' . number_format($totalEfectivo, 2, '.', ',') }}
            </p>
        </div>
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label for="efectivo_real" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Efectivo real (conteo)
            </label>
            <input type="number" step="0.01" name="efectivo_real" id="efectivo_real"
                value="{{ old('efectivo_real') }}" required
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg 
                       focus:ring-red-500 focus:border-red-500 block w-full p-2.5
                       dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                       dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500" />
        </div>

        <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                &nbsp;
            </label>
            <button type="submit"
                class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none 
                    focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center 
                    dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                Cerrar Turno
            </button>
        </div>
    @endif

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

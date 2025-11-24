<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
        <input type="text" id="nombre" name="nombre"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese sucursal" value="{{ old('nombre', $sucursales->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="direccion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dirección</label>
        <input type="text" id="direccion" name="direccion"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese dirección" value="{{ old('direccion', $sucursales->direccion) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
        <label for="telefono" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teléfono</label>
        <input type="text" id="telefono" name="telefono"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese teléfono" value="{{ old('telefono', $sucursales->telefono) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4 flex items-center mt-6">
        <input 
            id="es_matriz" 
            type="checkbox" 
            name="es_matriz" 
            value="1"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded 
                focus:ring-blue-500 dark:focus:ring-blue-600 
                dark:ring-offset-gray-800 focus:ring-2 
                dark:bg-gray-700 dark:border-gray-600"
            {{ old('es_matriz', $sucursales->es_matriz ?? false) ? 'checked' : '' }}
            onchange="document.getElementById('esMatrizLabel').innerText = this.checked ? 'Sí' : 'No';"
        >
        <label for="es_matriz" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
            ¿Es matriz?
        </label>
        <span id="esMatrizLabel" class="ml-2 text-sm font-semibold text-blue-600 dark:text-blue-400">
            {{ old('es_matriz', $sucursales->es_matriz ?? false) ? 'Sí' : 'No' }}
        </span>
    </div>
    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR SUCURSAL
        @elseif($metodo == 'edit')
            EDITAR SUCURSAL
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

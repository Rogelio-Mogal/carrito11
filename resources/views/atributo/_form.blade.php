<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('nombre', $atributo->nombre) }}" />
    </div>

    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="tipo_campo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <select id="tipo_campo" name="tipo_campo"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 
                dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Seleccione un tipo</option>
            <option value="texto" {{ old('tipo_campo', $atributo->tipo_campo) == 'texto' ? 'selected' : '' }}>Texto</option>
            <option value="numero" {{ old('tipo_campo', $atributo->tipo_campo) == 'numero' ? 'selected' : '' }}>Número</option>
            <option value="select" {{ old('tipo_campo', $atributo->tipo_campo) == 'select' ? 'selected' : '' }}>Select</option>
            <option value="multiselect" {{ old('tipo_campo', $atributo->tipo_campo) == 'multiselect' ? 'selected' : '' }}>Multi Select</option>
        </select>
    </div>

    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="opciones" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Opciones</label>
        <input type="text" id="opciones" name="opciones"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ejemplo: USB 2.0, USB 3.0, USB 3.1"
            value="{{ old('opciones', $atributo->opciones ? implode(',', $atributo->opciones) : '') }}" />
    </div>
    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR ATRIBUTO
        @elseif($metodo == 'edit')
            EDITAR ATRIBUTO
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

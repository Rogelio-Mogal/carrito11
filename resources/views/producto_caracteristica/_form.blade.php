<x-validation-errors class="mb-4" />
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('nombre', $caracteristica->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="input-group">
            <select id="tipo" name="tipo" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach ($tipoValues as $value)
                    <option value="{{ $value }}"
                        {{ old('tipo', isset($caracteristica) ? $caracteristica->tipo : '') == $value ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="imagen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
        <label
            class=" text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Nueva imagen
                @elseif($metodo == 'edit')
                    Actualizar imagen
                @endif
            </span>
            <input type="file" accept="image/*" 
                name="imagen" id="imagen" 
                class="hidden"
                onchange="previewImage(event, '#imgPreview')">
        </label>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 relative object-cover object-center">
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full" 
                {{--src="{{ $caracteristica->iamgen }}"--}}
                src="{{ $metodo == 'edit' && $caracteristica->imagen ? asset('' . $caracteristica->imagen) : '#' }}"
                alt="" id="imgPreview">
        </figure>
    </div>

    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR CARACTERISTICA
            @elseif($metodo == 'edit')
                EDITAR CARACTERISTICA
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        // PREVISUALIZACION DE IMAGEN
        function previewImage(event, querySelector) {
            const input = event.target; //Recuperamos el input que desencadeno la acción
            $imgPreview = document.querySelector(querySelector); //Recuperamos la etiqueta img donde cargaremos la imagen
            if (!input.files.length) return // Verificamos si existe una imagen seleccionada
            file = input.files[0]; //Recuperamos el archivo subido
            objectURL = URL.createObjectURL(file); //Creamos la url
            $imgPreview.src = objectURL; //Modificamos el atributo src de la etiqueta img
        }
        $(document).ready(function() {
            $('#tipo').select2({
                selectOnClose: true
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

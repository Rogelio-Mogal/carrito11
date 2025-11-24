<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input 
            type="text" 
            id="name"
            name="name" 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Ingrese el nombre" value="{{ old('name', $perfil->name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellidos</label>
        <input type="text" id="last_name" name="last_name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese los apellidos" value="{{ old('last_name', $perfil->last_name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
        <input 
            type="email" 
            id="email"
            name="email" 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Ingrese el correo" value="{{ old('email', $perfil->email) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="printer_size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ticket</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio1" value="58" {{ ($perfil->printer_size == '58') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">58mm</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio2" value="80" {{ ($perfil->printer_size == '80') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">80mm</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="menu_color" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Color menú</label>
        <div class="input-group">
            <select id="menu_color" name="menu_color"
                class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="bg-indigo-400" {{ ($perfil->menu_color == 'bg-indigo-400') ? 'selected' : '' }}>Predeterminado</option>
                <option value="bg-red-400" {{ ($perfil->menu_color == 'bg-red-400') ? 'selected' : '' }}>Rojo</option>
                <option value="bg-yellow-400" {{ ($perfil->menu_color == 'bg-yellow-400') ? 'selected' : '' }}>Amarillo</option>
                <option value="bg-blue-400" {{ ($perfil->menu_color == 'bg-blue-400') ? 'selected' : '' }}>Azul</option>
                <option value="bg-pink-400" {{ ($perfil->menu_color == 'bg-pink-400') ? 'selected' : '' }}>Rosa</option>
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="theme" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tema</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="theme" id="inlineRadio1" value="light" {{ ($perfil->theme == 'light') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">Claro</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="theme" id="inlineRadio3" value="dark" {{ ($perfil->theme == 'dark') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio3">Oscuro</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
        <input 
            type="password" 
            id="password"
            name="password" 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar contraseña</label>
        <input 
            type="password" 
            id="password_confirmation"
            name="password_confirmation" 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            />
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR PERFIL
            @elseif($metodo == 'edit')
                EDITAR PERFIL
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            $('#menu_color').select2({
                templateResult: formatColor,
                templateSelection: formatColor
            });

            function formatColor(state) {
                if (!state.id) {
                    return state.text;
                }

                var colorCode = state.element.value;
                var $state = $(
                    '<span><span class="' + colorCode + ' w-3 h-3 inline-block mr-2 rounded-full"></span>' + state.text + '</span>'
                );

                return $state;
            }
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


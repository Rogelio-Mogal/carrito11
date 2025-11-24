<x-validation-errors class="mb-4" />
<input type="hidden" name="activa" id="activa" value="0">
<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="name" name="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el nombre" value="{{ old('name', $user->name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Apellidos</label>
        <input type="text" id="last_name" name="last_name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese los apellidos" value="{{ old('last_name', $user->last_name) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-4">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correo</label>
        <input type="email" id="email" name="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el correo" value="{{ old('email', $user->email) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="sucursal_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sucursal</label>
        <div class="input-group">
            <select id="sucursal_id" name="sucursal_id" style="height: 400px !important;" required
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- SUCURSAL --</option>
                @foreach ($sucursales as $value)
                    <option value="{{ $value->id }}"
                        {{ old('sucursal_id', isset($user) ? $user->sucursal_id : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="printer_size" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ticket</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio1" value="58" 
                    {{ ($user->printer_size == '58') ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">58mm</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="printer_size" id="inlineRadio2" value="80"
                    {{ (isset($user) && $user->printer_size == '80') || !isset($user->printer_size) || $user->printer_size == '' ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">80mm</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="es_reparador" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reparador</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_reparador" id="inlineRadio3" value="1" {{ ($user->es_reparador == '1') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio3">Si</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_reparador" id="inlineRadio4" value="0" 
                {{ (isset($user) && $user->es_reparador == '0') || !isset($user->es_reparador) || $user->es_reparador == '' ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio4">No</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
        <label for="es_externo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reparador externo</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_externo" id="inlineRadio5" value="1" {{ ($user->es_externo == '1') ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio5">Si</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="es_externo" id="inlineRadio6" value="0" 
                {{ (isset($user) && $user->es_externo == '0') || !isset($user->es_externo) || $user->es_externo == '' ? 'checked' : '' }}
                >
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio6">No</label>
            </div>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
    <input type="password" id="password" name="password"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-6">
        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar
            contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <ul>
            @foreach ($roles as $item)
                <li>
                    <label class="text-sm font-medium text-gray-800 dark:text-gray-200">
                        <x-checkbox name="roles[]" value="{{ $item->id }}" :checked="in_array($item->id, old('roles', $user->roles->pluck('id')->toArray()))" />
                        {{ $item->name }}
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
    
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR USUARIO
        @elseif($metodo == 'edit')
            EDITAR USUARIO
        @endif
    </button>
    </div>
</div>

@section('js')
    <script>
        $(document).ready(function() {
            //Select2
            $('#sucursal_id').select2({
                placeholder: "-- SUCURSAL --",
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

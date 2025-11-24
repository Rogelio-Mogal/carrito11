<x-validation-errors class="mb-4" />

<div class="mb-2">
    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre del rol</label>
    <input 
        type="text" 
        id="name"
        name="name" 
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
        placeholder="Ingrese el nombre del rol" value="{{ old('name', $role->name) }}" />
</div>
<div class="mb-4">
    <ul>
        @foreach ($permissions as $item)
            <li>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200">
                    <x-checkbox
                        name="permissions[]" 
                        value="{{$item->id}}"
                        :checked="in_array($item->id, old('permissions', $role->permissions->pluck('id')->toArray()))"
                         />
                        {{$item->name}}
                </label>
            </li>
        @endforeach
    </ul>

</div>
<div class="flex">
    {{--<x-button class="bg-blue-700 hover:bg-blue-800 text-white focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            Crear rol
        @elseif($metodo == 'edit')
            Editar rol
        @endif
    </x-button>--}}
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
        @if ($metodo == 'create')
            CREAR ROL
        @elseif($metodo == 'edit')
            EDITAR ROL
        @endif
    </button>
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


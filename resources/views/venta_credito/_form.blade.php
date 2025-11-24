<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Producto</label>
        <p 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
            {{$inventario->producto->nombre}}
        </p>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Marca</label>
        <p 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
            {{$inventario->producto->marca_c->nombre}}
        </p>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Familia</label>
        <p 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
            {{$inventario->producto->familia_c->nombre}}
        </p>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sub familia</label>
        <p 
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
        >
            {{$inventario->producto->subFamilia_c->nombre}}
        </p>
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="cantidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Tienda
        </label>
        <input type="number" min="0" step="1" id="cantidad" name="cantidad" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Tienda"
            value="{{ old('cantidad', $inventario->cantidad ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="producto_apartado" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Apartado
        </label>
        <input type="number" min="0" step="1" id="producto_apartado" name="producto_apartado" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Apartado"
            value="{{ old('producto_apartado', $inventario->producto_apartado ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="producto_servicio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Servicio
        </label>
        <input type="number" min="0" step="1" id="producto_servicio" name="producto_servicio" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Servicio"
            value="{{ old('producto_servicio', $inventario->producto_servicio ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="serie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Ajuste por inventario
        </label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ajuste_inventario" id="inlineRadio1" value="1"
                    {{ $inventario->ajuste_inventario == '1' ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">Si</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="ajuste_inventario" id="inlineRadio2" value="0"
                    {{ $inventario->ajuste_inventario == '0' || is_null($inventario->ajuste_inventario) ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">No</label>
            </div>
        </div>
    </div>

    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="precio_costo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Precio costo
        </label>
        <input type="number" min="0" step="0.01" id="precio_costo" name="precio_costo" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Servicio"
            value="{{ old('precio_costo', $inventario->precio_costo ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="precio_publico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Precio público
        </label>
        <input type="number" min="0" step="0.01" id="precio_publico" name="precio_publico" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Servicio"
            value="{{ old('precio_publico', $inventario->precio_publico ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="precio_medio_mayoreo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Precio medio mayoreo
        </label>
        <input type="number" min="0" step="0.01" id="precio_medio_mayoreo" name="precio_medio_mayoreo" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Servicio"
            value="{{ old('precio_medio_mayoreo', $inventario->precio_medio_mayoreo ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-3 md:col-span-3">
        <label for="precio_mayoreo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Precio mayoreo
        </label>
        <input type="number" min="0" step="0.01" id="precio_mayoreo" name="precio_mayoreo" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Servicio"
            value="{{ old('precio_mayoreo', $inventario->precio_mayoreo ?? '') }}" />
    </div>

    <br/>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Motivo del ajuste</label>
        <textarea id="descripcion" name="descripcion" rows="2" required
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Motivo del ajuste">{{ old('descripcion', $inventario->descripcion) }}</textarea>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR INVENTARIO
            @elseif($metodo == 'edit')
                AJUSTAR INVENTARIO
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

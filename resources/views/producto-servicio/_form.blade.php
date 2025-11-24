<x-validation-errors class="mb-4" />

<div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
    <div class="sm:col-span-12 lg:col-span-6 md:col-span-12">
        <label for="nombre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nombre</label>
        <input type="text" id="nombre" name="nombre" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Producto / Servicio" value="{{ old('nombre', $productoServicio->nombre) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="tipo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="input-group">
            <select id="tipo" name="tipo" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach ($tipoValues as $value)
                    <option value="{{ $value }}"
                        {{ old('tipo', isset($productoServicio) ? $productoServicio->tipo : '') == $value ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-12">
        <label for="codigo_barra" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Código de barra principal
        </label>
        <input type="text" id="codigo_barra" name="codigo_barra"  required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Ingrese el código de barra principal"
            value="{{ old('codigo_barra', $productoServicio->codigo_barra) }}" />
    </div>

    <!-- Códigos alternos -->
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Códigos de barra alternos
        </label>

        <!-- Contenedor en línea con flex -->
        <div id="codigosAlternosContainer" class="flex flex-wrap gap-2">
            @if(isset($productoServicio) && $productoServicio->codigosAlternos->count())
                @foreach($productoServicio->codigosAlternos as $alterno)
                    <div class="flex items-center gap-2 w-[32%] min-w-[120px]">
                        <input type="text" name="codigos_alternos[]" value="{{ $alterno->codigo_barra }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 
                                dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="Código alterno">
                        <button type="button" class="removeAlterno text-red-500 font-bold px-2">✕</button>
                    </div>
                @endforeach
            @else
                <div class="flex items-center gap-2 w-[32%] min-w-[120px]">
                    <input type="text" name="codigos_alternos[]" value=""
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Código alterno">
                    <button type="button" class="removeAlterno text-red-500 font-bold px-2">✕</button>
                </div>
            @endif
        </div>

        <button type="button" id="addAlterno"
            class="mt-2 text-sm text-blue-600 hover:underline dark:text-blue-400">
            + Agregar código alterno
        </button>

        <p id="alternoLimitMsg" class="hidden text-xs text-red-500 mt-1">
            Solo puedes agregar hasta 3 códigos alternos.
        </p>
    </div>



    <div id="campos-precios" class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2 sm:col-span-12 md:col-span-12 lg:col-span-12 {{ old('tipo', $productoServicio->tipo ?? '') === 'SERVICIO' ? '' : 'hidden' }}">
        <div  class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="precio_publico" class="block text-sm font-medium text-gray-900 dark:text-white">Precio Público</label>
            <input type="number" step="0.01" name="servicio[precio_publico]" id="precio_publico_servicio"
                value="{{ old('precio_publico', $productoServicio->precio_publico) }}"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div  class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="precio_medio_mayoreo" class="block text-sm font-medium text-gray-900 dark:text-white">Precio Medio Mayoreo</label>
            <input type="number" step="0.01" name="servicio[precio_medio_mayoreo]" id="precio_medio_mayoreo_servicio"
                value="{{ old('precio_medio_mayoreo', $productoServicio->precio_medio_mayoreo) }}"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div  class="sm:col-span-12 lg:col-span-4 md:col-span-4">
            <label for="precio_mayoreo" class="block text-sm font-medium text-gray-900 dark:text-white">Precio Mayoreo</label>
            <input type="number" step="0.01" name="servicio[precio_mayoreo]" id="precio_mayoreo_servicio"
                value="{{ old('precio_mayoreo', $productoServicio->precio_mayoreo) }}"
                class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
    </div>


    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="marca" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Marca</label>
        <div class="input-group">
            <select id="marca" name="marca" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- MARCA --</option>
                @foreach ($marcaValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('marca', isset($productoServicio) ? $productoServicio->marca : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
            <p id="marcaError" class="mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Seleccione una marca.</span></p> 
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="familia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Familia
        </label>
        <div class="input-group">
            <select id="familia" name="familia" style="height: 400px !important;" 
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled @if ($metodo == 'create') selected @endif>-- FAMILIA --</option>
                @foreach ($familiaValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('familia', isset($productoServicio) ? $productoServicio->familia : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
            <p id="familiaError" class="mt-2 text-xs text-red-600 dark:text-red-400 hidden"><span class="font-medium">Seleccione una familia.</span></p> 
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="sub_familia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Sub familia
        </label>
        <div class="input-group">
            <select id="sub_familia" name="sub_familia" style="height: 400px !important;"
                class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="" disabled
                    @if ($metodo == 'create' || old('sub_familia', isset($productoServicio) ? $productoServicio->sub_familia : '') == '') selected @endif>
                    -- SUB FAMILIA --
                </option>
                @foreach ($subfamiliaValues as $value)
                    <option value="{{ $value->id }}"
                        {{ old('sub_familia', isset($productoServicio) ? $productoServicio->sub_familia : '') == $value->id ? 'selected' : '' }}>
                        {{ ucfirst($value->nombre) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="cantidad_minima" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
            Cantidad mínima
        </label>
        <input type="number" min="1" step="any" id="cantidad_minima" name="cantidad_minima" required
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Cantidad mínima"
            value="{{ old('cantidad_minima', $productoServicio->cantidad_minima ?? '') }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="garantia" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Garantia</label>
        <input type="text" id="garantia" name="garantia"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Garantia" value="{{ old('garantia', $productoServicio->garantia) }}" />
    </div>
    <div class="sm:col-span-12 lg:col-span-2 md:col-span-12">
        <label for="serie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Requiere número
            de serie</label>
        <div class="input-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="serie" id="inlineRadio1" value="1"
                    {{ $productoServicio->serie == '1' ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio1">Si</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="serie" id="inlineRadio2" value="0"
                    {{ $productoServicio->serie == '0' || is_null($productoServicio->serie) ? 'checked' : '' }}>
                <label class="text-sm font-medium text-gray-800 dark:text-gray-200" for="inlineRadio2">No</label>
            </div>
        </div>
    </div>

  
    <div id="atributos-container" class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2 sm:col-span-12 md:col-span-12 lg:col-span-12">
    </div>

    @if ($metodo == 'create')
        <div class="sm:col-span-12 lg:col-span-12 md:col-span-12 flex justify-center items-center h-full mt-1">
            <label for="btn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">&nbsp;</label>
            <button type="button" id="btn" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Cargar inventario inicial
            </button>
            <input type="hidden" id="menuVisible" name="menuVisible" value="0">
        </div>
        
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-12 menu" style="display:none">
            <label for="cantidad" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Cantidad
            </label>
            <input type="number" min="1" step="1" id="cantidad" name="cantidad"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Cantidad"
                value="{{ old('cantidad', $productoServicio->cantidad) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-12 menu" style="display:none">
            <label for="precio_costo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio costo
            </label>
            <input type="number" min="0" step="0.01" id="precio_costo" name="precio_costo"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio costo"
                value="{{ old('precio_costo', $productoServicio->precio_costo) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-2 md:col-span-12 menu" style="display:none">
            <label for="precio_publico" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio público
            </label>
            <input type="number" min="0" step="0.01" id="precio_publico" name="producto[precio_publico]"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio público"
                value="{{ old('precio_publico', $productoServicio->precio_publico) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-3 md:col-span-12 menu" style="display:none">
            <label for="precio_medio_mayoreo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio medio mayoreo
            </label>
            <input type="number" min="0" step="0.01" id="precio_medio_mayoreo" name="producto[precio_medio_mayoreo]"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio medio mayoreo"
                value="{{ old('precio_medio_mayoreo', $productoServicio->precio_medio_mayoreo) }}" />
        </div>
        <div class="sm:col-span-12 lg:col-span-3 md:col-span-12 menu" style="display:none">
            <label for="precio_mayoreo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                Precio mayoreo
            </label>
            <input type="number" min="0" step="0.01" id="precio_mayoreo" name="producto[precio_mayoreo]"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Precio mayoreo"
                value="{{ old('precio_mayoreo', $productoServicio->precio_mayoreo) }}" />
        </div>
    @endif
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-12">
        <label
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Imagen 1
                @elseif($metodo == 'edit')
                    Actualizar imagen 1
                @endif
            </span>
            <input type="file" accept="image/*" name="imagen_1" id="imagen_1" class="hidden"
                onchange="previewImage(event, '#imgPreview1')">
        </label>
        <div id="fileError" class="text-red-600 hidden">Por favor, seleccione una imagen.</div>
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full"
                src="{{ $metodo == 'edit' && $productoServicio->image1 ? asset('' . $productoServicio->image1) : '#' }}"
                alt="" id="imgPreview1">
        </figure>
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-12">
        <div class="flex items-left justify-between">
        <label
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Imagen 2
                @elseif($metodo == 'edit')
                    Actualizar imagen 2
                @endif
            </span>
            <input type="file" accept="image/*" name="imagen_2" id="imagen_2" class="hidden"
                onchange="previewImage(event, '#imgPreview2', '#remove-image2')">
        </label>
        <button type="button" id="remove-image2" class="hidden items-left bg-red-500 text-white rounded-lg text-sm px-5 py-2.5 mt-2" onclick="removeImage('#imagen_2', '#imgPreview2', '#remove-image2')">
            Quitar
        </button>
        </div>
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full"
                src="{{ $metodo == 'edit' && $productoServicio->image2 ? asset('' . $productoServicio->image2) : '#' }}"
                alt="" id="imgPreview2">
        </figure>
    </div>
    <div class="sm:col-span-12 lg:col-span-4 md:col-span-12">
        <div class="flex items-left justify-between">
        <label
            class=" text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-black dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 cursor-pointer">
            <span>
                @if ($metodo == 'create')
                    Imagen 3
                @elseif($metodo == 'edit')
                    Actualizar imagen 3
                @endif
            </span>
            <input type="file" accept="image/*" name="imagen_3" id="imagen_3" class="hidden"
                onchange="previewImage(event, '#imgPreview3', '#remove-image3')">
        </label>
        <button type="button" id="remove-image3" class="hidden items-left bg-red-500 text-white rounded-lg text-sm px-5 py-2.5 mt-2" onclick="removeImage('#imagen_3', '#imgPreview3', '#remove-image3')">
            Quitar
        </button>
        </div>
        <figure class="flex justify-center items-center w-full h-full">
            <img class="object-cover object-center max-w-full max-h-full"
                src="{{ $metodo == 'edit' && $productoServicio->image3 ? asset('' . $productoServicio->image3) : '#' }}"
                alt="" id="imgPreview3">
        </figure>
    </div>
    <br/>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <label for="descripcion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descripción del producto</label>
        <textarea id="descripcion" name="descripcion" rows="2" required
            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
            placeholder="Descripción del producto">{{ old('descripcion', $productoServicio->descripcion) }}</textarea>
    </div>
    <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
        <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            @if ($metodo == 'create')
                CREAR PRODUCTO/SERVICIO
            @elseif($metodo == 'edit')
                EDITAR PRODUCTO/SERVICIO
            @endif
        </button>
    </div>
</div>

@section('js')
    <script>
        window.atributosValores = @json($atributosValores);

        // PREVISUALIZACION DE IMAGEN
        function previewImage(event, querySelector, btnSelector) {
            const input = event.target; //Recuperamos el input que desencadeno la acción
            $imgPreview = document.querySelector(querySelector); //Recuperamos la etiqueta img donde cargaremos la imagen
            const $removeButton = document.querySelector(btnSelector); //Recuperamos el botón de eliminar
            if (!input.files.length) return // Verificamos si existe una imagen seleccionada
            file = input.files[0]; //Recuperamos el archivo subido
            objectURL = URL.createObjectURL(file); //Creamos la url
            $imgPreview.src = objectURL; //Modificamos el atributo src de la etiqueta img
            $removeButton.classList.remove('hidden'); // Mostramos el botón de quitar
        }

        // FUNCION PARA REMOVER IMAGEN
        function removeImage(inputSelector, imgSelector, btnSelector) {
            const input = document.querySelector(inputSelector);
            const $imgPreview = document.querySelector(imgSelector);
            const $removeButton = document.querySelector(btnSelector);
            input.value = ''; // Limpiamos el valor del input de archivo
            $imgPreview.src = '#'; // Restablecemos la src de la vista previa
            $removeButton.classList.add('hidden'); // Ocultamos el botón de quitar
        }

        // GEBNERA LOS CAMPOS DINÁMICOS DE LOS ATRIBUTOS
        function cargarAtributos(familiaId, atributosValores = {}) {
            const contenedor = $('#atributos-container');
            contenedor.html('<p class="text-gray-500">Cargando atributos...</p>');

            fetch(`/familias/${familiaId}/atributos`)
                .then(res => res.json())
                .then(data => {
                    contenedor.html(''); // Limpiamos

                    data.forEach(atributo => {
                        const rowDiv = document.createElement('div');
                        rowDiv.className = "atributo-row sm:col-span-12 lg:col-span-4 md:col-span-4 mb-3";

                        const label = document.createElement('label');
                        label.className = "block mb-2 text-sm font-medium text-gray-900 dark:text-white";
                        label.textContent = atributo.nombre;
                        rowDiv.appendChild(label);

                        const multiple = atributo.tipo_campo === 'multiselect';
                        const nameValor = `atributos[${atributo.id}][valor]${multiple ? '[]' : ''}`;

                        // Valores previos
                        const valoresPrevios = atributosValores[atributo.id] || [];

                        let field;
                        if (atributo.tipo_campo === 'texto' || atributo.tipo_campo === 'numero') {
                            field = document.createElement('input');
                            field.type = atributo.tipo_campo === 'texto' ? 'text' : 'number';
                            field.name = nameValor;
                            field.value = valoresPrevios.length > 0 ? valoresPrevios[0] : '';
                        } else if (atributo.tipo_campo === 'booleano') {
                            field = document.createElement('select');
                            field.name = nameValor;
                            const optSi = document.createElement('option'); optSi.value = 1; optSi.textContent = 'Sí';
                            const optNo = document.createElement('option'); optNo.value = 0; optNo.textContent = 'No';
                            field.appendChild(optSi);
                            field.appendChild(optNo);
                            field.value = valoresPrevios.length > 0 ? valoresPrevios[0] : '';
                        } else if (atributo.tipo_campo === 'select' || atributo.tipo_campo === 'multiselect') {
                            field = document.createElement('select');
                            field.name = nameValor;
                            if (multiple) field.multiple = true;
                            let opciones = Array.isArray(atributo.opciones) ? atributo.opciones : JSON.parse(atributo.opciones);
                            opciones.forEach(op => {
                                const option = document.createElement('option');
                                option.value = op;
                                option.textContent = op;
                                if (valoresPrevios.includes(op)) option.selected = true;
                                field.appendChild(option);
                            });
                        }

                        field.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5";
                        rowDiv.appendChild(field);

                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = `atributos[${atributo.id}][atributo_id]`;
                        hidden.value = atributo.id;
                        rowDiv.appendChild(hidden);

                        contenedor.append(rowDiv);
                    });
                })
                .catch(err => console.error(err));
        }

        // BLOQUEAR INVENTARIO INICIAL SI ES SERVICIO
        function toggleInventarioPorTipo() {
            const tipo = $('#tipo').val();
            if (tipo === 'SERVICIO') {
                console.log('1');
                // Deshabilitar botón
                $("#btn").prop("disabled", true).addClass("opacity-50 cursor-not-allowed");
                // Ocultar inventario si estaba abierto
                $('.menu').hide();
                $("#menuVisible").val(0);
                $("#cantidad").val('');
                $("#precio_costo").val('');
                $("#precio_publico").val('');
                $("#precio_medio_mayoreo").val('');
                $("#precio_mayoreo").val('');
                // Quitar required
                $("#cantidad").removeAttr('required');
                $("#precio_costo").removeAttr('required');
                $("#precio_publico").removeAttr('required');
                $("#precio_medio_mayoreo").removeAttr('required');
                $("#precio_mayoreo").removeAttr('required');
            } else {
                // Si es producto habilitar
                console.log('2');
                $("#btn").prop("disabled", false).removeClass("opacity-50 cursor-not-allowed");
            }
        }

        //PRECIOS DE LOS SERVICIOS
        function toggleCamposPrecios() {
            const tipo = $('#tipo').val();
            if (tipo === "SERVICIO") {
                $('#campos-precios').removeClass("hidden");
            } else {
                $('#campos-precios').addClass("hidden");
            }
        }

        //CODIGOS DE BARRA ALTERNOS
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('codigosAlternosContainer');
            const addBtn = document.getElementById('addAlterno');
            const limitMsg = document.getElementById('alternoLimitMsg');
            const maxAlternos = 3;

            addBtn.addEventListener('click', () => {
                const count = container.querySelectorAll('input[name="codigos_alternos[]"]').length;

                if (count >= maxAlternos) {
                    limitMsg.classList.remove('hidden');
                    return;
                }

                const div = document.createElement('div');
                div.classList.add('flex', 'items-center', 'gap-2', 'w-1/3', 'min-w-[120px]');
                div.innerHTML = `
                    <input type="text" name="codigos_alternos[]" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        placeholder="Código alterno">
                    <button type="button" class="removeAlterno text-red-500 font-bold px-2">✕</button>
                `;
                container.appendChild(div);
                limitMsg.classList.add('hidden');
            });

            container.addEventListener('click', (e) => {
                if (e.target.classList.contains('removeAlterno')) {
                    e.target.closest('div').remove();
                    limitMsg.classList.add('hidden');
                }
            });
        });
        

        $(document).ready(function() {
            // Ejecutar al inicio
            toggleInventarioPorTipo();
            toggleCamposPrecios();
            

            //OBTENGO DINAMICAMENTE LOS ATRIBUTOS DE LA FAMILIA
            const familiaId = $('#sub_familia').val();
            if (familiaId) {
                cargarAtributos(familiaId, window.atributosValores || {});
            }

            $('#sub_familia').on('change', function() {
                const familiaId = $(this).val();
                cargarAtributos(familiaId, window.atributosValores || {});
            });

            /*$('#familia').on('change', function() {
                const familiaId = $(this).val();
                const contenedor = $('#atributos-container');
                contenedor.html('<p class="text-gray-500">Cargando atributos...</p>');

                fetch(`/familias/${familiaId}/atributos`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        data.forEach((atributo, i) => {
                            html += `
                                <div class="atributo-row sm:col-span-12 lg:col-span-4 md:col-span-4">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">${atributo.nombre}</label>`;
                                        if(atributo.tipo_campo === 'texto'){
                                            html += `<input type="text" name="atributos[${i}][valor]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">`;
                                        } else if(atributo.tipo_campo === 'numero'){
                                            html += `<input type="number" name="atributos[${i}][valor]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">`;
                                        } else if(atributo.tipo_campo === 'booleano'){
                                            html += `<select name="atributos[${i}][valor]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                                    <option value="1">Sí</option>
                                                    <option value="0">No</option>
                                                </select>`;
                                        } else if(atributo.tipo_campo === 'select' || atributo.tipo_campo === 'multiselect'){
                                            let opciones = Array.isArray(atributo.opciones) ? atributo.opciones : JSON.parse(atributo.opciones);
                                            const multiple = atributo.tipo_campo === 'multiselect' ? 'multiple' : '';
                                            html += `<select name="atributos[${i}][valor]${multiple ? '[]' : ''}" ${multiple} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">`;
                                            opciones.forEach(op => html += `<option value="${op}">${op}</option>`);
                                            html += `</select>`;
                                        }

                            html += `<input type="hidden" name="atributos[${i}][atributo_id]" value="${atributo.id}"></div>`;
                        });
                        contenedor.html(html);
                    })
                    .catch(err => console.error(err));
            });*/

            /*$('#familia').on('change', function() {
                const familiaId = $(this).val();
                const contenedor = $('#atributos-container');

                contenedor.html('<p class="text-gray-500">Cargando atributos...</p>');

                fetch(`/familias/${familiaId}/atributos`)
                    .then(res => res.json())
                    .then(data => {
                        contenedor.html(''); // Limpiamos

                        data.forEach(atributo => {
                            // Creamos el nombre dinámico usando atributo_id
                            const multiple = atributo.tipo_campo === 'multiselect' ? 'multiple' : '';
                            const nameValor = `atributos[${atributo.id}][valor]${multiple ? '[]' : ''}`;

                            let html = `
                                <div class="atributo-row sm:col-span-12 lg:col-span-4 md:col-span-4 mb-3">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">${atributo.nombre}</label>
                            `;

                            if (atributo.tipo_campo === 'texto') {
                                html += `<input type="text" name="${nameValor}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">`;
                            } else if (atributo.tipo_campo === 'numero') {
                                html += `<input type="number" name="${nameValor}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">`;
                            } else if (atributo.tipo_campo === 'booleano') {
                                html += `<select name="${nameValor}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                            <option value="1">Sí</option>
                                            <option value="0">No</option>
                                        </select>`;
                            } else if (atributo.tipo_campo === 'select' || atributo.tipo_campo === 'multiselect') {
                                let opciones = Array.isArray(atributo.opciones) ? atributo.opciones : JSON.parse(atributo.opciones);
                                html += `<select name="${nameValor}" ${multiple} class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">`;
                                opciones.forEach(op => html += `<option value="${op}">${op}</option>`);
                                html += `</select>`;
                            }

                            // Siempre enviamos atributo_id
                            html += `<input type="hidden" name="atributos[${atributo.id}][atributo_id]" value="${atributo.id}">`;
                            html += `</div>`;

                            contenedor.append(html);
                        });
                    })
                    .catch(err => console.error(err));
            });*/

            /*$('#familia').on('change', function() {
                const familiaId = $(this).val();
                const contenedor = $('#atributos-container');

                contenedor.html('<p class="text-gray-500">Cargando atributos...</p>');

                fetch(`/familias/${familiaId}/atributos`)
                    .then(res => res.json())
                    .then(data => {
                        contenedor.html(''); // Limpiamos

                        data.forEach(atributo => {
                            // Creamos un contenedor para cada atributo
                            const rowDiv = document.createElement('div');
                            rowDiv.className = "atributo-row sm:col-span-12 lg:col-span-4 md:col-span-4 mb-3";

                            // Label
                            const label = document.createElement('label');
                            label.className = "block mb-2 text-sm font-medium text-gray-900 dark:text-white";
                            label.textContent = atributo.nombre;
                            rowDiv.appendChild(label);

                            // Nombre dinámico para input/select
                            const multiple = atributo.tipo_campo === 'multiselect';
                            const nameValor = `atributos[${atributo.id}][valor]${multiple ? '[]' : ''}`;

                            if (atributo.tipo_campo === 'texto' || atributo.tipo_campo === 'numero') {
                                const input = document.createElement('input');
                                input.type = atributo.tipo_campo === 'texto' ? 'text' : 'number';
                                input.name = nameValor;
                                input.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5";
                                rowDiv.appendChild(input);
                            } else if (atributo.tipo_campo === 'booleano') {
                                const select = document.createElement('select');
                                select.name = nameValor;
                                select.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5";
                                const optSi = document.createElement('option'); optSi.value = 1; optSi.textContent = 'Sí';
                                const optNo = document.createElement('option'); optNo.value = 0; optNo.textContent = 'No';
                                select.appendChild(optSi);
                                select.appendChild(optNo);
                                rowDiv.appendChild(select);
                            } else if (atributo.tipo_campo === 'select' || atributo.tipo_campo === 'multiselect') {
                                const select = document.createElement('select');
                                select.name = nameValor;
                                select.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5";
                                if (multiple) select.multiple = true;

                                let opciones = Array.isArray(atributo.opciones) ? atributo.opciones : JSON.parse(atributo.opciones);
                                opciones.forEach(op => {
                                    const option = document.createElement('option');
                                    option.value = op;
                                    option.textContent = op;
                                    select.appendChild(option);
                                });

                                rowDiv.appendChild(select);
                            }

                            // Siempre enviamos atributo_id
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = `atributos[${atributo.id}][atributo_id]`;
                            hidden.value = atributo.id;
                            rowDiv.appendChild(hidden);

                            contenedor.append(rowDiv);
                        });
                    })
                    .catch(err => console.error(err));
            });*/

            /*$('#familia').on('change', function() {
                const familiaId = $(this).val();
                const contenedor = $('#atributos-container');

                contenedor.html('<p class="text-gray-500">Cargando atributos...</p>');

                fetch(`/familias/${familiaId}/atributos`)
                    .then(res => res.json())
                    .then(data => {
                        contenedor.html(''); // Limpiamos

                        data.forEach(atributo => {
                            // Contenedor de cada atributo
                            const rowDiv = document.createElement('div');
                            rowDiv.className = "atributo-row sm:col-span-12 lg:col-span-4 md:col-span-4 mb-3";

                            // Label
                            const label = document.createElement('label');
                            label.className = "block mb-2 text-sm font-medium text-gray-900 dark:text-white";
                            label.textContent = atributo.nombre;
                            rowDiv.appendChild(label);

                            // Nombre dinámico
                            const multiple = atributo.tipo_campo === 'multiselect';
                            const nameValor = `atributos[${atributo.id}][valor]${multiple ? '[]' : ''}`;

                            // Valores previos
                            const valoresPrevios = window.atributosValores ? window.atributosValores[atributo.id] || [] : [];

                            // Input o Select según tipo
                            let field;
                            if (atributo.tipo_campo === 'texto' || atributo.tipo_campo === 'numero') {
                                field = document.createElement('input');
                                field.type = atributo.tipo_campo === 'texto' ? 'text' : 'number';
                                field.name = nameValor;
                                field.value = valoresPrevios.length > 0 ? valoresPrevios[0] : '';
                            } else if (atributo.tipo_campo === 'booleano') {
                                field = document.createElement('select');
                                field.name = nameValor;
                                const optSi = document.createElement('option'); optSi.value = 1; optSi.textContent = 'Sí';
                                const optNo = document.createElement('option'); optNo.value = 0; optNo.textContent = 'No';
                                field.appendChild(optSi);
                                field.appendChild(optNo);
                                field.value = valoresPrevios.length > 0 ? valoresPrevios[0] : '';
                            } else if (atributo.tipo_campo === 'select' || atributo.tipo_campo === 'multiselect') {
                                field = document.createElement('select');
                                field.name = nameValor;
                                if (multiple) field.multiple = true;
                                let opciones = Array.isArray(atributo.opciones) ? atributo.opciones : JSON.parse(atributo.opciones);
                                opciones.forEach(op => {
                                    const option = document.createElement('option');
                                    option.value = op;
                                    option.textContent = op;
                                    if (valoresPrevios.includes(op)) option.selected = true;
                                    field.appendChild(option);
                                });
                            }

                            field.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5";
                            rowDiv.appendChild(field);

                            // Hidden atributo_id
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = `atributos[${atributo.id}][atributo_id]`;
                            hidden.value = atributo.id;
                            rowDiv.appendChild(hidden);

                            contenedor.append(rowDiv);
                        });
                    })
                    .catch(err => console.error(err));
            });*/


            // ACTIVA LA BUSQUEDA
            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                $(this).one('mouseup keyup', () => {
                    setTimeout(() => {
                        allFound[allFound.length - 1].focus();
                    }, 0);
                });
            });

            $('#tipo').select2({
                //selectOnClose: true
            });
            $('#marca').select2({
                placeholder: "-- MARCA --",
                allowClear: true
            });
            $('#familia').select2({
                placeholder: "-- FAMILIA --",
                allowClear: true
            });
            $('#sub_familia').select2({
                placeholder: "-- SUB FAMILIA --",
                allowClear: true
            });

            // Ejecutar al cambiar el tipo
            $('#tipo').on('change', function() {
                toggleInventarioPorTipo();
                toggleCamposPrecios();
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

            // AUMENTA-DECREMENTA INPUT
            $('#increment-button').on('click', function() {
                let currentValue = parseInt($('#cantidad_minima').val());
                if (!isNaN(currentValue) && currentValue < 999) {
                    $('#cantidad_minima').val(currentValue - 1);
                }
            });

            $('#decrement-button').on('click', function() {
                let currentValue = parseInt($('#cantidad_minima').val());
                if (!isNaN(currentValue) && currentValue > 1) {
                    $('#cantidad_minima').val(currentValue + 1);
                }
            });

            $('#increment-button2').on('click', function() {
                let currentValue = parseInt($('#cantidad').val());
                if (!isNaN(currentValue) && currentValue < 999) {
                    $('#cantidad').val(currentValue - 1);
                }
            });

            $('#decrement-button2').on('click', function() {
                let currentValue = parseInt($('#cantidad').val());
                if (!isNaN(currentValue) && currentValue > 1) {
                    $('#cantidad').val(currentValue + 1);
                }
            });

            // MUESTRA INVENTARIO INICIAL
            /*$("#btn").click(function() {
                var $menu = $('.menu');
                if ($menu.is(':visible')) {
                    $menu.hide();
                    $("#menuVisible").val(0);
                    $("#cantidad").val('');
                    $("#precio_costo").val('');
                    $("#precio_publico").val('');
                    $("#precio_medio_mayoreo").val('');
                    $("#precio_mayoreo").val('');
                    // Quito los required
                    $("#cantidad").removeAttr('required');
                    $("#precio_costo").removeAttr('required');
                    $("#precio_publico").removeAttr('required');
                    $("#precio_medio_mayoreo").removeAttr('required');
                    $("#precio_mayoreo").removeAttr('required');
                } else {
                    $menu.show();
                    $("#menuVisible").val(1);
                    $("#cantidad").val(1);
                    // Agrego required
                    $("#cantidad").attr('required', 'required');
                    $("#precio_costo").attr('required', 'required');
                    $("#precio_publico").attr('required', 'required');
                    $("#precio_medio_mayoreo").attr('required', 'required');
                    $("#precio_mayoreo").attr('required', 'required');
                }
            });*/

            $("#btn").click(function() {
                // 🚫 Bloquear si es Servicio
                console.log($("#tipo").val());
                if ($("#tipo").val() === "SERVICIO") {
                    return; // no hace nada
                }

                var $menu = $('.menu');
                if ($menu.is(':visible')) {
                    $menu.hide();
                    $("#menuVisible").val(0);
                    $("#cantidad").val('');
                    $("#precio_costo").val('');
                    $("#precio_publico").val('');
                    $("#precio_medio_mayoreo").val('');
                    $("#precio_mayoreo").val('');
                    // Quito los required
                    $("#cantidad").removeAttr('required');
                    $("#precio_costo").removeAttr('required');
                    $("#precio_publico").removeAttr('required');
                    $("#precio_medio_mayoreo").removeAttr('required');
                    $("#precio_mayoreo").removeAttr('required');
                } else {
                    $menu.show();
                    $("#menuVisible").val(1);
                    $("#cantidad").val(1);
                    // Agrego required
                    $("#cantidad").attr('required', 'required');
                    $("#precio_costo").attr('required', 'required');
                    $("#precio_publico").attr('required', 'required');
                    $("#precio_medio_mayoreo").attr('required', 'required');
                    $("#precio_mayoreo").attr('required', 'required');
                }
            });

            // VALIDACION DEL CAMPO FILA (IMAGEN 1)
            $('form').on('submit', function(e) {
                //e.preventDefault();
                var fileInput = $('#imagen_1');
                var fileError = $('#fileError');
                var marcaError = $('#marcaError');
                var familiaError = $('#familiaError');
                var isValid = true;

                /*if (fileInput.get(0).files.length === 0) {
                    e.preventDefault(); // Detener el envío del formulario
                    fileError.removeClass('hidden');
                    fileInput.focus(); // Dar foco al input para que se pueda seleccionar un archivo
                    isValid = false;
                } else {
                    fileError.addClass('hidden');
                }*/

                
                // validacion de select MARCA
                if ($('#marca').val() === null || $('#marca').val() === "") {
                    marcaError.removeClass('hidden');
                    isValid = false;
                } else {
                    marcaError.addClass('hidden');
                }

                // validacion de select FAMILIA
                if ($('#familia').val() === null || $('#familia').val() === "") {
                    familiaError.removeClass('hidden');
                    isValid = false;
                } else {
                    familiaError.addClass('hidden');
                }

                if (!isValid) {
                    e.preventDefault();  // Prevent form submission if validation fails
                }

            });

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

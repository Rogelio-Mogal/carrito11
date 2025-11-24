<div id="nuevo-cliente-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
        <h3 class="text-lg font-bold mb-4">Registrar nuevo cliente</h3>
        <form id="form-nuevo-cliente">
            @csrf
            <div class="mb-3">
                <label for="name_modal" class="block text-sm font-medium">Nombre</label>
                <input type="text" id="name_modal" name="name" required
                    class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-3">
                <label for="last_name_modal" class="block text-sm font-medium">Apellidos</label>
                <input type="text" id="last_name_modal" name="last_name" required
                    class="w-full border rounded-lg p-2">
            </div>
            <div class="mb-3">
                <label for="telefono_modal" class="block text-sm font-medium">Teléfono</label>
                <input type="text" id="telefono_modal" name="telefono" required
                    class="w-full border rounded-lg p-2">
            </div>

            <!-- Tipo de cliente -->
            <div class="mb-3">
                <label for="tipo_cliente_modal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Tipo de cliente
                </label>
                <select id="tipo_cliente_modal" name="tipo_cliente"
                    class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($tipoValues as $value)
                        <option value="{{ $value }}">
                            {{ ucfirst($value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Ejecutivo -->
            <div class="mb-3">
                <label for="ejecutivo_id_modal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Ejecutivo
                </label>
                <select id="ejecutivo_id_modal" name="ejecutivo_id"
                    class="select2 block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="" selected disabled>-- EJECUTIVO --</option>
                    @foreach ($ejecutivoValues as $value)
                        <option value="{{ $value->id }}">
                            {{ ucfirst($value->full_name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" data-modal-hide="nuevo-cliente-modal"
                    class="px-4 py-2 bg-gray-400 text-white rounded-lg">Cancelar</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg">Guardar</button>
            </div>
        </form>
    </div>
</div>


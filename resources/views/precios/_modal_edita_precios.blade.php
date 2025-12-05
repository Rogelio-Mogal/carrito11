<div id="edit-modal" style="display: none;"
    class="modal fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50 dark:bg-gray-800">

    <div class="relative w-full max-w-lg bg-white rounded-lg shadow-lg dark:bg-gray-800">
        <form method="POST" id="form-edit">
            @csrf
            @method('PUT')

            <input type="hidden" name="tipo_precio" id="tipo_precio">

            <div class="flex items-center justify-between p-4 border-b dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Editar Registro
                    <span id="desde"></span>
                    Hasta:
                    <span id="hasta"></span>
                </h3>

                <button type="button"
                    class="close-modal text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 dark:hover:bg-gray-600">
                    ✕
                </button>
            </div>

            <div class="p-4 md:p-5">
                <div class="grid lg:grid-cols-12 gap-2" id="contenedorCampos">
                    <!-- Aquí JS insertará los inputs -->
                </div>
            </div>

            <div class="p-4 border-t flex justify-end">
                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

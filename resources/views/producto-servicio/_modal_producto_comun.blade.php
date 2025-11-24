<!-- Main modal -->
<div id="producto-comun-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-4xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 dark:bg-gray-800">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    PRODUCTO EN COMÚN
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="producto-comun-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4 dark:bg-gray-800">
                <div class="grid lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-2">
                    <!-- Form Fields -->
                    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                        <label for="modal_cantidad"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Cantidad
                        </label>
                        <input type="number" min="1" step="1" id="modal_cantidad" name="modal_cantidad"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Cantidad" value="" />
                    </div>
                    <div class="sm:col-span-12 lg:col-span-2 md:col-span-2">
                        <label for="modal_precio"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Precio
                        </label>
                        <input type="number" id="modal_precio" name="modal_precio" min="0" step="0.01"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Precio" value="" />
                    </div>
                    <div class="sm:col-span-12 lg:col-span-8 md:col-span-8">
                        <label for="modal_producto_comun"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Producto en común
                        </label>
                        <input type="text" id="modal_producto_comun" name="modal_producto_comun"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Producto en común" value="" />
                    </div>
                </div>
            </div>
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600 dark:bg-gray-800">
                <button type="button" id="btnComun"
                    class="btn-submit text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Guardar</button>
                <button type="button"
                    class="close-modal py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800">Cancelar</button>
            </div>
        </div>
    </div>
</div>

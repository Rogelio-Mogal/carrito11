<!-- Main modal -->
<div id="nota-credito-modal" tabindex="-1" aria-hidden="true"
    class="nota-credito-modal hidden fixed inset-0 z-50 flex justify-center items-center w-full overflow-y-auto overflow-x-hidden bg-black bg-opacity-50">
    <div class="relative w-full max-w-4xl max-h-full mx-auto my-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 dark:bg-gray-800">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    LISTADO DE NOTAS DE CRÉDITO
                </h3>
                <button type="button"
                    class="close-modal text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="nota-credito-modal">
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
                <table id="tablaNotas" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Monto</th>
                            <th>Aplicar</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

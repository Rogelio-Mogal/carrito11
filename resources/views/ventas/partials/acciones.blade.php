<a href="{{ route('ticket.venta', ['id' => $item->id]) }}" target="_blank"
    data-popover-target="ticket-venta{{ $item->id }}" data-popover-placement="bottom"
    class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
    <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
        height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z" />
    </svg>
    <span class="sr-only">Ticket de venta</span>
</a>
<div id="ticket-venta{{ $item->id }}" role="tooltip"
    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
    <div class="p-2 space-y-2">
        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket de venta</h6>
    </div>
</div>

@if ($item->activo == 1)

    <!-- Botón para pasar a venta (solo para pedidos finalizados) -->
    @if($item->tipo == 'pedido' && $item->estatus == 'Finalizado')
        <a href="{{ route('admin.ventas.create', ['referencia_cliente' => $item->referencia_cliente]) }}"
            data-id="{{ $item->id }}" data-popover-target="pasar-venta{{ $item->id }}" data-popover-placement="bottom"
            class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
            </svg>
            <span class="sr-only">Pasar a venta</span>
        </a>
        <div id="pasar-venta{{ $item->id }}" role="tooltip"
            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
            <div class="p-2 space-y-2">
                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Pasar a venta</h6>
            </div>
        </div>
    @endif

    <!-- Botón para ticket (solo para ventas) -->
    @if($item->tipo == 'venta')
        <a href="{{ route('ticket.venta', ['id' => $item->id]) }}" target="_blank"
            data-popover-target="ticket-venta{{ $item->id }}" data-popover-placement="bottom"
            class="mb-1 text-white bg-green-600 hover:bg-green-700 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center">
            <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 8h6m-6 4h6m-6 4h6M6 3v18l2-2 2 2 2-2 2 2 2-2 2 2V3l-2 2-2-2-2 2-2-2-2 2-2-2Z" />
            </svg>
            <span class="sr-only">Ticket de venta</span>
        </a>
        <div id="ticket-venta{{ $item->id }}" role="tooltip"
            class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
            <div class="p-2 space-y-2">
                <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Ticket de venta</h6>
            </div>
        </div>
    @endif
    <a href="{{ route('admin.ventas.show', $item->id) }}" data-id="{{ $item->id }}"
        data-popover-target="cancela{{ $item->id }}" data-popover-placement="bottom"
        class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="sr-only">Cancela venta</span>
    </a>
    <div id="cancela{{ $item->id }}" role="tooltip"
        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        <div class="p-2 space-y-2">
            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cancela venta</h6>
        </div>
    </div>
@else
    <a href="#" data-popover-target="eliminado{{ $item->id }}" data-popover-placement="bottom" data-id="{{ $item->id }}"
        class="mb-1 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
        <svg class="w-5 h-5 text-gray-100 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>

        <span class="sr-only">Activar</span>
    </a>
    <div id="eliminado{{ $item->id }}" role="tooltip"
        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        <div class="p-2 space-y-2">
            <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminado</h6>
        </div>
    </div>
@endif

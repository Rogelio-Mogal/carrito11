<a href="{{ route('admin.cotizacion.show', $item->id) }}" data-popover-target="detalles{{ $item->id }}"
    data-popover-placement="bottom"
    class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-width="2"
            d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
        <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
    </svg>
    <span class="sr-only">Detalles</span>
</a>
@if ($item->estado == 'CREADO' && $item->activo == 1)
    <a href="#" data-popover-target="listo{{ $item->id }}" data-popover-placement="bottom" data-id="{{ $item->id }}"
        class="listo-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 11.917 9.724 16.5 19 7.5" />
        </svg>
        <span class="sr-only">Listo</span>
    </a>

    <a href="{{ route('admin.cotizacion.edit', $item->id) }}" data-popover-target="editar{{ $item->id }}"
        data-popover-placement="bottom"
        class="text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
        </svg>
        <span class="sr-only">Editar</span>
    </a>
@endif
<a href="{{ route('admin.cotizacion.destroy', $item->id) }}" data-popover-target="eliminar{{ $item->id }}"
    data-popover-placement="bottom" data-id="{{ $item->id }}"
    class="delete-item mb-1  text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
        viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
    <span class="sr-only">Eliminar</span>
</a>

<div id="listo{{ $item->id }}" role="tooltip"
    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
    <div class="p-2 space-y-2">
        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Cotización lista</h6>
    </div>
</div>
<div id="detalles{{ $item->id }}" role="tooltip"
    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
    <div class="p-2 space-y-2">
        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Detalles</h6>
    </div>
</div>
<div id="editar{{ $item->id }}" role="tooltip"
    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
    <div class="p-2 space-y-2">
        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Editar</h6>
    </div>
</div>
<div id="eliminar{{ $item->id }}" role="tooltip"
    class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
    <div class="p-2 space-y-2">
        <h6 class="font-semibold mb-0 text-gray-900 dark:text-black">Eliminar</h6>
    </div>
</div>

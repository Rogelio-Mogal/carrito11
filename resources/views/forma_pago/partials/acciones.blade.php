@if ($item->activo == 1)
    <a href="#" data-id="{{ $item->id }}" data-popover-target="editar{{ $item->id }}" data-popover-placement="left"
        class="open-modal edit-item text-white mb-1 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
        </svg>
        <span class="sr-only">Editar</span>
    </a>
    <a href="{{ route('admin.forma.pago.destroy', $item->id) }}" data-popover-target="eliminar{{ $item->id }}"
        data-popover-placement="left" data-id="{{ $item->id }}"
        class="delete-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="sr-only">Eliminar</span>
    </a>
    <div id="editar{{ $item->id }}" role="tooltip"
        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        <div class="p-2 space-y-2">
            <h6 class="font-semibold text-gray-900 dark:text-black">&nbsp; Editar</h6>
        </div>
        <div data-popper-arrow></div>
    </div>
    <div id="eliminar{{ $item->id }}" role="tooltip"
        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        <div class="p-2 space-y-2">
            <h6 class="font-semibold text-gray-900 dark:text-black">&nbsp; Eliminar</h6>
        </div>
        <div data-popper-arrow></div>
    </div>
@else
    <a href="{{ route('admin.forma.pago.edit', $item->id) }}" data-popover-target="activar{{ $item->id }}"
        data-popover-placement="left" data-id="{{ $item->id }}"
        class="activa-item mb-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2.5 text-center inline-flex items-center me-0 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
            viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3" />
        </svg>
        <span class="sr-only">Activar</span>
    </a>
    <div id="activar{{ $item->id }}" role="tooltip"
        class="absolute z-10 invisible inline-block w-54 text-sm font-light text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
        <div class="p-2 space-y-2">
            <h6 class="font-semibold text-gray-900 dark:text-black">&nbsp; Cambiar a activo</h6>
        </div>
        <div data-popper-arrow></div>
    </div>
@endif

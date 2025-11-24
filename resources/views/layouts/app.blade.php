@include('layouts.header')
<!--<div class="container">-->
{{-- <main>
        @yield('content')
    </main> --}}
<!-- </div>-->

<div class="flex-1 min-h-screen bg-indigo dark:bg-gray-800 text-black dark:text-white">
    <div class="p-3 sm:ml-64 dark:bg-gray-900 text-black dark:text-white">
        <div class="-mt-1 flex flex-wrap justify-between items-center">
            @include('layouts._breadcrumb')
            @yield('action')
        </div>
        @yield('content')
    </div>
</div>

@include('layouts.footer')

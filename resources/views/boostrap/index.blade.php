<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    <!--datatables -->
    <link rel="stylesheet" href="{{ asset('datatable/css/datatables.min.css') }}">

    <!--select2 -->
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">

    <!-- Flowbite -->
    <link rel="stylesheet" href="{{ mix('node_modules/flowbite/dist/flowbite.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
    rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: { "50": "#eff6ff", "100": "#dbeafe", "200": "#bfdbfe", "300": "#93c5fd", "400": "#60a5fa", "500": "#3b82f6", "600": "#2563eb", "700": "#1d4ed8", "800": "#1e40af", "900": "#1e3a8a", "950": "#172554" }
                }
            },
            fontFamily: {
                "body": ["Inter", "ui-sans-serif", "system-ui", "-apple-system", "system-ui", "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "Noto Sans", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
                "sans": ["Inter", "ui-sans-serif", "system-ui", "-apple-system", "system-ui", "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "Noto Sans", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"]
            }
        }
    }
</script>

<link rel="stylesheet"
    href="https://d2s3ptptdai04x.cloudfront.net/get-flowbite-pro.7e3d4ee4-5b87-4f3a-83c5-d6467ceddb41.css?Expires=2028450724&amp;Policy=eyJTdGF0ZW1lbnQiOlt7IlJlc291cmNlIjoiaHR0cHM6Ly9kMnMzcHRwdGRhaTA0eC5jbG91ZGZyb250Lm5ldC9nZXQtZmxvd2JpdGUtcHJvLjdlM2Q0ZWU0LTViODctNGYzYS04M2M1LWQ2NDY3Y2VkZGI0MS5jc3MiLCJDb25kaXRpb24iOnsiRGF0ZUxlc3NUaGFuIjp7IkFXUzpFcG9jaFRpbWUiOjIwMjg0NTA3MjR9fX1dfQ__&amp;Signature=2RB6zMT5gosVhFidiKo4~aIZSJEyJHrg1-RojPr-gGU2qkknkvBIp3xCoyfQZ8QZpDVUyF4srvMQzvHS0M5116BNfe4utvOWxyUwBvlAQRMGt8LRk1dQPqeNruce9SB4GU-0yVh-T5cVUFKt5PWEsBb~Xu3GGgpxqF0aV5gUA7fnFRHaDZr78p0YbYluAH8LZTWKj21DRCcGb0wk~-53t0I3U14tR5uPgdSbHaxK-ug0EHOkKWfEf2rRoihNPyMtPyPA9lMS44oEirbVWTmT-DjLroxKGfocX6lxEMp12-6KNsuM5Q3Aqzzt1Q-vAz8lTeBLwmmgEUXNcByICQqPxA__&amp;Key-Pair-Id=K2J5W1U51CTOXP">
<style>
    /* ! tailwindcss v3.4.4 | MIT License | https://tailwindcss.com */
    *,
    ::after,
    ::before {
        box-sizing: border-box;
        border-width: 0;
        border-style: solid;
        border-color: #e5e7eb
    }

    ::after,
    ::before {
        --tw-content: ''
    }

    :host,
    html {
        line-height: 1.5;
        -webkit-text-size-adjust: 100%;
        -moz-tab-size: 4;
        tab-size: 4;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, system-ui, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
        font-feature-settings: normal;
        font-variation-settings: normal;
        -webkit-tap-highlight-color: transparent
    }

    body {
        margin: 0;
        line-height: inherit
    }

    hr {
        height: 0;
        color: inherit;
        border-top-width: 1px
    }

    abbr:where([title]) {
        -webkit-text-decoration: underline dotted;
        text-decoration: underline dotted
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-size: inherit;
        font-weight: inherit
    }

    a {
        color: inherit;
        text-decoration: inherit
    }

    b,
    strong {
        font-weight: bolder
    }

    code,
    kbd,
    pre,
    samp {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        font-feature-settings: normal;
        font-variation-settings: normal;
        font-size: 1em
    }

    small {
        font-size: 80%
    }

    sub,
    sup {
        font-size: 75%;
        line-height: 0;
        position: relative;
        vertical-align: baseline
    }

    sub {
        bottom: -.25em
    }

    sup {
        top: -.5em
    }

    table {
        text-indent: 0;
        border-color: inherit;
        border-collapse: collapse
    }

    button,
    input,
    optgroup,
    select,
    textarea {
        font-family: inherit;
        font-feature-settings: inherit;
        font-variation-settings: inherit;
        font-size: 100%;
        font-weight: inherit;
        line-height: inherit;
        letter-spacing: inherit;
        color: inherit;
        margin: 0;
        padding: 0
    }

    button,
    select {
        text-transform: none
    }

    button,
    input:where([type=button]),
    input:where([type=reset]),
    input:where([type=submit]) {
        -webkit-appearance: button;
        background-color: transparent;
        background-image: none
    }

    :-moz-focusring {
        outline: auto
    }

    :-moz-ui-invalid {
        box-shadow: none
    }

    progress {
        vertical-align: baseline
    }

    ::-webkit-inner-spin-button,
    ::-webkit-outer-spin-button {
        height: auto
    }

    [type=search] {
        -webkit-appearance: textfield;
        outline-offset: -2px
    }

    ::-webkit-search-decoration {
        -webkit-appearance: none
    }

    ::-webkit-file-upload-button {
        -webkit-appearance: button;
        font: inherit
    }

    summary {
        display: list-item
    }

    blockquote,
    dd,
    dl,
    figure,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    hr,
    p,
    pre {
        margin: 0
    }

    fieldset {
        margin: 0;
        padding: 0
    }

    legend {
        padding: 0
    }

    menu,
    ol,
    ul {
        list-style: none;
        margin: 0;
        padding: 0
    }

    dialog {
        padding: 0
    }

    textarea {
        resize: vertical
    }

    input::placeholder,
    textarea::placeholder {
        opacity: 1;
        color: #9ca3af
    }

    [role=button],
    button {
        cursor: pointer
    }

    :disabled {
        cursor: default
    }

    audio,
    canvas,
    embed,
    iframe,
    img,
    object,
    svg,
    video {
        display: block;
        vertical-align: middle
    }

    img,
    video {
        max-width: 100%;
        height: auto
    }

    [hidden] {
        display: none
    }

    *,
    ::before,
    ::after {
        --tw-border-spacing-x: 0;
        --tw-border-spacing-y: 0;
        --tw-translate-x: 0;
        --tw-translate-y: 0;
        --tw-rotate: 0;
        --tw-skew-x: 0;
        --tw-skew-y: 0;
        --tw-scale-x: 1;
        --tw-scale-y: 1;
        --tw-pan-x: ;
        --tw-pan-y: ;
        --tw-pinch-zoom: ;
        --tw-scroll-snap-strictness: proximity;
        --tw-gradient-from-position: ;
        --tw-gradient-via-position: ;
        --tw-gradient-to-position: ;
        --tw-ordinal: ;
        --tw-slashed-zero: ;
        --tw-numeric-figure: ;
        --tw-numeric-spacing: ;
        --tw-numeric-fraction: ;
        --tw-ring-inset: ;
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        --tw-ring-color: rgb(59 130 246 / 0.5);
        --tw-ring-offset-shadow: 0 0 #0000;
        --tw-ring-shadow: 0 0 #0000;
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        --tw-blur: ;
        --tw-brightness: ;
        --tw-contrast: ;
        --tw-grayscale: ;
        --tw-hue-rotate: ;
        --tw-invert: ;
        --tw-saturate: ;
        --tw-sepia: ;
        --tw-drop-shadow: ;
        --tw-backdrop-blur: ;
        --tw-backdrop-brightness: ;
        --tw-backdrop-contrast: ;
        --tw-backdrop-grayscale: ;
        --tw-backdrop-hue-rotate: ;
        --tw-backdrop-invert: ;
        --tw-backdrop-opacity: ;
        --tw-backdrop-saturate: ;
        --tw-backdrop-sepia: ;
        --tw-contain-size: ;
        --tw-contain-layout: ;
        --tw-contain-paint: ;
        --tw-contain-style:
    }

    ::backdrop {
        --tw-border-spacing-x: 0;
        --tw-border-spacing-y: 0;
        --tw-translate-x: 0;
        --tw-translate-y: 0;
        --tw-rotate: 0;
        --tw-skew-x: 0;
        --tw-skew-y: 0;
        --tw-scale-x: 1;
        --tw-scale-y: 1;
        --tw-pan-x: ;
        --tw-pan-y: ;
        --tw-pinch-zoom: ;
        --tw-scroll-snap-strictness: proximity;
        --tw-gradient-from-position: ;
        --tw-gradient-via-position: ;
        --tw-gradient-to-position: ;
        --tw-ordinal: ;
        --tw-slashed-zero: ;
        --tw-numeric-figure: ;
        --tw-numeric-spacing: ;
        --tw-numeric-fraction: ;
        --tw-ring-inset: ;
        --tw-ring-offset-width: 0px;
        --tw-ring-offset-color: #fff;
        --tw-ring-color: rgb(59 130 246 / 0.5);
        --tw-ring-offset-shadow: 0 0 #0000;
        --tw-ring-shadow: 0 0 #0000;
        --tw-shadow: 0 0 #0000;
        --tw-shadow-colored: 0 0 #0000;
        --tw-blur: ;
        --tw-brightness: ;
        --tw-contrast: ;
        --tw-grayscale: ;
        --tw-hue-rotate: ;
        --tw-invert: ;
        --tw-saturate: ;
        --tw-sepia: ;
        --tw-drop-shadow: ;
        --tw-backdrop-blur: ;
        --tw-backdrop-brightness: ;
        --tw-backdrop-contrast: ;
        --tw-backdrop-grayscale: ;
        --tw-backdrop-hue-rotate: ;
        --tw-backdrop-invert: ;
        --tw-backdrop-opacity: ;
        --tw-backdrop-saturate: ;
        --tw-backdrop-sepia: ;
        --tw-contain-size: ;
        --tw-contain-layout: ;
        --tw-contain-paint: ;
        --tw-contain-style:
    }

    .left-0 {
        left: 0px
    }

    .top-0 {
        top: 0px
    }

    .-translate-x-full {
        --tw-translate-x: -100%;
        transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y))
    }

    .transform-none {
        transform: none
    }

    .bg-primary-100 {
        --tw-bg-opacity: 1;
        background-color: rgb(219 234 254 / var(--tw-bg-opacity))
    }

    .text-primary-800 {
        --tw-text-opacity: 1;
        color: rgb(30 64 175 / var(--tw-text-opacity))
    }

    .dark\:bg-primary-200:is(.dark *) {
        --tw-bg-opacity: 1;
        background-color: rgb(191 219 254 / var(--tw-bg-opacity))
    }

    .dark\:text-primary-800:is(.dark *) {
        --tw-text-opacity: 1;
        color: rgb(30 64 175 / var(--tw-text-opacity))
    }
</style>

    <script>
        $(document).ready(function() {
            // Dark mode:
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window
                    .matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark')
            }

            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window
                    .matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            themeToggleBtn.addEventListener('click', function() {

                // toggle icons inside button
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // if set via local storage previously
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }

                    // if NOT set via local storage previously
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }

            });

        });
    </script>


</head>

<body class="bg-gray-100 dark:bg-gray-800">

    <div class="flex h-screen">
        <!-- Menú lateral izquierdo -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md">
            <!-- Contenido del menú aquí -->
            <nav class="p-4">
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Configuración</a></li>
                    <!-- Agrega más elementos de menú según sea necesario -->
                </ul>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-4">
            @yield('content')
        </main>
    </div>
    


    <div id="app">
        <div class="bg-white dark:bg-gray-900 text-black dark:text-white">
            @yield('content')
        </div>
    </div>

    <!--datatables -->
    <script src="{{ asset('datatable/js/datatables.min.js') }}"></script>

    <!--select2 -->
    <script src="{{ asset('select2/select2.min.js') }}"></script>

    <!-- Flowbite -->
    <script src="{{ mix('node_modules/flowbite/dist/flowbite.min.js') }}"></script>

    @yield('js')
</body>

</html>

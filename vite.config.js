import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                //'resources/css/app.css',
                //'resources/js/app.js',
                'resources/sass/app.scss',
                //'resources/mdb/css/mdb.min.css',
                //'resources/css/fontawesome.min.css',
                //'resources/datatable/css/datatables.min.css',
                //'resources/select2/select2.min.css',
                'resources/js/app.js',
                //'resources/mdb/js/mdb.min.js',
                //'resources/datatable/js/datatables.min.js',
                //'resources/select2/select2.min.js'
                
                'node_modules/flowbite/dist/flowbite.min.css',
                'node_modules/flowbite/dist/flowbite.min.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});

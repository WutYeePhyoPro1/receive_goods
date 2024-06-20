import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';


export default defineConfig({
    plugins: [

        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // server: {
    //     host: true,
    // },
    server: {
        host: '192.168.201.90',
        port: 3000,
        hmr: {
            host: '192.168.201.90',
            port: 3000,
        },
    },
});
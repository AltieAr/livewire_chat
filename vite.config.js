import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: '/', // pastikan path root
    build: {
        manifest: true,
        outDir: 'public/build', // hasil build disimpan di sini
        rollupOptions: {
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

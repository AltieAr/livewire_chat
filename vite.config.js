import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {

        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
        },
    },
      output: {
            // tambahkan manual output folder jika perlu
            // untuk menghindari .vite/
            assetFileNames: 'assets/[name]-[hash][extname]',
            entryFileNames: 'assets/[name]-[hash].js',
        }
    ,
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

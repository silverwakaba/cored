import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Adminlte 3
                'resources/css/custom-bs4.css', 'resources/js/custom-bs4.js',

                // Other theme

                // Global
                'resources/js/echo.js',
            ],
            refresh: true,
        }),
    ],
});

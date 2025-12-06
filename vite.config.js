import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Adminlte 3
                'resources/css/adminlte3.css', 'resources/js/adminlte3.js',

                // Other theme

                // Global
                'resources/js/echo.js',
            ],
            refresh: true,
        }),
    ],
});

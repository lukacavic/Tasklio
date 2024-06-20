import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/app/theme.css', 'resources/css/filament/project/theme.css'],
            refresh: true,
        }),
    ],
});

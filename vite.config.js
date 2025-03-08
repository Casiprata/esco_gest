import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/filament/matricula/theme.css',
                'resources/css/filament/professor/theme.css',
                'resources/css/filament/responsavel/theme.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});

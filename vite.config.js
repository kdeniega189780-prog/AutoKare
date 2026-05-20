import os from 'node:os';
import path from 'node:path';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    cacheDir: path.join(os.tmpdir(), 'vite-cache'),
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});

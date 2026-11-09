import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import Vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        Vue({
            template: { transformAssetUrls: { base: null, includeAbsolute: false } },
        }),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin.css',
                'resources/js/admin/app.js',
                'resources/portfolio/main.js',
            ],
            refresh: true,
        }),
    ],
});

import { defineConfig } from 'vite';
import url from 'url';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwind from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import checker from 'vite-plugin-checker';

export default defineConfig({
    resolve: {
        alias: [
            {
                find: '@',
                replacement: url.fileURLToPath(new URL('./resources/js', import.meta.url).href),
            },
        ],
    },

    css: {
        postcss: {
            plugins: [tailwind(), autoprefixer()],
        },
    },

    plugins: [
        checker({
            vueTsc: true,
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
    ],
});

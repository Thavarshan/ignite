import path from 'path';
import { defineConfig } from 'vite';
import dts from 'vite-plugin-dts';

// https://vitejs.dev/config/
export default defineConfig({
    build: {
        lib: {
            entry: path.resolve(__dirname, './src/index.ts'),
            name: '@ignite/scribe',
            fileName: format => `scribe.${format}.js`
        }
    },

    resolve: {
        alias: {
            '@': path.resolve(__dirname, './src')
        }
    },

    plugins: [(dts({
        outputDir: path.resolve(__dirname, './types')
    }) as any)]
});

import { defineConfig } from 'vite';
import { getDirname } from '@adonisjs/core/helpers';
import inertia from '@adonisjs/inertia/client';
import vue from '@vitejs/plugin-vue';
import adonisjs from '@adonisjs/vite/client';
import tailwind from 'tailwindcss';
import autoprefixer from 'autoprefixer';

export default defineConfig({
  css: {
    postcss: {
      plugins: [tailwind(), autoprefixer()],
    },
  },

  plugins: [
    inertia({ ssr: { enabled: false } }),
    vue(),
    adonisjs({
      entrypoints: ['inertia/app/app.ts'],
      reload: ['resources/views/**/*.edge']
    }),
  ],

  /**
   * Define aliases for importing modules from
   * your frontend code
   */
  resolve: {
    alias: {
      '@': `${getDirname(import.meta.url)}/inertia/`,
    },
  },
});

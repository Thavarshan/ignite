import './bootstrap';
import '../css/app.css';
import { ZiggyVue } from 'ziggy-js';
import { MotionPlugin } from '@vueuse/motion';
import { createInertiaApp } from '@inertiajs/vue3';
import { DefineComponent, createApp, h } from 'vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { defineConfig } from '@/lib/utils/config';
import AppLayout from '@/layouts/app-layout.vue';
import { Items } from './types/repository';

createInertiaApp({
    title: (title) => `${title} - Minerva`,
    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

        for (const key in pages) {
            const page = await pages[key]();

            if (!key.includes('Auth/')) {
                page.default.layout = page.default.layout || AppLayout;
            }
        }

        return resolvePageComponent(`./pages/${name}.vue`, pages);
    },

    setup({ el, App, props, plugin }) {
        defineConfig(props.initialPage.props.config as Items);

        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(MotionPlugin)
            .mount(el);
    },

    progress: {
        color: '#38bdf8',
    },
});

import '../css/app.css';
import { ZiggyVue } from 'ziggy-js';
import { createInertiaApp } from '@inertiajs/vue3';
import { DefineComponent, createApp, h } from 'vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { config } from '@/lib/config';
import DefaultLayout from '@/layouts/default.vue';

createInertiaApp({
    title: (title) => `${title} - ${config('app.name', 'Ignite')}`,

    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

        for (const key in pages) {
            const page = await pages[key]();

            if (!key.includes('auth/')) {
                page.default.layout = page.default.layout || DefaultLayout;
            }
        }

        return resolvePageComponent(`./pages/${name}.vue`, pages);
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },

    progress: {
        color: '#2563eb',
    },
});

import './bootstrap';
import '../css/app.css';
import { ZiggyVue } from 'ziggy-js';
import { MotionPlugin } from '@vueuse/motion';
import { createInertiaApp } from '@inertiajs/vue3';
import { DefineComponent, createApp, h } from 'vue';

createInertiaApp({
    title: (title) => `${title} - Ignite`,
    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue', { eager: true });

        return pages[`./pages/${name}.vue`];
    },

    setup({ el, App, props, plugin }) {
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

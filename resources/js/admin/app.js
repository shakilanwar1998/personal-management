import '@fontsource-variable/inter';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import AdminLayout from './Layouts/AdminLayout.vue';

const pages = import.meta.glob('./Pages/**/*.vue');

createInertiaApp({
    title: (title) => (title ? `${title} · Finance` : 'Finance'),
    resolve: async (name) => {
        const loader = pages[`./Pages/${name}.vue`];
        if (!loader) throw new Error(`Admin page not found: ${name}`);
        const page = await loader();
        // Every page uses the admin shell unless it opts out with `layout: null`.
        if (page.default.layout === undefined) page.default.layout = AdminLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) }).use(plugin).mount(el);
    },
    progress: { color: '#6366f1', delay: 150 },
});

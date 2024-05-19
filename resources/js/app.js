import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import tailwindConfig from "../../tailwind.config.js";


createInertiaApp({
    resolve: (name) => resolvePageComponent(`../views/Pages/${name}.vue`, import.meta.glob('../views/Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .mount(el)
    },

    progress: {
        delay: 500,

        color: '#dd224e',

        // Whether to include the default NProgress styles...
        includeCSS: true,

        // Whether the NProgress spinner will be shown...
        showSpinner: true,
    },

}).then(r =>{
    console.log('App Initialized')
});


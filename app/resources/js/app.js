import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import DefaultLayout from './Shared/DefaultLayout.vue';
import { route} from 'ziggy-js';
import { Link } from '@inertiajs/inertia-vue3';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('DefaultLayout', DefaultLayout)
            .component('Link', Link)
            .mixin({ methods: { route: (name, params) => route(name, params) } }) //
            .mount(el);
    },
});

import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h, type App as VueApp } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import type { Page } from '@inertiajs/core';

const appName: string = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page: Page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title: string) => `${title} - ${appName}`,
        resolve: (name: string) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue'),
            ),
        setup({ App, props, plugin }) {
            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                });
        },
    }),
);

import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, type App as VueApp } from 'vue';
import { createI18n, type I18n } from 'vue-i18n';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import hu from './locales/hu.json';
import en from './locales/en.json';

const appName: string = import.meta.env.VITE_APP_NAME || 'Laravel';

const i18n: I18n = createI18n({
    locale: 'hu',
    fallbackLocale: 'en',
    messages: { hu, en },
    legacy: false,
    globalInjection: true,
});

createInertiaApp({
    title: (title: string) => `${title} - ${appName}`,
    resolve: (name: string) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app: VueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue);
        
        if (props.initialPage?.props?.locale) {
            i18n.global.locale.value = props.initialPage.props.locale;
        }
        
        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

import '../css/app.css';
import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Toast, {POSITION} from "vue-toastification";
import "vue-toastification/dist/index.css";
import dayjs from "dayjs";
import utc from "dayjs/plugin/utc";
import timezone from "dayjs/plugin/timezone";
import {useIsDark} from "@/Composables/useIsDark.js";
import {__} from "@/Composables/useTranslations.js";

dayjs.extend(utc)
dayjs.extend(timezone)


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'HRS';

const toastOptions = {
    position: POSITION.BOTTOM_RIGHT,        // ToastOptions object for each type of toast
    hideProgressBar: true,
    timeout: 3000,
};


createInertiaApp({
    title: (title) => `${title} | ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        dayjs.tz.setDefault(props.initialPage.props.timezone);
        const VueApp = createApp({ render: () => h(App, props) });
        VueApp.config.globalProperties.__ = __;
        VueApp.use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(Toast, toastOptions)
            .use(VueSweetalert2)
            .provide('isDark', useIsDark())
            .mount(el);
    },
    progress: {
        // The delay after which the progress bar will appear, in milliseconds...
        delay: 150,
        // The color of the progress bar...
        color: '#9345fe',
        // Whether to include the default NProgress styles...
        includeCSS: true,
    },
});

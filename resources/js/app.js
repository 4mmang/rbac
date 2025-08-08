import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';
import 'flowbite';

import DataTablesLib from 'datatables.net'; 
import DataTable from 'datatables.net-vue3';

 
DataTable.use(DataTablesLib); 

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const page = pages[`./Pages/${name}.vue`]

        if (!page) {
            console.error(`❌ Halaman Vue "${name}" tidak ditemukan! Cek apakah file ./Pages/${name}.vue ada.`)
            throw new Error(`Halaman "${name}" tidak ditemukan.`)
        }

        return page
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('DataTable', DataTable)
            .mount(el)  
    },
})
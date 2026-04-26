import { createInertiaApp } from '@inertiajs/svelte'
import render from '@inertiajs/svelte/server'

createInertiaApp({
    page: (page) => page,
    render,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
        return pages[`./Pages/${name}.svelte`]
    },
    setup({ App, props }) {
        return App({ props })
    },
})

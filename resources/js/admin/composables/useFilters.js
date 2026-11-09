import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';

function clean(obj) {
    const out = {};
    for (const [k, v] of Object.entries(obj)) {
        if (v === '' || v === null || v === undefined || v === false) continue;
        out[k] = v;
    }
    return out;
}

/**
 * Keeps list filters in the URL. Any change reloads the page data (debounced),
 * keeps component state and scroll position, and drops the page number.
 */
export function useFilters(initial, { only = undefined, delay = 300 } = {}) {
    const filters = reactive({ ...initial });
    let timer = null;

    const apply = () => {
        router.get(window.location.pathname, clean(filters), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only,
        });
    };

    watch(
        filters,
        () => {
            clearTimeout(timer);
            timer = setTimeout(apply, delay);
        },
        { deep: true },
    );

    const set = (values) => Object.assign(filters, values);

    const reset = (defaults = {}) => {
        for (const k of Object.keys(filters)) filters[k] = defaults[k] ?? '';
    };

    const sortBy = (column) => {
        if (filters.sort === column) {
            filters.dir = filters.dir === 'asc' ? 'desc' : 'asc';
        } else {
            filters.sort = column;
            filters.dir = column === 'name' || column.endsWith('_name') ? 'asc' : 'desc';
        }
    };

    return { filters, set, reset, sortBy, apply };
}

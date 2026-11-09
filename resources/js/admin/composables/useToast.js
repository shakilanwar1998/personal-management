import { reactive } from 'vue';

export const toasts = reactive([]);
let id = 0;

export function toast(message, type = 'success', timeout = 3500) {
    const t = { id: ++id, message, type };
    toasts.push(t);
    setTimeout(() => dismiss(t.id), timeout);
}

export function dismiss(tid) {
    const i = toasts.findIndex((t) => t.id === tid);
    if (i !== -1) toasts.splice(i, 1);
}

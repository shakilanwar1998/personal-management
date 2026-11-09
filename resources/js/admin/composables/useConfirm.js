import { reactive } from 'vue';

export const confirmState = reactive({
    open: false,
    title: '',
    message: '',
    confirmText: 'Confirm',
    tone: 'danger',
    resolve: null,
});

/** `await confirm({ title, message })` → true / false. */
export function confirm({ title = 'Are you sure?', message = '', confirmText = 'Delete', tone = 'danger' } = {}) {
    return new Promise((resolve) => {
        Object.assign(confirmState, { open: true, title, message, confirmText, tone, resolve });
    });
}

export function settleConfirm(value) {
    confirmState.resolve?.(value);
    confirmState.open = false;
    confirmState.resolve = null;
}

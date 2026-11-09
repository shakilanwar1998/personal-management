import { reactive } from 'vue';

/** Shared state for the global "add transaction" dialog. */
export const quickAdd = reactive({ open: false, type: 'expense', record: null });

export function openQuickAdd(type = 'expense', record = null) {
    Object.assign(quickAdd, { open: true, type, record });
}

export function closeQuickAdd() {
    quickAdd.open = false;
    quickAdd.record = null;
}

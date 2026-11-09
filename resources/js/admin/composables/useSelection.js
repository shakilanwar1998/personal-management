import { computed, ref, watch } from 'vue';

/** Row selection for bulk actions. `rows` is a ref/computed of the current page's rows. */
export function useSelection(rows) {
    const selected = ref([]);

    watch(rows, () => {
        const ids = new Set(rows.value.map((r) => r.id));
        selected.value = selected.value.filter((id) => ids.has(id));
    });

    const allSelected = computed(() => rows.value.length > 0 && selected.value.length === rows.value.length);
    const someSelected = computed(() => selected.value.length > 0 && !allSelected.value);

    const toggleAll = () => {
        selected.value = allSelected.value ? [] : rows.value.map((r) => r.id);
    };
    const clear = () => (selected.value = []);

    return { selected, allSelected, someSelected, toggleAll, clear };
}

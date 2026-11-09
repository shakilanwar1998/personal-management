<script setup>
import { ArrowDown, ArrowUp, ChevronsUpDown } from 'lucide-vue-next';
defineProps({
    column: { type: String, required: true },
    sort: { type: String, default: '' },
    dir: { type: String, default: 'desc' },
    align: { type: String, default: 'left' },
});
defineEmits(['sort']);
</script>

<template>
    <th :class="align === 'right' ? 'text-right' : ''">
        <button type="button" class="group inline-flex items-center gap-1 hover:text-zinc-900 dark:hover:text-zinc-100" :class="align === 'right' ? 'flex-row-reverse' : ''" @click="$emit('sort', column)">
            <slot />
            <ArrowUp v-if="sort === column && dir === 'asc'" class="h-3.5 w-3.5 text-zinc-700 dark:text-zinc-200" />
            <ArrowDown v-else-if="sort === column" class="h-3.5 w-3.5 text-zinc-700 dark:text-zinc-200" />
            <ChevronsUpDown v-else class="h-3.5 w-3.5 opacity-0 transition group-hover:opacity-60" />
        </button>
    </th>
</template>

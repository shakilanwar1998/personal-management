<script setup>
import { computed } from 'vue';
import CategoryIcon from './CategoryIcon.vue';
import { money } from '../lib/format';

/** Ranked horizontal bars — rows: [{ name, total, icon?, color?, sub? }]. */
const props = defineProps({
    rows: { type: Array, required: true },
    limit: { type: Number, default: 8 },
    showShare: { type: Boolean, default: true },
    barClass: { type: String, default: 'bg-brand-500/80 dark:bg-brand-400/70' },
});
const emit = defineEmits(['select']);
const total = computed(() => props.rows.reduce((s, r) => s + r.total, 0));
const max = computed(() => Math.max(1, ...props.rows.map((r) => r.total)));
const shown = computed(() => {
    if (props.rows.length <= props.limit) return props.rows;
    const head = props.rows.slice(0, props.limit - 1);
    const rest = props.rows.slice(props.limit - 1);
    return [...head, { name: `Other (${rest.length})`, total: rest.reduce((s, r) => s + r.total, 0), icon: 'circle-dot', color: 'zinc', other: true }];
});
</script>

<template>
    <ul class="space-y-3">
        <li v-for="r in shown" :key="r.name" class="group" :title="`${r.name}: ${money(r.total)}`">
            <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                <button type="button" class="flex min-w-0 items-center gap-2.5 text-left" @click="!r.other && emit('select', r)">
                    <CategoryIcon v-if="r.icon || r.color" :icon="r.icon" :color="r.color" size="sm" />
                    <span class="truncate font-medium text-zinc-700 group-hover:text-zinc-900 dark:text-zinc-300 dark:group-hover:text-white">{{ r.name }}</span>
                </button>
                <span class="num shrink-0 text-zinc-900 dark:text-zinc-100">
                    {{ money(r.total, { decimals: 0 }) }}
                    <span v-if="showShare && total > 0" class="ml-1 text-xs text-zinc-400">{{ ((r.total / total) * 100).toFixed(0) }}%</span>
                </span>
            </div>
            <div class="h-1.5 w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800">
                <div class="h-full rounded-full" :class="barClass" :style="{ width: Math.max(1.5, (r.total / max) * 100) + '%' }" />
            </div>
        </li>
    </ul>
</template>

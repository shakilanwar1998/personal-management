<script setup>
import { ref } from 'vue';
import { ChevronRight } from 'lucide-vue-next';
import CategoryIcon from './CategoryIcon.vue';
import { money, moneyCompact } from '../lib/format';

/** Category × month table with single-hue (sequential) cell shading. */
const props = defineProps({
    matrix: { type: Object, required: true }, // { rows, monthTotals, total, max }
    labels: { type: Array, required: true },
});

const open = ref(new Set());
const toggle = (id) => {
    const s = new Set(open.value);
    s.has(id) ? s.delete(id) : s.add(id);
    open.value = s;
};

// One hue, light → dark: mix the series colour into the surface by value / max.
function shade(v) {
    if (!v || !props.matrix.max) return {};
    const pct = Math.round(6 + 49 * Math.min(1, v / props.matrix.max));
    return { backgroundColor: `color-mix(in srgb, var(--series-savings) ${pct}%, transparent)` };
}
const cell = (v) => (v ? moneyCompact(v) : '—');
</script>

<template>
    <div class="table-wrap">
        <table class="data-table text-[13px]">
            <thead>
                <tr>
                    <th class="sticky left-0 z-10 min-w-[11rem] bg-zinc-50 dark:bg-zinc-900">Category</th>
                    <th v-for="m in labels" :key="m" class="text-right">{{ m }}</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Share</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="r in matrix.rows" :key="r.id ?? 'none'">
                    <tr>
                        <td class="sticky left-0 z-10 bg-white dark:bg-zinc-900">
                            <button
                                type="button"
                                class="flex items-center gap-2 text-left"
                                :class="r.children.length ? 'cursor-pointer' : 'cursor-default'"
                                :aria-expanded="open.has(r.id)"
                                @click="r.children.length && toggle(r.id)"
                            >
                                <ChevronRight
                                    class="h-3.5 w-3.5 shrink-0 text-zinc-400 transition-transform"
                                    :class="[open.has(r.id) ? 'rotate-90' : '', r.children.length ? '' : 'invisible']"
                                />
                                <CategoryIcon :icon="r.icon" :color="r.color" size="sm" />
                                <span class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ r.name }}</span>
                            </button>
                        </td>
                        <td
                            v-for="(v, i) in r.months"
                            :key="i"
                            class="num whitespace-nowrap text-right"
                            :class="v ? 'text-zinc-800 dark:text-zinc-100' : 'text-zinc-300 dark:text-zinc-600'"
                            :style="shade(v)"
                            :title="`${r.name} · ${labels[i]}: ${money(v)}`"
                        >{{ cell(v) }}</td>
                        <td class="num whitespace-nowrap text-right font-semibold text-zinc-900 dark:text-white">{{ money(r.total, { decimals: 0 }) }}</td>
                        <td class="num whitespace-nowrap text-right text-zinc-500">{{ r.share }}%</td>
                    </tr>
                    <template v-if="open.has(r.id)">
                        <tr v-for="c in r.children" :key="`${r.id}-${c.id}-${c.name}`" class="bg-zinc-50/50 dark:bg-zinc-900/40">
                            <td class="sticky left-0 z-10 bg-zinc-50 pl-14 text-zinc-600 dark:bg-zinc-900 dark:text-zinc-400">{{ c.name }}</td>
                            <td
                                v-for="(v, i) in c.months"
                                :key="i"
                                class="num whitespace-nowrap text-right text-zinc-600 dark:text-zinc-400"
                                :title="`${c.name} · ${labels[i]}: ${money(v)}`"
                            >{{ cell(v) }}</td>
                            <td class="num whitespace-nowrap text-right text-zinc-700 dark:text-zinc-300">{{ money(c.total, { decimals: 0 }) }}</td>
                            <td />
                        </tr>
                    </template>
                </template>
            </tbody>
            <tfoot v-if="matrix.rows.length">
                <tr>
                    <td class="sticky left-0 z-10 bg-zinc-50 dark:bg-zinc-900">Total</td>
                    <td v-for="(v, i) in matrix.monthTotals" :key="i" class="num whitespace-nowrap text-right">{{ cell(v) }}</td>
                    <td class="num whitespace-nowrap text-right">{{ money(matrix.total, { decimals: 0 }) }}</td>
                    <td class="num text-right">100%</td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

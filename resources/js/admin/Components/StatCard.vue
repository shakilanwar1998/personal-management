<script setup>
import { computed } from 'vue';
import { ArrowDownRight, ArrowUpRight } from 'lucide-vue-next';

const props = defineProps({
    label: { type: String, required: true },
    value: { type: String, required: true },
    sub: { type: String, default: '' },
    delta: { type: Number, default: null }, // percent change
    goodWhen: { type: String, default: 'up' }, // up | down — which direction is good
    icon: { type: [Object, Function], default: null },
    tone: { type: String, default: 'zinc' }, // icon tint
});
const good = computed(() => (props.delta === null ? null : props.goodWhen === 'up' ? props.delta >= 0 : props.delta <= 0));
const tones = {
    zinc: 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300',
    brand: 'bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300',
    green: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
    red: 'bg-rose-50 text-rose-600 dark:bg-rose-500/15 dark:text-rose-400',
    amber: 'bg-amber-50 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400',
    blue: 'bg-sky-50 text-sky-600 dark:bg-sky-500/15 dark:text-sky-400',
    violet: 'bg-violet-50 text-violet-600 dark:bg-violet-500/15 dark:text-violet-400',
};
</script>

<template>
    <div class="card p-5">
        <div class="flex items-start justify-between gap-3">
            <p class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">{{ label }}</p>
            <span v-if="icon" class="flex h-8 w-8 items-center justify-center rounded-lg" :class="tones[tone]">
                <component :is="icon" class="h-4 w-4" />
            </span>
        </div>
        <p class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ value }}</p>
        <div class="mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-1 text-xs">
            <span
                v-if="delta !== null"
                class="inline-flex items-center gap-0.5 rounded-md px-1.5 py-0.5 font-medium"
                :class="good ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400'"
            >
                <ArrowUpRight v-if="delta >= 0" class="h-3 w-3" />
                <ArrowDownRight v-else class="h-3 w-3" />
                {{ Math.abs(delta).toFixed(1) }}%
            </span>
            <span v-if="sub" class="text-zinc-500 dark:text-zinc-400">{{ sub }}</span>
        </div>
        <slot />
    </div>
</template>

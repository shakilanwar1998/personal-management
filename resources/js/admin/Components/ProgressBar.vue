<script setup>
import { computed } from 'vue';

const props = defineProps({
    value: { type: Number, default: 0 },
    max: { type: Number, default: 100 },
    tone: { type: String, default: 'auto' }, // auto | brand | green | amber | red
    height: { type: String, default: 'h-2' },
});
const pct = computed(() => (props.max > 0 ? Math.min(100, Math.max(0, (props.value / props.max) * 100)) : 0));
const ratio = computed(() => (props.max > 0 ? props.value / props.max : 0));
const color = computed(() => {
    const t = props.tone === 'auto' ? (ratio.value >= 1 ? 'red' : ratio.value >= 0.85 ? 'amber' : 'green') : props.tone;
    return { brand: 'bg-brand-500', green: 'bg-emerald-500', amber: 'bg-amber-500', red: 'bg-rose-500' }[t] || 'bg-brand-500';
});
</script>

<template>
    <div class="w-full overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800" :class="height" role="progressbar" :aria-valuenow="Math.round(pct)" aria-valuemin="0" aria-valuemax="100">
        <div class="h-full rounded-full transition-all duration-500" :class="color" :style="{ width: pct + '%' }" />
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { iconFor } from '../lib/icons';
import { chipStyle } from '../lib/colors';

const props = defineProps({
    icon: { type: String, default: null },
    color: { type: String, default: null },
    size: { type: String, default: 'md' }, // sm | md | lg
});
const cls = computed(() => ({ sm: 'h-6 w-6 rounded-md', md: 'h-8 w-8 rounded-lg', lg: 'h-10 w-10 rounded-xl' })[props.size]);
const iconCls = computed(() => ({ sm: 'h-3.5 w-3.5', md: 'h-4 w-4', lg: 'h-5 w-5' })[props.size]);
</script>

<template>
    <span class="inline-flex shrink-0 items-center justify-center" :class="cls" :style="chipStyle(color)">
        <component :is="iconFor(icon)" :class="iconCls" stroke-width="2" />
    </span>
</template>

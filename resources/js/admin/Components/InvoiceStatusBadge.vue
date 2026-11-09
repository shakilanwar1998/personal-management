<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, required: true },
    overdue: { type: Boolean, default: false },
});

const cls = {
    draft: 'badge-gray',
    sent: 'badge-blue',
    due: 'badge-amber',
    paid: 'badge-green',
    overdue: 'badge-red',
    cancelled: 'badge-gray line-through decoration-zinc-400/60',
};
const label = computed(() => props.status.charAt(0).toUpperCase() + props.status.slice(1));
</script>

<template>
    <span class="inline-flex items-center gap-1">
        <span class="badge" :class="cls[status] || 'badge-gray'">{{ label }}</span>
        <span v-if="overdue && status !== 'overdue'" class="badge badge-red" title="Due date has passed">Overdue</span>
    </span>
</template>

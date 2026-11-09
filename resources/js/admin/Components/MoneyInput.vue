<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const model = defineModel();
defineProps({
    error: { type: Boolean, default: false },
    id: { type: String, default: undefined },
    placeholder: { type: String, default: '0' },
    prefix: { type: String, default: null },
});
const page = usePage();
const symbol = computed(() => page.props.currency || '৳');
</script>

<template>
    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm text-zinc-400">{{ prefix ?? symbol }}</span>
        <input
            :id="id"
            v-model="model"
            type="number"
            step="0.01"
            min="0"
            inputmode="decimal"
            :placeholder="placeholder"
            class="input num"
            :class="[{ 'input-error': error }, (prefix ?? symbol).length > 2 ? 'pl-12' : (prefix ?? symbol).length > 1 ? 'pl-10' : 'pl-8']"
        />
    </div>
</template>

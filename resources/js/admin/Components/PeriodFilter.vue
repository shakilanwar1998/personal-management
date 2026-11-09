<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/** v-model:year ('' | 'all' | 'YYYY' | 'custom'), v-model:from, v-model:to */
const year = defineModel('year', { type: [String, Number], default: '' });
const from = defineModel('from', { type: String, default: '' });
const to = defineModel('to', { type: String, default: '' });

const page = usePage();
const selected = computed(() => page.props.calendar?.selected);
const others = computed(() => (page.props.calendar?.available ?? []).filter((y) => y !== selected.value));
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <select v-model="year" class="input w-auto min-w-[9.5rem]" aria-label="Period">
            <option value="">{{ selected }} (selected year)</option>
            <option v-for="y in others" :key="y" :value="String(y)">{{ y }}</option>
            <option value="all">All time</option>
            <option value="custom">Custom range…</option>
        </select>
        <template v-if="year === 'custom'">
            <input v-model="from" type="date" class="input w-auto" aria-label="From date" />
            <span class="text-sm text-zinc-400">to</span>
            <input v-model="to" type="date" class="input w-auto" aria-label="To date" />
        </template>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/** Period picker for ledgers that default to all time: '' = all time, 'YYYY', 'custom'. */
const year = defineModel('year', { type: [String, Number], default: '' });
const from = defineModel('from', { type: String, default: '' });
const to = defineModel('to', { type: String, default: '' });

const page = usePage();
const years = computed(() => page.props.calendar?.available ?? []);
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <select v-model="year" class="input w-auto min-w-[8.5rem]" aria-label="Period">
            <option value="">All time</option>
            <option v-for="y in years" :key="y" :value="String(y)">{{ y }}</option>
            <option value="custom">Custom range…</option>
        </select>
        <template v-if="year === 'custom'">
            <input v-model="from" type="date" class="input w-auto" aria-label="From date" />
            <span class="text-sm text-zinc-400">to</span>
            <input v-model="to" type="date" class="input w-auto" aria-label="To date" />
        </template>
    </div>
</template>

<script setup>
defineProps({
    title: { type: String, required: true },
    description: { type: String, default: '' },
});
</script>

<template>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white sm:text-2xl">{{ title }}</h1>
            <p v-if="description" class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ description }}</p>
        </div>
        <div v-if="$slots.default" class="flex flex-wrap items-center gap-2"><slot /></div>
    </div>
</template>

<script setup>
const model = defineModel({ type: Boolean, default: false });
defineProps({ label: { type: String, default: '' }, description: { type: String, default: '' } });
</script>

<template>
    <label class="flex cursor-pointer items-start gap-3">
        <button
            type="button"
            role="switch"
            :aria-checked="model"
            class="relative mt-0.5 inline-flex h-5 w-9 shrink-0 rounded-full transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-500/50"
            :class="model ? 'bg-brand-600' : 'bg-zinc-300 dark:bg-zinc-700'"
            @click="model = !model"
        >
            <span class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform" :class="model ? 'translate-x-4' : ''" />
        </button>
        <span v-if="label || description" class="text-sm" @click.prevent="model = !model">
            <span class="block font-medium text-zinc-800 dark:text-zinc-200">{{ label }}</span>
            <span v-if="description" class="block text-xs text-zinc-500 dark:text-zinc-400">{{ description }}</span>
        </span>
    </label>
</template>

<script setup>
import { Inbox } from 'lucide-vue-next';
defineProps({
    title: { type: String, default: 'Nothing here yet' },
    description: { type: String, default: '' },
    icon: { type: [Object, Function], default: () => Inbox },
});
</script>

<template>
    <div class="flex flex-col items-center justify-center px-6 py-14 text-center">
        <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-zinc-100 text-zinc-400 dark:bg-zinc-800 dark:text-zinc-500">
            <component :is="icon" class="h-5 w-5" />
        </div>
        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ title }}</h3>
        <p v-if="description" class="mt-1 max-w-sm text-sm text-zinc-500 dark:text-zinc-400">{{ description }}</p>
        <div v-if="$slots.default" class="mt-4"><slot /></div>
    </div>
</template>

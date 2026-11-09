<script setup>
import { Trash2, X } from 'lucide-vue-next';
defineProps({ count: { type: Number, required: true } });
defineEmits(['delete', 'clear']);
</script>

<template>
    <Transition enter-from-class="opacity-0 translate-y-2" enter-active-class="transition" leave-to-class="opacity-0 translate-y-2" leave-active-class="transition">
        <div v-if="count > 0" class="fixed inset-x-0 bottom-5 z-40 flex justify-center px-4">
            <div class="flex items-center gap-3 rounded-xl border border-zinc-800 bg-zinc-900 py-2 pl-4 pr-2 text-sm text-white shadow-pop dark:border-zinc-700">
                <span class="num font-medium">{{ count }} selected</span>
                <span class="h-5 w-px bg-zinc-700" />
                <slot />
                <button type="button" class="btn btn-sm bg-rose-600 text-white hover:bg-rose-500" @click="$emit('delete')">
                    <Trash2 class="h-3.5 w-3.5" /> Delete
                </button>
                <button type="button" class="btn btn-icon btn-sm text-zinc-400 hover:bg-zinc-800 hover:text-white" aria-label="Clear selection" @click="$emit('clear')">
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
    </Transition>
</template>

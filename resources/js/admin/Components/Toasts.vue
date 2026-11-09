<script setup>
import { CheckCircle2, XCircle, X } from 'lucide-vue-next';
import { toasts, dismiss } from '../composables/useToast';
</script>

<template>
    <Teleport to="body">
        <div class="pointer-events-none fixed inset-x-0 bottom-0 z-[60] flex flex-col items-center gap-2 p-4 sm:items-end sm:p-6" aria-live="polite">
            <div
                v-for="t in toasts"
                :key="t.id"
                class="pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-pop animate-slide-in dark:border-zinc-700 dark:bg-zinc-900"
            >
                <CheckCircle2 v-if="t.type === 'success'" class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" />
                <XCircle v-else class="mt-0.5 h-5 w-5 shrink-0 text-rose-500" />
                <p class="flex-1 text-sm text-zinc-800 dark:text-zinc-100">{{ t.message }}</p>
                <button class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200" aria-label="Dismiss" @click="dismiss(t.id)">
                    <X class="h-4 w-4" />
                </button>
            </div>
        </div>
    </Teleport>
</template>

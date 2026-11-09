<script setup>
import { nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    size: { type: String, default: 'md' }, // sm | md | lg | xl | 2xl
});
const emit = defineEmits(['close']);
const panel = ref(null);

const widths = { sm: 'max-w-sm', md: 'max-w-lg', lg: 'max-w-2xl', xl: 'max-w-4xl', '2xl': 'max-w-6xl' };

const onKey = (e) => {
    if (e.key === 'Escape' && props.show) emit('close');
};

watch(
    () => props.show,
    async (open) => {
        document.body.style.overflow = open ? 'hidden' : '';
        if (open) {
            window.addEventListener('keydown', onKey);
            await nextTick();
            panel.value?.querySelector('[autofocus], input:not([type=hidden]):not([type=checkbox]), select, textarea')?.focus();
        } else {
            window.removeEventListener('keydown', onKey);
        }
    },
    { immediate: true },
);

onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-zinc-950/40 backdrop-blur-[2px] animate-fade-in dark:bg-black/60" @click="emit('close')" />
            <div class="relative flex min-h-full items-end justify-center p-0 sm:items-center sm:p-6">
                <div
                    ref="panel"
                    role="dialog"
                    aria-modal="true"
                    :aria-label="title"
                    :class="[widths[size] || widths.md]"
                    class="relative w-full rounded-t-2xl border border-zinc-200 bg-white shadow-pop animate-pop-in dark:border-zinc-800 dark:bg-zinc-900 sm:rounded-2xl"
                >
                    <div v-if="title" class="flex items-start justify-between gap-4 px-6 pb-2 pt-5">
                        <div>
                            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">{{ title }}</h2>
                            <p v-if="description" class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ description }}</p>
                        </div>
                        <button type="button" class="btn btn-ghost btn-icon btn-sm -mr-2 -mt-1" aria-label="Close" @click="emit('close')">
                            <X class="h-4 w-4" />
                        </button>
                    </div>
                    <div class="px-6 py-4"><slot /></div>
                    <div v-if="$slots.footer" class="flex flex-col-reverse gap-2 rounded-b-2xl border-t border-zinc-100 bg-zinc-50/70 px-6 py-3.5 dark:border-zinc-800 dark:bg-zinc-900/60 sm:flex-row sm:items-center sm:justify-end">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

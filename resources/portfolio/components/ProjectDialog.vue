<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import Icon from './Icon.vue';
import PostCard from './PostCard.vue';

// Full project details for projects without a case study or external link.
const props = defineProps({ post: { type: Object, required: true } });
const emit = defineEmits(['close', 'ask', 'tag']);
const panel = ref(null);
const opener = document.activeElement;

function onKey(e) {
    if (e.key === 'Escape') emit('close');
    if (e.key === 'Tab' && panel.value) {
        const f = [...panel.value.querySelectorAll('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])')];
        if (!f.length) return;
        if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
        else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
    }
}
onMounted(async () => {
    document.addEventListener('keydown', onKey);
    document.body.style.overflow = 'hidden';
    await nextTick();
    panel.value?.querySelector('[data-close]')?.focus();
});
onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKey);
    document.body.style.overflow = '';
    if (opener && opener !== document.body && document.contains(opener)) opener.focus({ preventScroll: true });
});
</script>

<template>
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true" :aria-label="post.name">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        <div class="relative mx-auto flex min-h-full max-w-3xl items-start justify-center px-3 py-6 sm:px-6 sm:py-12">
            <div ref="panel" class="relative w-full animate-rise">
                <button data-close type="button" class="btn-icon absolute -top-1 right-1 z-10 h-9 w-9 shadow-lg sm:-right-12 sm:top-0" aria-label="Close project" @click="emit('close')">
                    <Icon name="close" :size="17" />
                </button>
                <PostCard :post="post" expanded-default class="shadow-2xl" @ask="(p) => emit('ask', p)" @tag="(t) => emit('tag', t)" />
            </div>
        </div>
    </div>
</template>

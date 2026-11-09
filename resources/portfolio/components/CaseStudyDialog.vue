<script setup>
// A case study in a modal, with a link to open it as its own page in a new tab.
import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import Icon from './Icon.vue';
import CaseStudy from './CaseStudy.vue';
import { caseUrl } from '../cases/index.js';

const props = defineProps({
    post: { type: Object, required: true },
    study: { type: Object, required: true },
});
const emit = defineEmits(['close', 'ask']);
const panel = ref(null);
const scroller = ref(null);
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
    <div ref="scroller" class="fixed inset-0 z-[60] overflow-y-auto overscroll-contain" role="dialog" aria-modal="true" :aria-label="`${post.name} case study`">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        <div class="relative mx-auto flex min-h-full max-w-[860px] items-start justify-center px-0 py-0 sm:px-6 sm:py-10">
            <div ref="panel" class="relative w-full animate-rise overflow-hidden bg-surface shadow-2xl sm:rounded-[28px] sm:border sm:border-line">
                <!-- toolbar -->
                <div class="sticky top-0 z-10 flex items-center gap-2 border-b border-line bg-surface/90 px-4 py-2.5 backdrop-blur-xl sm:px-6">
                    <p class="min-w-0 flex-1 truncate text-sm font-semibold">{{ post.name }} <span class="font-normal text-faint">· case study</span></p>
                    <a :href="caseUrl(post.id)" target="_blank" rel="noopener" class="btn h-9 px-3 text-[13px] text-muted hover:bg-raised hover:text-ink">
                        <Icon name="external" :size="15" /><span class="hidden sm:inline">Open in new tab</span>
                    </a>
                    <button data-close type="button" class="btn-icon h-9 w-9" aria-label="Close case study" @click="emit('close')">
                        <Icon name="close" :size="17" />
                    </button>
                </div>
                <div class="px-4 pb-8 pt-5 sm:px-8 sm:pb-10 sm:pt-7">
                    <CaseStudy :post="post" :study="study" @ask="(p) => emit('ask', p)" />
                </div>
            </div>
        </div>
    </div>
</template>

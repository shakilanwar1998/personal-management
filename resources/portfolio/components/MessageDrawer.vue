<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import Icon from './Icon.vue';
import Avatar from './Avatar.vue';
import { faqs, profile } from '../data.js';

const props = defineProps({
    draft: { type: String, default: '' },
    name: { type: String, default: '' },
});
// close payload: { message, name } so the parent can restore an unsent draft next time.
const emit = defineEmits(['close']);

// Element that opened the drawer; focus goes back to it on close.
const opener = typeof document !== 'undefined' ? document.activeElement : null;

const thread = ref([
    { from: 'me', text: 'Hi! 👋 Tell me which part of your business still runs on copy-paste. I’ll reply with how I’d automate it.' },
]);
const asked = ref(new Set());
const message = ref(props.draft);
const name = ref(props.name);
const panel = ref(null);
const list = ref(null);
const copied = ref(false);

function close() {
    emit('close', { message: message.value, name: name.value });
}

async function ask(f) {
    if (asked.value.has(f.q)) return;
    asked.value = new Set([...asked.value, f.q]);
    thread.value.push({ from: 'you', text: f.q });
    await scroll();
    // Keep keyboard users in place: next unasked chip, otherwise the message box.
    const nextChip = panel.value?.querySelector('[data-faq]:not([aria-disabled="true"])');
    (nextChip || panel.value?.querySelector('#dm-msg'))?.focus({ preventScroll: true });
    setTimeout(async () => {
        thread.value.push({ from: 'me', text: f.a, quick: true });
        await scroll();
    }, 350);
}
async function scroll() {
    await nextTick();
    list.value?.scrollTo({ top: list.value.scrollHeight, behavior: 'smooth' });
}

const body = computed(() => {
    const sign = name.value.trim() ? `\n\n— ${name.value.trim()}` : '';
    return `${message.value.trim()}${sign}`;
});
const mailto = computed(
    () => `mailto:${profile.email}?subject=${encodeURIComponent('Automation project' + (name.value.trim() ? ' · ' + name.value.trim() : ''))}&body=${encodeURIComponent(body.value)}`,
);
const wa = computed(() => `https://wa.me/${profile.whatsapp}?text=${encodeURIComponent(body.value)}`);
const canSend = computed(() => message.value.trim().length > 0);

async function copyEmail() {
    try {
        await navigator.clipboard.writeText(profile.email);
        copied.value = true;
        setTimeout(() => (copied.value = false), 1500);
    } catch (e) { /* clipboard blocked */ }
}

function onKey(e) {
    if (e.key === 'Escape') close();
    if (e.key === 'Tab' && panel.value) {
        const f = [...panel.value.querySelectorAll('button:not([disabled]), a[href], textarea, input')].filter((el) => el.offsetParent !== null);
        if (!f.length) return;
        if (e.shiftKey && document.activeElement === f[0]) { e.preventDefault(); f[f.length - 1].focus(); }
        else if (!e.shiftKey && document.activeElement === f[f.length - 1]) { e.preventDefault(); f[0].focus(); }
    }
}
onMounted(async () => {
    document.addEventListener('keydown', onKey);
    document.documentElement.style.overflow = 'hidden';
    await nextTick();
    panel.value?.querySelector(props.draft ? 'textarea' : '[data-autofocus]')?.focus();
});
onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKey);
    document.documentElement.style.overflow = '';
    if (opener && opener !== document.body && document.contains(opener)) opener.focus({ preventScroll: true });
});
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-end justify-end bg-black/50 backdrop-blur-[2px] sm:items-stretch" @click.self="close">
        <section
            ref="panel"
            role="dialog"
            aria-modal="true"
            aria-labelledby="dm-title"
            class="flex max-h-[92dvh] w-full animate-rise flex-col overflow-hidden rounded-t-[28px] border border-line bg-surface shadow-2xl sm:max-h-none sm:w-[420px] sm:rounded-none sm:rounded-l-[28px] sm:border-y-0 sm:border-r-0"
        >
            <header class="flex items-center gap-3 border-b border-line px-4 py-3">
                <Avatar :size="40" online />
                <div class="min-w-0 flex-1">
                    <h2 id="dm-title" class="text-[15px] font-semibold leading-tight">{{ profile.name }}</h2>
                    <p class="text-xs text-muted">Replies by email or WhatsApp · {{ profile.timezone }}</p>
                </div>
                <button type="button" data-autofocus class="btn-icon border-transparent" aria-label="Close messages" @click="close">
                    <Icon name="close" />
                </button>
            </header>

            <div ref="list" class="flex flex-1 flex-col overflow-y-auto px-4 py-4" aria-live="polite">
                <div class="mt-auto space-y-2">
                <div v-for="(m, i) in thread" :key="i" class="flex animate-rise" :class="m.from === 'you' ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[85%] rounded-[20px] px-3.5 py-2 text-[14px] leading-snug" :class="m.from === 'you' ? 'rounded-br-md bg-ai-fill text-white' : 'rounded-bl-md bg-raised'">
                        {{ m.text }}
                        <span v-if="m.quick" class="mt-1 block font-mono text-[10px] text-faint">quick answer</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-1.5 pt-2">
                    <button
                        v-for="f in faqs"
                        :key="f.q"
                        type="button"
                        data-faq
                        class="rounded-full border border-line px-3 py-1.5 text-[13px] font-medium transition hover:border-ai/50 hover:bg-ai/5 aria-disabled:cursor-default aria-disabled:opacity-40 aria-disabled:hover:border-line aria-disabled:hover:bg-transparent"
                        :aria-disabled="asked.has(f.q) ? 'true' : undefined"
                        @click="ask(f)"
                    >{{ f.q }}</button>
                </div>
                </div>
            </div>

            <form class="space-y-2 border-t border-line p-3" @submit.prevent>
                <label class="sr-only" for="dm-name">Your name</label>
                <input id="dm-name" v-model="name" type="text" autocomplete="name" placeholder="Your name (optional)" class="w-full rounded-xl border border-line bg-bg px-3 py-2 text-sm placeholder:text-faint focus:border-ai focus:outline-none" />
                <label class="sr-only" for="dm-msg">Message</label>
                <textarea id="dm-msg" v-model="message" rows="3" placeholder="What would you like to automate?" class="w-full resize-none rounded-xl border border-line bg-bg px-3 py-2 text-sm placeholder:text-faint focus:border-ai focus:outline-none"></textarea>
                <div class="flex flex-wrap items-center gap-2">
                    <a :href="canSend ? mailto : undefined" class="btn-primary flex-1" :class="{ 'cursor-not-allowed opacity-50': !canSend }" :aria-disabled="!canSend" :title="canSend ? undefined : 'Type a message first'">
                        <Icon name="mail" :size="16" /> Send by email
                    </a>
                    <a :href="canSend ? wa : undefined" target="_blank" rel="noopener noreferrer" class="btn-ghost flex-1" :class="{ 'cursor-not-allowed opacity-50': !canSend }" :aria-disabled="!canSend" :title="canSend ? undefined : 'Type a message first'">
                        <Icon name="whatsapp" :size="16" class="text-[#25d366]" /> WhatsApp
                    </a>
                </div>
                <p class="flex items-start justify-between gap-3 px-1 pt-0.5 text-xs text-faint">
                    <span>Opens your email app or WhatsApp. Nothing is stored here.</span>
                    <button type="button" class="shrink-0 font-medium text-muted underline-offset-2 hover:text-ink hover:underline" @click="copyEmail">{{ copied ? 'Copied!' : 'Copy email' }}</button>
                </p>
            </form>
        </section>
    </div>
</template>

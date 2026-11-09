<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import Icon from './Icon.vue';
import Avatar from './Avatar.vue';
import { posts, profile } from '../data.js';

const props = defineProps({
    highlights: { type: Array, required: true },
    start: { type: Number, default: 0 },
});
const emit = defineEmits(['close', 'open-post', 'seen']);

// Element that opened the viewer; focus goes back to it on close.
const opener = typeof document !== 'undefined' ? document.activeElement : null;

const DURATION = 6500;
const h = ref(props.start);
const s = ref(0);
const progress = ref(0);
const paused = ref(false); // only the Pause button, Space and reduced motion change this
const held = ref(false); // true while a finger/mouse is pressed on the slide
const dialog = ref(null);
const announce = ref(''); // read out only for user-initiated slide changes
let downAt = 0;

const story = computed(() => props.highlights[h.value]);
const slide = computed(() => story.value.slides[s.value]);
const related = computed(() => (slide.value.posts || []).map((id) => posts.find((p) => p.id === id)).filter(Boolean));

// Video slides: progress follows the video, and the story moves on when it ends.
const video = ref(null);
const isVideo = computed(() => !!slide.value.video);
const muted = ref(false);
async function playVideo({ restart = false } = {}) {
    const v = video.value;
    if (!v) return;
    if (restart) v.currentTime = 0;
    if (paused.value || held.value) return v.pause();
    try {
        await v.play();
    } catch (e) {
        // Browsers can block autoplay with sound; fall back to muted.
        if (!v.muted) {
            muted.value = true;
            v.muted = true;
            v.play().catch(() => {});
        }
    }
}
function toggleMute() {
    muted.value = !muted.value;
    if (video.value) video.value.muted = muted.value;
}

let raf;
let last;
function tick(t) {
    if (last === undefined) last = t;
    if (isVideo.value) {
        const v = video.value;
        if (v?.duration) progress.value = Math.min(v.currentTime / v.duration, 0.999);
    } else if (!paused.value && !held.value) progress.value += (t - last) / DURATION;
    last = t;
    if (progress.value >= 1) next();
    raf = requestAnimationFrame(tick);
}

function next() {
    progress.value = 0;
    if (s.value < story.value.slides.length - 1) s.value++;
    else if (h.value < props.highlights.length - 1) { h.value++; s.value = 0; }
    else emit('close');
}
function prev() {
    progress.value = 0;
    if (s.value > 0) s.value--;
    else if (h.value > 0) { h.value--; s.value = props.highlights[h.value].slides.length - 1; }
}

function say() {
    announce.value = `Slide ${s.value + 1} of ${story.value.slides.length}${slide.value.title ? `: ${slide.value.title}` : ''}`;
}
function userNext() { next(); say(); }
function userPrev() { prev(); say(); }

// A long press pauses the story; releasing it must not also navigate.
// Keyboard activation has e.detail === 0 and always navigates.
function tap(e, fn) {
    if (e.detail === 0 || performance.now() - downAt <= 250) fn();
}

function onKey(e) {
    if (e.key === 'Escape') emit('close');
    else if (e.key === 'ArrowRight') userNext();
    else if (e.key === 'ArrowLeft') userPrev();
    else if (e.key === ' ' && !e.target.closest?.('button, a[href], input, textarea, select, [role="button"]')) {
        e.preventDefault();
        paused.value = !paused.value;
    }
    else if (e.key === 'Tab') {
        // keep focus inside the dialog
        const f = dialog.value.querySelectorAll('button, a[href]');
        if (!f.length) return;
        const first = f[0], lastEl = f[f.length - 1];
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); lastEl.focus(); }
        else if (!e.shiftKey && document.activeElement === lastEl) { e.preventDefault(); first.focus(); }
    }
}

const reduced = typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
onMounted(async () => {
    document.addEventListener('keydown', onKey);
    document.documentElement.style.overflow = 'hidden';
    if (reduced) paused.value = true;
    raf = requestAnimationFrame(tick);
    await nextTick();
    dialog.value?.querySelector('[data-autofocus]')?.focus();
});
onBeforeUnmount(() => {
    document.removeEventListener('keydown', onKey);
    document.documentElement.style.overflow = '';
    cancelAnimationFrame(raf);
    if (opener && opener !== document.body && document.contains(opener)) opener.focus({ preventScroll: true });
});
watch([h, s], async () => {
    progress.value = 0;
    await nextTick();
    if (isVideo.value) playVideo({ restart: true });
});
watch([paused, held], () => isVideo.value && playVideo());
watch(h, (i) => emit('seen', props.highlights[i].id), { immediate: true });
</script>

<template>
    <div class="fixed inset-0 z-[60] grid place-items-center bg-black/80 p-0 backdrop-blur-sm sm:p-6" @click.self="emit('close')">
        <div
            ref="dialog"
            role="dialog"
            aria-modal="true"
            :aria-label="`${story.label} highlights`"
            class="relative flex aspect-[9/16] w-[min(100vw,calc(100dvh*9/16))] flex-col overflow-hidden bg-[#0d0f13] text-white sm:w-[min(420px,calc(92dvh*9/16))] sm:rounded-[28px]"
        >
            <!-- backdrop art -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-[#3a66f5]/40 blur-3xl"></div>
                <div class="absolute -right-24 bottom-24 h-72 w-72 rounded-full bg-[#a78bfa]/20 blur-3xl"></div>
                <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,.07)_1px,transparent_1px)] [background-size:18px_18px]"></div>
            </div>

            <!-- video slide (full-bleed, behind the controls) -->
            <template v-if="isVideo">
                <video
                    :key="`${h}-${s}`"
                    ref="video"
                    :src="slide.video"
                    :poster="slide.poster"
                    :muted="muted"
                    class="absolute inset-0 z-0 h-full w-full object-cover"
                    playsinline
                    preload="auto"
                    autoplay
                    @loadeddata="playVideo()"
                    @ended="next()"
                ></video>
                <div class="pointer-events-none absolute inset-x-0 top-0 z-0 h-28 bg-gradient-to-b from-black/60 to-transparent"></div>
            </template>

            <!-- progress -->
            <div class="relative z-10 flex gap-1 px-3 pt-3">
                <span v-for="(sl, i) in story.slides" :key="i" class="h-[3px] flex-1 overflow-hidden rounded-full bg-white/25">
                    <span class="block h-full origin-left rounded-full bg-white" :style="{ transform: `scaleX(${i < s ? 1 : i === s ? Math.min(progress, 1) : 0})` }"></span>
                </span>
            </div>

            <header class="relative z-10 flex items-center gap-2.5 px-4 pt-3">
                <Avatar :size="34" />
                <p class="min-w-0 flex-1 text-sm leading-tight">
                    <span class="font-semibold">{{ profile.name }}</span>
                    <span class="block text-xs text-white/60">{{ story.label }} · {{ s + 1 }}/{{ story.slides.length }}</span>
                </p>
                <button v-if="isVideo" type="button" class="grid h-9 w-9 place-items-center rounded-full hover:bg-white/10" :aria-label="muted ? 'Unmute' : 'Mute'" :aria-pressed="muted" @click="toggleMute">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 5 6 9H3v6h3l5 4V5Z" fill="currentColor" />
                        <path v-if="muted" d="m16 9 5 6M21 9l-5 6" />
                        <path v-else d="M15.5 8.5a5 5 0 0 1 0 7M18.5 5.5a9 9 0 0 1 0 13" />
                    </svg>
                </button>
                <button type="button" class="grid h-9 w-9 place-items-center rounded-full hover:bg-white/10" :aria-label="paused ? 'Play' : 'Pause'" :aria-pressed="paused" @click="paused = !paused">
                    <svg v-if="paused" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 4v16l13-8z" /></svg>
                    <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6 4h4v16H6zM14 4h4v16h-4z" /></svg>
                </button>
                <button type="button" data-autofocus class="grid h-9 w-9 place-items-center rounded-full hover:bg-white/10" aria-label="Close highlights" @click="emit('close')">
                    <Icon name="close" :size="20" />
                </button>
            </header>

            <!-- slide -->
            <div
                class="relative z-10 flex flex-1 flex-col justify-center px-7"
                @pointerdown="downAt = performance.now(); held = true"
                @pointerup="held = false"
                @pointercancel="held = false"
                @pointerleave="held = false"
            >
                <!-- tap zones -->
                <button type="button" class="absolute inset-y-0 left-0 w-1/3" aria-label="Previous slide" @click="tap($event, userPrev)"></button>
                <button type="button" class="absolute inset-y-0 right-0 w-1/3" aria-label="Next slide" @click="tap($event, userNext)"></button>
                <p class="sr-only" role="status" aria-live="polite">{{ announce }}</p>

                <Transition mode="out-in" enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 translate-y-3" leave-active-class="transition duration-150" leave-to-class="opacity-0">
                    <div v-if="isVideo" :key="`v-${h}-${s}`" class="pointer-events-none mt-auto pb-2">
                        <p v-if="slide.title" class="text-balance text-[22px] font-bold leading-tight tracking-[-0.02em] [text-shadow:0_1px_12px_rgb(0_0_0/.6)]">{{ slide.title }}</p>
                        <p v-if="slide.body" class="mt-1.5 text-[14px] leading-snug text-white/85 [text-shadow:0_1px_8px_rgb(0_0_0/.6)]">{{ slide.body }}</p>
                    </div>
                    <div v-else :key="`${h}-${s}`" class="pointer-events-none">
                        <span class="inline-grid h-14 w-14 place-items-center rounded-2xl bg-white/10 font-mono text-lg font-semibold ring-1 ring-white/15">{{ story.glyph }}</span>
                        <h2 class="mt-6 text-balance text-[32px] font-bold leading-[1.1] tracking-[-0.025em]">{{ slide.title }}</h2>
                        <p class="mt-4 text-[15px] leading-relaxed text-white/75">{{ slide.body }}</p>
                    </div>
                </Transition>
            </div>

            <footer v-if="related.length" class="relative z-20 px-4 pb-5">
                <p class="mb-2 font-mono text-[10.5px] uppercase tracking-[0.14em] text-white/50">Seen in</p>
                <div class="no-scrollbar -m-1 flex gap-2 overflow-x-auto p-1">
                    <button
                        v-for="p in related"
                        :key="p.id"
                        type="button"
                        class="flex max-w-[240px] shrink-0 items-center gap-2 rounded-full bg-white/10 py-1.5 pl-1.5 pr-3 text-left text-[13px] ring-1 ring-white/10 transition hover:bg-white/20 focus-visible:outline-offset-[-2px]"
                        @click="emit('open-post', p.id)"
                    >
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-[#3a66f5] text-white"><Icon name="arrow" :size="13" /></span>
                        <span class="truncate">{{ p.name }}</span>
                    </button>
                </div>
            </footer>

            <div class="pointer-events-none absolute inset-x-0 bottom-0 z-0 h-40 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>
    </div>
</template>

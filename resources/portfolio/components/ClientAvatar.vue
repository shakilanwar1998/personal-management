<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';

// A reviewer's photo when data.js has one, otherwise their initials on a colour picked from their name.
// The small corner badge shows which platform the review came from.
const props = defineProps({
    review: { type: Object, required: true },
    size: { type: Number, default: 40 },
});

const TONES = [
    'from-[#8b7cff] to-[#3b2f99]',
    'from-[#fcd34d] to-[#b45309]',
    'from-[#93c5fd] to-[#1d4ed8]',
    'from-[#6ee7b7] to-[#047857]',
    'from-[#fda4af] to-[#be123c]',
    'from-[#c4b5fd] to-[#6d28d9]',
    'from-[#67e8f9] to-[#0e7490]',
];
const tone = computed(() => TONES[[...props.review.name].reduce((n, c) => (n * 31 + c.charCodeAt(0)) >>> 0, 43) % TONES.length]);
const initials = computed(() => props.review.name.split(/\s+/).map((w) => w[0]).join('').slice(0, 2).toUpperCase());
</script>

<template>
    <span class="relative inline-grid shrink-0 place-items-center" :style="{ width: size + 'px', height: size + 'px' }">
        <img v-if="review.avatar" :src="review.avatar" :alt="review.name" class="h-full w-full rounded-full object-cover ring-1 ring-line" loading="lazy" />
        <span v-else-if="review.anonymous" class="grid h-full w-full place-items-center rounded-full bg-raised text-muted ring-1 ring-inset ring-line" aria-hidden="true">
            <Icon name="user" :size="Math.round(size * 0.5)" />
        </span>
        <span
            v-else
            class="grid h-full w-full place-items-center rounded-full bg-gradient-to-br font-semibold text-white ring-1 ring-inset ring-white/20"
            :class="tone"
            :style="{ fontSize: size * 0.36 + 'px' }"
            aria-hidden="true"
        >{{ initials }}</span>
        <span
            v-if="review.platform"
            class="absolute -bottom-0.5 -right-0.5 grid place-items-center rounded-full border-2 border-surface bg-surface"
            :style="{ width: size * 0.42 + 'px', height: size * 0.42 + 'px' }"
        >
            <TechLogo :name="review.platform" :size="Math.round(size * 0.26)" />
        </span>
    </span>
</template>

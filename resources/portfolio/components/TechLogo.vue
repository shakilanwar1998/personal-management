<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';
import { logo, useInk } from '../logos.js';

// A technology's brand logo (or a line icon for concepts without one).
const props = defineProps({
    name: { type: String, required: true },
    size: { type: Number, default: 18 },
    tile: { type: Boolean, default: false }, // draw it on a rounded tile
    mono: { type: Boolean, default: false }, // single colour (inherits text colour)
});
const l = computed(() => logo(props.name));
const color = computed(() => (!l.value || l.value.kind !== 'brand' || props.mono || useInk(l.value.hex) ? 'currentColor' : l.value.hex));
const initials = computed(() => props.name.replace(/[^A-Za-z0-9 ]/g, '').split(/\s+/).map((w) => w[0]).join('').slice(0, 2).toUpperCase());
</script>

<template>
    <span
        class="inline-grid shrink-0 place-items-center"
        :class="tile ? 'rounded-[28%] border border-line bg-surface shadow-[0_1px_2px_rgb(0_0_0/.06)]' : ''"
        :style="tile ? { width: size * 1.9 + 'px', height: size * 1.9 + 'px' } : {}"
        :title="l?.title || name"
    >
        <svg v-if="l?.kind === 'brand'" :width="size" :height="size" viewBox="0 0 24 24" role="img" :aria-label="l.title" :fill="color">
            <path :d="l.path" />
        </svg>
        <Icon v-else-if="l?.kind === 'glyph'" :name="l.icon" :size="size" class="text-ai-text" />
        <span v-else class="font-mono font-semibold text-muted" :style="{ fontSize: size * 0.6 + 'px' }">{{ initials }}</span>
    </span>
</template>

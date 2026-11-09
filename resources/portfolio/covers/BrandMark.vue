<script setup>
// The product's own logo on a cover. Real marks for products that have one (Ghurun, VintBay);
// everything else gets a monogram tile in the product's colour, so anonymised client work still
// reads as a product.
import { computed } from 'vue';
import { logo } from '../logos.js';

const props = defineProps({
    brand: { type: Object, default: () => ({}) }, // { image: '/images/brands/x.png' } or { mark: 'ghurun' | 'vintbay' | 'sadb' } or { text: 'QA', color: '#…', glyph?: logo key }
    name: { type: String, required: true },
    textSize: { type: String, default: '2.6cqw' }, // monogram letters, in cover units
});

const initials = computed(() => props.brand.text || props.name.split(/\s+/).map((w) => w[0]).join('').slice(0, 2).toUpperCase());
const glyph = computed(() => (props.brand.glyph ? logo(props.brand.glyph) : null));
</script>

<template>
    <!-- A real logo file (e.g. Okkhor's app icon) -->
    <img v-if="brand.image" :src="brand.image" :alt="`${name} logo`" class="h-full w-full rounded-[24%] object-cover" />

    <!-- Ghurun: a map pin on the flag's green, with the flag's red disc as its eye -->
    <svg v-else-if="brand.mark === 'ghurun'" viewBox="0 0 48 48" class="h-full w-full" aria-hidden="true">
        <rect width="48" height="48" rx="12" fill="#006a4e" />
        <path d="M24 9.5a11.5 11.5 0 0 0-11.5 11.5c0 8.2 9 15.6 10.6 17.3a1.25 1.25 0 0 0 1.8 0C26.5 36.6 35.5 29.2 35.5 21A11.5 11.5 0 0 0 24 9.5z" fill="#fff" />
        <circle cx="24" cy="21" r="5" fill="#f42a41" />
    </svg>

    <!-- VintBay: the V + e ligature on Vinted's teal -->
    <svg v-else-if="brand.mark === 'vintbay'" viewBox="0 0 32 32" fill="none" class="h-full w-full" aria-hidden="true">
        <rect x="1" y="1" width="30" height="30" rx="8.75" fill="#3E7F88" />
        <path d="M5.40 10.40C5.47 9.06 6.39 8.49 7.52 8.56C8.65 8.64 9.28 9.34 9.21 10.47C9.35 13.44 9.85 17.25 10.76 20.00C12.11 15.98 13.87 11.67 15.49 8.49C15.71 8.00 16.27 7.86 16.41 8.21C16.48 8.49 16.27 9.06 16.06 9.69C14.51 14.14 12.95 18.87 11.96 22.19C11.61 23.25 10.84 23.67 9.92 23.60C8.93 23.53 8.29 22.96 8.08 21.98C7.02 18.24 5.89 13.65 5.40 10.40Z" fill="#fff" />
        <path d="M22.46 21.16A4.27 4.27 0 1 1 23.75 19.01" stroke="#fff" stroke-width="2.68" fill="none" />
        <path d="M14.15 17.98H25.05" stroke="#fff" stroke-width="2.68" fill="none" />
    </svg>

    <!-- SaDB: a database cylinder with a spark -->
    <svg v-else-if="brand.mark === 'sadb'" viewBox="0 0 48 48" class="h-full w-full" aria-hidden="true">
        <rect width="48" height="48" rx="12" fill="#1f1a3d" />
        <ellipse cx="22" cy="15" rx="10" ry="4" fill="none" stroke="#b0a6ff" stroke-width="2.6" />
        <path d="M12 15v17c0 2.2 4.5 4 10 4s10-1.8 10-4V15M12 23.5c0 2.2 4.5 4 10 4s10-1.8 10-4" fill="none" stroke="#b0a6ff" stroke-width="2.6" />
        <path d="M36 25l1.6 4.4L42 31l-4.4 1.6L36 37l-1.6-4.4L30 31l4.4-1.6L36 25Z" fill="#ffba52" />
    </svg>

    <!-- Monogram tile -->
    <span
        v-else
        class="grid h-full w-full place-items-center rounded-[24%] font-bold leading-none tracking-[-0.03em] text-white ring-1 ring-inset ring-white/25"
        :style="{ background: `linear-gradient(140deg, ${brand.color || '#5b5bd6'}, ${brand.color2 || brand.color || '#3b2f99'})`, fontSize: textSize }"
    >
        <svg v-if="glyph?.kind === 'brand'" viewBox="0 0 24 24" class="h-[56%] w-[56%]" fill="#fff" aria-hidden="true"><path :d="glyph.path" /></svg>
        <template v-else>{{ initials }}</template>
    </span>
</template>

<script setup>
import { computed } from 'vue';
import { logo, useInk } from '../logos.js';
import BrandMark from '../covers/BrandMark.vue';

// Product scenes: covers/scenes/<Name>.vue, picked by a post's `cover.scene`.
const SCENES = Object.fromEntries(
    Object.entries(import.meta.glob('../covers/scenes/*.vue', { eager: true })).map(([path, mod]) => [path.split('/').pop().replace('.vue', ''), mod.default]),
);

/*
 * Cover art for a project.
 * - `cover.scene`: a sketch of the product itself (covers/scenes/), with the product's logo
 *   (`cover.brand`) and a one-line `cover.tagline`. Client UIs are redrawn, never screenshotted.
 * - Otherwise: generated from the project's tone, tech stack and headline number.
 * - `image` (e.g. '/images/projects/ghurun.jpg') replaces both with a real picture.
 */
const props = defineProps({
    post: { type: Object, required: true },
    compact: { type: Boolean, default: false }, // smaller cards: hide secondary text
});

const TONES = {
    violet: { from: '#2a1f66', to: '#0e0b24', glow: '#8b7cff', ink: '#c9c1ff' },
    orange: { from: '#40190a', to: '#140805', glow: '#ff915e', ink: '#ffc4a8' },
    teal: { from: '#0a3330', to: '#041312', glow: '#3ddc97', ink: '#a7f3d0' },
    blue: { from: '#0d2a52', to: '#060f1e', glow: '#5aa9ff', ink: '#bfdbfe' },
    rose: { from: '#3f0f27', to: '#15060d', glow: '#ff7aa8', ink: '#fecdd3' },
    amber: { from: '#3d2c06', to: '#151004', glow: '#ffba52', ink: '#fde68a' },
    slate: { from: '#1e2633', to: '#0a0d12', glow: '#94a3b8', ink: '#e2e8f0' },
};
const PATTERNS = {
    dots: 'radial-gradient(rgb(255 255 255 / .22) 1px, transparent 1.2px) 0 0 / 3.2cqw 3.2cqw',
    grid: 'linear-gradient(rgb(255 255 255 / .09) 1px, transparent 1px) 0 0 / 6cqw 6cqw, linear-gradient(90deg, rgb(255 255 255 / .09) 1px, transparent 1px) 0 0 / 6cqw 6cqw',
    rings: 'repeating-radial-gradient(circle at 78% 50%, rgb(255 255 255 / .1) 0 1px, transparent 1px 4.5cqw)',
    lines: 'repeating-linear-gradient(135deg, rgb(255 255 255 / .07) 0 1px, transparent 1px 2.4cqw)',
};

const cover = computed(() => props.post.cover || {});
const tone = computed(() => TONES[cover.value.tone] || TONES.violet);
const pattern = computed(() => PATTERNS[cover.value.pattern] || PATTERNS.dots);

// Brand logos from the tags (or an explicit list), at most four.
const logos = computed(() => {
    const names = cover.value.logos || props.post.tags || [];
    const seen = new Set();
    const out = [];
    for (const n of names) {
        const l = logo(n);
        if (l?.kind === 'brand' && !seen.has(l.key)) {
            seen.add(l.key);
            out.push(l);
        }
        if (out.length === 4) break;
    }
    return out;
});
const fill = (l) => (useInk(l.hex) ? '#111' : l.hex);

const headline = computed(() => cover.value.stat || props.post.stats?.[0] || null);
const scene = computed(() => SCENES[cover.value.scene] || null);
</script>

<template>
    <div class="relative aspect-[16/9] w-full overflow-hidden bg-[#0b0d12] [container-type:inline-size]" role="img" :aria-label="`Cover image for ${post.name}`">
        <img v-if="post.image" :src="post.image" :alt="post.name" loading="lazy" class="absolute inset-0 h-full w-full object-cover" />
        <template v-else>
            <!-- background -->
            <div class="absolute inset-0" :style="{ background: `radial-gradient(90% 120% at 85% 0%, ${tone.glow}40, transparent 55%), linear-gradient(140deg, ${tone.from}, ${tone.to})` }" />
            <div class="absolute inset-0 [mask-image:linear-gradient(90deg,transparent,black_45%)]" :style="{ background: pattern }" />
            <div class="absolute -bottom-[30cqw] -left-[10cqw] h-[55cqw] w-[55cqw] rounded-full blur-[6cqw]" :style="{ background: `${tone.glow}22` }" />

            <!-- product scene: the product's logo and a sketch of the product itself -->
            <template v-if="scene">
                <component :is="scene" :post="post" />
                <div class="absolute left-0 top-0 max-w-[46%] p-[5cqw]">
                    <div class="flex items-center gap-[1.6cqw]">
                        <span class="block h-[6.4cqw] w-[6.4cqw] shrink-0 drop-shadow-[0_0.8cqw_1.6cqw_rgb(0_0_0/.35)]"><BrandMark :brand="cover.brand || {}" :name="post.name" text-size="2.6cqw" /></span>
                        <p class="text-[4.4cqw] font-bold leading-[1.02] tracking-[-0.025em] text-white">{{ post.name }}</p>
                    </div>
                    <p v-if="cover.tagline" class="mt-[2cqw] text-pretty text-[2.5cqw] font-medium leading-snug text-white/80">{{ cover.tagline }}</p>
                </div>
                <p class="absolute bottom-[4.4cqw] left-[5cqw] font-mono text-[1.6cqw] font-medium uppercase tracking-[0.16em]" :style="{ color: tone.ink }">
                    {{ post.kind }}<template v-if="!compact"> · {{ post.when }}</template>
                </p>
            </template>

            <!-- left: what it is -->
            <div v-else class="absolute inset-y-0 left-0 flex w-[54%] flex-col justify-between p-[5.5cqw]">
                <div>
                    <p class="text-[4.4cqw] font-bold leading-[1.05] tracking-[-0.02em] text-white">{{ post.name }}</p>
                    <p class="mt-[0.8cqw] font-mono text-[1.9cqw] font-medium uppercase tracking-[0.16em]" :style="{ color: tone.ink }">{{ post.kind }}</p>
                </div>
                <div>
                    <p v-if="headline" class="text-[10.5cqw] font-bold leading-[0.95] tracking-[-0.04em] text-white">{{ headline.value }}</p>
                    <p v-if="headline" class="mt-[1.4cqw] max-w-[90%] text-[2.6cqw] leading-snug text-white/70">{{ headline.label }}</p>
                    <p v-else class="text-[5.2cqw] font-semibold leading-[1.05] tracking-[-0.03em] text-white">{{ cover.label || post.kind }}</p>
                </div>
                <p v-if="!compact" class="truncate text-[2.2cqw] text-white/50">{{ post.when }}</p>
                <p v-else class="h-[2.2cqw]"></p>
            </div>

            <!-- right: the stack, drawn as connected tiles -->
            <div v-if="!scene" class="absolute inset-y-0 right-0 w-[46%]">
                <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <path v-if="logos.length > 1" d="M38 50 L72 22 M38 50 L76 78 M38 50 L88 50" :stroke="tone.glow" stroke-opacity=".45" stroke-width=".5" stroke-dasharray="1.5 1.5" vector-effect="non-scaling-stroke" fill="none" />
                </svg>
                <div
                    v-for="(l, i) in logos"
                    :key="l.key"
                    class="absolute grid place-items-center rounded-[22%] bg-white shadow-[0_1.2cqw_3cqw_rgb(0_0_0/.45)] ring-1 ring-white/30"
                    :style="[
                        i === 0
                            ? { left: '38%', top: '50%', width: '17cqw', height: '17cqw', transform: 'translate(-50%,-50%)' }
                            : [{ left: '72%', top: '22%' }, { left: '76%', top: '78%' }, { left: '88%', top: '50%' }][i - 1],
                        i > 0 ? { width: '9.5cqw', height: '9.5cqw', transform: 'translate(-50%,-50%)' } : {},
                    ]"
                    :title="l.title"
                >
                    <svg :width="i === 0 ? '55%' : '56%'" :height="i === 0 ? '55%' : '56%'" viewBox="0 0 24 24" :fill="fill(l)" aria-hidden="true"><path :d="l.path" /></svg>
                </div>
                <div v-if="!logos.length" class="absolute left-[40%] top-1/2 grid h-[17cqw] w-[17cqw] -translate-x-1/2 -translate-y-1/2 place-items-center rounded-[22%] text-[6cqw] font-bold text-white ring-1 ring-white/25" :style="{ background: `${tone.glow}33` }">
                    {{ post.name.charAt(0) }}
                </div>
            </div>
        </template>
    </div>
</template>

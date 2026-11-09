<script setup>
// Deal Finder: the Buy Score for one item, and the eBay comparables it was priced against —
// look-alikes set aside with a reason, and any comparable a person can untick.
import Frame from '../Frame.vue';

const COMPS = [
    { t: 'Waxed field jacket, olive, M', p: '£72.00', on: true, c: ['#7a8450', '#4c562d'] },
    { t: 'Wax cotton country jacket, M', p: '£64.50', on: true, c: ['#8a7a55', '#5a4a2b'] },
    { t: 'Classic waxed jacket, sage, M', p: '£68.00', on: true, c: ['#76866a', '#46553b'] },
    { t: 'Kids’ waxed jacket, age 10', p: '£24.00', on: false, why: 'Kids’ size, not comparable', c: ['#9aa28a', '#6c7458'] },
    { t: 'Quilted jacket liner only', p: '£18.00', on: false, why: 'Lining only, not a jacket', c: ['#a39a86', '#6f6656'] },
];
</script>

<template>
    <!-- comparables the score was priced against -->
    <div class="absolute left-[49cqw] top-[5.5cqw] w-[47cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="deal finder · item 2187">
            <div class="p-[1.6cqw]">
                <p class="flex items-baseline justify-between leading-tight">
                    <span class="text-[1.55cqw] font-bold">Comparable eBay listings</span>
                    <span class="text-[1.15cqw] font-medium text-black/45">3 of 5 used</span>
                </p>
                <div class="mt-[1cqw] space-y-[0.7cqw]">
                    <div
                        v-for="c in COMPS"
                        :key="c.t"
                        class="flex items-center gap-[1cqw] rounded-[0.9cqw] border px-[1cqw] py-[0.7cqw]"
                        :class="c.on ? 'border-black/[.08]' : 'border-dashed border-black/15 bg-black/[.025]'"
                    >
                        <span
                            class="grid h-[2.1cqw] w-[2.1cqw] shrink-0 place-items-center rounded-[0.5cqw]"
                            :class="c.on ? 'bg-[#d97706] text-white' : 'border-[0.2cqw] border-black/20'"
                        >
                            <svg v-if="c.on" viewBox="0 0 24 24" class="h-[70%] w-[70%]" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                        </span>
                        <span class="h-[3.2cqw] w-[3.2cqw] shrink-0 rounded-[0.6cqw]" :class="c.on ? '' : 'opacity-50'" :style="{ background: `linear-gradient(135deg, ${c.c[0]}, ${c.c[1]})` }"></span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block truncate text-[1.3cqw] font-semibold" :class="c.on ? '' : 'text-black/35 line-through'">{{ c.t }}</span>
                            <span v-if="c.why" class="mt-[0.25cqw] inline-flex items-center gap-[0.4cqw] rounded-full bg-[#e5484d]/10 px-[0.7cqw] py-[0.1cqw] text-[1.02cqw] font-semibold text-[#c62a2f]">
                                <svg viewBox="0 0 24 24" class="h-[1.1cqw] w-[1.1cqw]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18" /></svg>
                                {{ c.why }}
                            </span>
                            <span v-else class="block text-[1.05cqw] text-black/45">Pre-owned · Buy it now</span>
                        </span>
                        <span class="text-[1.4cqw] font-bold tabular-nums" :class="c.on ? '' : 'text-black/30 line-through'">{{ c.p }}</span>
                    </div>
                </div>
            </div>
            <div class="h-[3cqw]"></div>
        </Frame>
    </div>

    <!-- the score -->
    <div class="absolute bottom-[8cqw] left-[27cqw] w-[20cqw] -rotate-[2.5deg] rounded-[1.6cqw] bg-white px-[1.6cqw] pb-[1.4cqw] pt-[1.2cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <p class="text-[1.15cqw] font-semibold uppercase tracking-[0.08em] text-black/40">Buy Score</p>
        <div class="relative mt-[0.4cqw]">
            <svg viewBox="0 0 100 56" class="block w-full" aria-hidden="true">
                <path d="M8 50a42 42 0 0 1 84 0" fill="none" stroke="#000" stroke-opacity=".08" stroke-width="9" stroke-linecap="round" />
                <path d="M8 50a42 42 0 0 1 84 0" fill="none" stroke="#f59e0b" stroke-width="9" stroke-linecap="round" pathLength="100" stroke-dasharray="78 100" />
            </svg>
            <p class="absolute inset-x-0 bottom-0 text-center leading-none">
                <span class="text-[4.2cqw] font-bold tracking-[-0.04em]">7.8</span><span class="text-[1.5cqw] font-semibold text-black/40"> / 10</span>
            </p>
        </div>
        <p class="mt-[1cqw] flex items-center justify-center gap-[0.6cqw] text-[1.2cqw] font-semibold text-[#2f8a5b]">
            <span class="h-[1cqw] w-[1cqw] rounded-full bg-[#30a46c]"></span>
            High confidence · 3 comps
        </p>
    </div>
</template>

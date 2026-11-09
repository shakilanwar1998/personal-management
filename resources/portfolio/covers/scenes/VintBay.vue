<script setup>
// VintBay: a Vinted item flows into an eBay listing whose item specifics each carry their source,
// ready to publish once it passes eBay's checks.
import Frame from '../Frame.vue';

const SRC = {
    Vinted: 'bg-[#09b1ba]/15 text-[#047a80]',
    Rule: 'bg-[#64748b]/15 text-[#475569]',
    AI: 'bg-[#8b5cf6]/15 text-[#6d28d9]',
    Manual: 'bg-[#f59e0b]/20 text-[#a16207]',
};
const ROWS = [
    { k: 'Department', v: 'Women', s: 'Vinted' },
    { k: 'Size', v: 'UK 12', s: 'Rule' },
    { k: 'Condition', v: 'Pre-owned · Excellent', s: 'Rule' },
    { k: 'Material', v: 'Waxed cotton', s: 'AI' },
    { k: 'Style', v: 'Field jacket', s: 'Manual' },
];
</script>

<template>
    <!-- the eBay listing being built -->
    <div class="absolute left-[50cqw] top-[5.5cqw] w-[46cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="ebay.co.uk · create listing">
            <div class="p-[1.6cqw]">
                <div class="flex gap-[1.4cqw]">
                    <!-- the photo, cut out onto white -->
                    <div class="grid h-[11cqw] w-[11cqw] shrink-0 place-items-center rounded-[0.9cqw] border border-black/[.08] bg-white">
                        <svg viewBox="0 0 100 100" class="h-[86%] w-[86%]" aria-hidden="true">
                            <path d="M38 12 50 18 62 12 80 22 90 70 78 73 72 46 72 90H28V46l-6 27-12-3 10-48z" fill="#5f6b3a" />
                            <path d="M38 12 50 30 62 12" fill="none" stroke="#8a5a2b" stroke-width="5" stroke-linejoin="round" />
                            <path d="M50 30v60" stroke="#3f4826" stroke-width="1.6" />
                            <rect x="33" y="58" width="12" height="9" rx="1" fill="#4c562d" /><rect x="55" y="58" width="12" height="9" rx="1" fill="#4c562d" />
                            <circle cx="50" cy="44" r="1.4" fill="#2a2f18" /><circle cx="50" cy="56" r="1.4" fill="#2a2f18" /><circle cx="50" cy="68" r="1.4" fill="#2a2f18" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1 leading-tight">
                        <p class="text-[1.9cqw] font-black tracking-[-0.04em]">
                            <span class="text-[#e53238]">e</span><span class="text-[#0064d2]">b</span><span class="text-[#f5af02]">a</span><span class="text-[#86b817]">y</span>
                        </p>
                        <p class="mt-[0.5cqw] text-[1.45cqw] font-semibold">Women’s waxed field jacket, olive, UK 12</p>
                        <p class="mt-[0.8cqw] flex items-baseline gap-[0.8cqw]">
                            <span class="text-[2.2cqw] font-bold">£64.00</span>
                            <span class="text-[1.15cqw] text-black/45">Buy it now</span>
                        </p>
                    </div>
                </div>

                <p class="mt-[1.4cqw] text-[1.1cqw] font-semibold uppercase tracking-[0.08em] text-black/40">Item specifics</p>
                <div class="mt-[0.6cqw] divide-y divide-black/[.06] rounded-[0.9cqw] border border-black/[.08]">
                    <div v-for="r in ROWS" :key="r.k" class="flex items-center gap-[1cqw] px-[1.1cqw] py-[0.65cqw] text-[1.3cqw]">
                        <span class="w-[9cqw] shrink-0 text-black/50">{{ r.k }}</span>
                        <span class="min-w-0 flex-1 truncate font-semibold">{{ r.v }}</span>
                        <span class="rounded-full px-[0.8cqw] py-[0.15cqw] text-[1.05cqw] font-semibold" :class="SRC[r.s]">{{ r.s }}</span>
                    </div>
                </div>
            </div>
            <div class="h-[3cqw]"></div>
        </Frame>
        <!-- eBay's checks -->
        <div class="absolute -bottom-[1.2cqw] right-[3cqw] flex items-center gap-[0.8cqw] rounded-full bg-[#30a46c] px-[1.4cqw] py-[0.7cqw] text-[1.35cqw] font-bold text-white shadow-[0_1cqw_2.4cqw_rgb(0_0_0/.4)]">
            <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw]" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
            Passes eBay checks
        </div>
    </div>

    <!-- the Vinted item it came from -->
    <div class="absolute bottom-[7.5cqw] left-[29cqw] w-[16cqw] -rotate-[4deg] overflow-hidden rounded-[1.4cqw] bg-white text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <div class="relative h-[13cqw] bg-gradient-to-br from-[#d9cdb8] to-[#a8957a]">
            <svg viewBox="0 0 100 100" class="absolute inset-x-[12%] bottom-0 h-[92%] w-[76%]" aria-hidden="true">
                <path d="M50 4v8" stroke="#6b5b45" stroke-width="2" />
                <path d="M30 14h40" stroke="#6b5b45" stroke-width="2.4" stroke-linecap="round" />
                <path d="M38 14 50 20 62 14 80 24 90 72 78 75 72 48 72 100H28V48l-6 27-12-3 10-48z" fill="#5f6b3a" />
                <path d="M38 14 50 32 62 14" fill="none" stroke="#8a5a2b" stroke-width="5" stroke-linejoin="round" />
                <path d="M50 32v68" stroke="#3f4826" stroke-width="1.6" />
            </svg>
            <span class="absolute left-[0.8cqw] top-[0.8cqw] rounded-full bg-white/90 px-[0.8cqw] py-[0.2cqw] text-[1.05cqw] font-bold text-[#09b1ba]">Vinted</span>
        </div>
        <div class="px-[1.2cqw] py-[1cqw] leading-tight">
            <p class="text-[1.8cqw] font-bold">£38.00</p>
            <p class="mt-[0.3cqw] truncate text-[1.15cqw] text-black/50">UK 12 · Very good · Waxed</p>
        </div>
    </div>

    <!-- link → listing -->
    <svg class="absolute bottom-[23cqw] left-[44cqw] h-[7cqw] w-[7.5cqw] overflow-visible" viewBox="0 0 90 80" fill="none" aria-hidden="true">
        <path d="M4 74C20 40 44 20 78 14" stroke="#3ddc97" stroke-width="2.6" stroke-dasharray="6 5" stroke-linecap="round" />
        <path d="M68 6l12 8-10 10" stroke="#3ddc97" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
</template>

<script setup>
// ShopSync SEO: a messy eBay listing title goes in, an AI-written SEO title/description comes out
// on Shopify (shown as the search snippet it earns), and failures wait for a one-click retry.
import Frame from '../Frame.vue';
import { logo, useInk } from '../../logos.js';

const shopify = logo('shopify');
const openai = logo('openai');
const ink = (l) => (useInk(l.hex) ? '#14161a' : l.hex);
</script>

<template>
    <!-- the published product, as it shows up in search -->
    <div class="absolute right-[-3cqw] top-[5.5cqw] w-[53cqw] rotate-[2deg]">
        <Frame kind="browser" url="search · industrial gear pump 12cc">
            <div class="px-[2.6cqw] pb-[2.4cqw] pt-[2cqw]">
                <div class="flex items-center gap-[1cqw] rounded-full border border-black/10 px-[1.6cqw] py-[0.8cqw] text-[1.35cqw] text-black/70 shadow-[0_0.3cqw_0.8cqw_rgb(0_0_0/.06)]">
                    <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw] text-black/40" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><circle cx="10.5" cy="10.5" r="6.5" /><path d="m20 20-4.8-4.8" /></svg>
                    industrial gear pump 12cc
                </div>

                <div class="relative mt-[2cqw] rounded-[1.2cqw] bg-[#fff7f1] p-[1.6cqw] ring-1 ring-[#ff915e]/40">
                    <span class="absolute -top-[1.1cqw] right-[1.4cqw] flex items-center gap-[0.5cqw] rounded-full bg-[#14161a] px-[1cqw] py-[0.35cqw] text-[1.1cqw] font-semibold text-white">
                        <svg viewBox="0 0 24 24" class="h-[1.2cqw] w-[1.2cqw]" fill="#fff"><path :d="openai.path" /></svg>
                        SEO by AI
                    </span>
                    <div class="flex items-center gap-[0.9cqw]">
                        <span class="grid h-[2.4cqw] w-[2.4cqw] place-items-center rounded-full bg-[#f1f3ef]">
                            <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw]" :fill="ink(shopify)"><path :d="shopify.path" /></svg>
                        </span>
                        <span class="leading-tight">
                            <span class="block text-[1.25cqw] font-medium text-black/80">Parts Depot</span>
                            <span class="block text-[1.1cqw] text-black/45">partsdepot.shop › products › gear-pump-12cc</span>
                        </span>
                    </div>
                    <p class="mt-[0.9cqw] text-[1.75cqw] font-medium leading-snug text-[#1a0dab]">Hydraulic Gear Pump 12 cc/rev, SAE A Flange – New OEM</p>
                    <p class="mt-[0.5cqw] text-[1.25cqw] leading-snug text-black/60">Replacement 12 cc/rev aluminium gear pump with a 2-bolt SAE A flange and 9T spline shaft. Rated to 250 bar. New, boxed, ships in 24 h.</p>
                    <p class="mt-[0.8cqw] flex gap-[1.2cqw] text-[1.15cqw] text-black/55">
                        <span><b class="font-semibold text-black/75">€189.00</b> · In stock</span>
                        <span class="text-[#e8a400]">★★★★★</span>
                    </p>
                </div>

                <div class="mt-[1.6cqw] space-y-[0.7cqw] opacity-60">
                    <span class="block h-[1cqw] w-[38%] rounded-full bg-[#1a0dab]/25"></span>
                    <span class="block h-[0.8cqw] w-[82%] rounded-full bg-black/10"></span>
                    <span class="block h-[0.8cqw] w-[66%] rounded-full bg-black/10"></span>
                </div>
            </div>
        </Frame>
    </div>

    <!-- what came in from eBay -->
    <div class="absolute left-[5cqw] top-[27.5cqw] w-[33cqw] -rotate-[2deg] rounded-[1.4cqw] bg-white p-[1.5cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <div class="flex items-center justify-between">
            <span class="text-[1.9cqw] font-extrabold leading-none tracking-[-0.06em]"><span class="text-[#e53238]">e</span><span class="text-[#0064d2]">b</span><span class="text-[#f5af02]">a</span><span class="text-[#86b817]">y</span></span>
            <span class="text-[1.1cqw] font-medium text-black/45">new listing · 09:00</span>
        </div>
        <p class="mt-[1cqw] font-mono text-[1.2cqw] font-semibold leading-snug text-black/75">HYDRAULIC GEAR PUMP 12CC P/N 0510-625-024 *NEW* OEM !!! L@@K FAST SHIP</p>
    </div>

    <!-- arrow: eBay → AI → Shopify -->
    <svg class="absolute left-[38.5cqw] top-[29cqw] h-[6cqw] w-[9cqw] text-white/60" viewBox="0 0 90 60" fill="none" aria-hidden="true">
        <path d="M2 40 C 30 40, 50 30, 82 10" stroke="currentColor" stroke-width="2.4" stroke-dasharray="5 5" stroke-linecap="round" />
        <path d="M70 8 L84 9 L79 22" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
    </svg>

    <!-- failures wait for a retry -->
    <div class="absolute bottom-[4cqw] right-[5cqw] flex -rotate-[1deg] items-center gap-[1.2cqw] rounded-[1.3cqw] bg-white py-[1cqw] pl-[1.2cqw] pr-[1cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <span class="rounded-full bg-[#fee2e2] px-[0.9cqw] py-[0.3cqw] text-[1.15cqw] font-semibold text-[#b91c1c]">Failed</span>
        <span class="leading-tight">
            <span class="block text-[1.3cqw] font-semibold">Pressure Valve 3/8 BSP</span>
            <span class="block text-[1.1cqw] text-black/50">OpenAI timeout · 2 min ago</span>
        </span>
        <span class="ml-[0.8cqw] flex items-center gap-[0.5cqw] rounded-[0.8cqw] bg-[#f59e0b] px-[1.2cqw] py-[0.6cqw] text-[1.2cqw] font-semibold text-white">
            <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 11a8 8 0 1 0-2.3 5.7" /><path d="M20 4v7h-7" /></svg>
            Retry
        </span>
    </div>
</template>

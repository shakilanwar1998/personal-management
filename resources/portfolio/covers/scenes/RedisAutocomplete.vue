<script setup>
// Redis Autocomplete: a search box answering as you type, straight from a Redis FT index.
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const redis = logo('redis');
const Q = 'lapt';
const HITS = [
    { name: 'Laptop stand, aluminium', cat: 'Desk' },
    { name: 'Laptop sleeve 14″, grey', cat: 'Bags' },
    { name: 'Gaming laptop 16 GB', cat: 'Computers' },
    { name: 'Laptop cooling pad', cat: 'Accessories' },
    { name: 'Slim laptop backpack', cat: 'Bags' },
];
// split a name around the matched prefix of a word
const parts = (s) => {
    const i = s.toLowerCase().indexOf(Q);
    return [s.slice(0, i), s.slice(i, i + Q.length), s.slice(i + Q.length)];
};
</script>

<template>
    <!-- the site's search -->
    <div class="absolute right-[-3cqw] top-[5cqw] w-[51cqw] rotate-[2deg]">
        <Frame kind="browser" url="shop.example.com">
            <div class="flex items-center gap-[1.4cqw] px-[2.6cqw] pt-[1.8cqw]">
                <span class="h-[2.2cqw] w-[2.2cqw] rounded-[0.6cqw] bg-[#ff4438]/80"></span>
                <span class="h-[1cqw] w-[8cqw] rounded-full bg-black/15"></span>
                <span class="ml-auto h-[0.9cqw] w-[5cqw] rounded-full bg-black/10"></span>
                <span class="h-[0.9cqw] w-[5cqw] rounded-full bg-black/10"></span>
            </div>
            <div class="px-[2.6cqw] pb-[3.4cqw] pt-[2cqw]">
                <div class="flex items-center gap-[1cqw] rounded-[1.2cqw] border-[0.2cqw] border-[#ff4438] bg-white px-[1.6cqw] py-[1cqw] text-[1.8cqw] shadow-[0_0_0_0.6cqw_rgb(255_68_56/.12)]">
                    <svg viewBox="0 0 24 24" class="h-[2cqw] w-[2cqw] text-black/40" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><circle cx="10.5" cy="10.5" r="6.5" /><path d="m20 20-4.8-4.8" /></svg>
                    <span class="font-medium">{{ Q }}</span><span class="-ml-[0.8cqw] h-[2.2cqw] w-[0.18cqw] bg-[#14161a]"></span>
                </div>
                <div class="mt-[0.8cqw] overflow-hidden rounded-[1.2cqw] border border-black/[.08] bg-white shadow-[0_1.2cqw_3cqw_rgb(0_0_0/.14)]">
                    <div
                        v-for="(h, i) in HITS"
                        :key="h.name"
                        class="flex items-center gap-[1.2cqw] px-[1.6cqw] py-[0.95cqw] text-[1.45cqw]"
                        :class="i === 0 ? 'bg-[#fff1f0]' : 'border-t border-black/[.05]'"
                    >
                        <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw] shrink-0 text-black/30" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="10.5" cy="10.5" r="6.5" /><path d="m20 20-4.8-4.8" /></svg>
                        <span class="truncate text-black/70">{{ parts(h.name)[0] }}<mark class="rounded-[0.3cqw] bg-[#ffd9d6] px-[0.1cqw] font-bold text-[#14161a]">{{ parts(h.name)[1] }}</mark>{{ parts(h.name)[2] }}</span>
                        <span class="ml-auto shrink-0 text-[1.15cqw] text-black/40">{{ h.cat }}</span>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- the query behind it -->
    <div class="absolute bottom-[9cqw] left-[24cqw] flex -rotate-[2deg] items-center gap-[1.3cqw] rounded-[1.4cqw] bg-[#14161a] px-[1.6cqw] py-[1.2cqw] text-white shadow-[0_1.8cqw_4.5cqw_rgb(0_0_0/.55)] ring-1 ring-white/10">
        <span class="grid h-[3.6cqw] w-[3.6cqw] place-items-center rounded-[0.9cqw] bg-white">
            <svg viewBox="0 0 24 24" class="h-[65%] w-[65%]" :fill="redis.hex"><path :d="redis.path" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block font-mono text-[1.25cqw] text-white/70"><span class="font-semibold text-[#ff8a80]">FT.SEARCH</span> idx:products "@name:{{ Q }}*"</span>
            <span class="mt-[0.4cqw] block text-[1.2cqw] text-white/45">5 suggestions · prefix match</span>
        </span>
        <span class="ml-[0.4cqw] rounded-full bg-[#22c55e] px-[1.1cqw] py-[0.4cqw] text-[1.45cqw] font-bold text-[#052e16]">3 ms</span>
    </div>
</template>

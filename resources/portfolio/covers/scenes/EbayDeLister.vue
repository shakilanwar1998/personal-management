<script setup>
// eBay.de Auto Lister: the team's Airtable inventory with a "List on eBay" button,
// and the live eBay.de listing it produces (category, item specifics, policies).
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const AIRTABLE = logo('airtable');
const EBAY = logo('ebay');

const ROWS = [
    { item: 'Stereo amplifier, 2×60 W', cond: 'Used', price: '€289', status: 'ready', hue: 'from-[#cbd5e1] to-[#64748b]' },
    { item: 'Belt-drive turntable', cond: 'Used', price: '€349', status: 'listed', hue: 'from-[#e7d3b5] to-[#8a6a42]' },
    { item: 'Cassette deck, 3-head', cond: 'Serviced', price: '€219', status: 'listed', hue: 'from-[#d4d4d8] to-[#3f3f46]' },
    { item: 'AM/FM tuner', cond: 'Used', price: '€129', status: 'draft', hue: 'from-[#e2e8f0] to-[#94a3b8]' },
];
const STATUS = {
    ready: 'bg-[#fef3c7] text-[#92400e]',
    listed: 'bg-[#dcfce7] text-[#166534]',
    draft: 'bg-black/[.06] text-black/50',
};
</script>

<template>
    <!-- Airtable grid -->
    <div class="absolute right-[-2cqw] top-[5cqw] w-[53cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="airtable.com · Hi-Fi inventory">
            <div class="flex items-center gap-[0.9cqw] bg-[#1d4ed8] px-[1.6cqw] py-[0.9cqw] text-white">
                <svg viewBox="0 0 24 24" class="h-[1.9cqw] w-[1.9cqw]" fill="#fff"><path :d="AIRTABLE.path" /></svg>
                <span class="text-[1.4cqw] font-semibold">Hi-Fi inventory</span>
                <span class="ml-[1cqw] rounded-[0.4cqw] bg-white/20 px-[0.8cqw] py-[0.2cqw] text-[1.05cqw]">Grid view</span>
            </div>
            <div class="text-[1.15cqw]">
                <div class="grid grid-cols-[2.4cqw_3.4cqw_1fr_7cqw_5.6cqw_6.6cqw_10.5cqw] items-center border-b border-black/10 bg-[#f7f8fa] px-[1cqw] py-[0.55cqw] text-[1cqw] font-semibold text-black/50">
                    <span></span><span>Photo</span><span>Item</span><span>Condition</span><span>Price</span><span>Status</span><span>eBay</span>
                </div>
                <div v-for="(r, i) in ROWS" :key="i" class="grid grid-cols-[2.4cqw_3.4cqw_1fr_7cqw_5.6cqw_6.6cqw_10.5cqw] items-center border-b border-black/[.06] px-[1cqw] py-[0.55cqw]" :class="i === 0 ? 'bg-[#eff6ff]' : ''">
                    <span class="text-[0.95cqw] text-black/35">{{ i + 1 }}</span>
                    <span class="block h-[2.4cqw] w-[2.4cqw] rounded-[0.35cqw] bg-gradient-to-br" :class="r.hue"></span>
                    <span class="truncate pr-[0.6cqw] font-medium">{{ r.item }}</span>
                    <span class="text-black/55">{{ r.cond }}</span>
                    <span class="tabular-nums">{{ r.price }}</span>
                    <span><span class="rounded-full px-[0.7cqw] py-[0.15cqw] text-[0.95cqw] font-semibold capitalize" :class="STATUS[r.status]">{{ r.status }}</span></span>
                    <span v-if="i === 0" class="relative">
                        <span class="inline-block rounded-[0.5cqw] bg-[#2563eb] px-[0.9cqw] py-[0.35cqw] text-[1cqw] font-semibold text-white shadow-[0_0.4cqw_1cqw_rgb(37_99_235/.4)]">List on eBay</span>
                        <svg viewBox="0 0 24 24" class="absolute left-[6.2cqw] top-[1.3cqw] h-[2.2cqw] w-[2.2cqw] drop-shadow-[0_0.2cqw_0.3cqw_rgb(0_0_0/.35)]"><path d="M5 3l14 8-6 1.5L10 19z" fill="#111" stroke="#fff" stroke-width="1.5" stroke-linejoin="round" /></svg>
                    </span>
                    <span v-else class="font-mono text-[0.9cqw] text-black/35">{{ r.status === 'listed' ? '#1567…' + (30 + i * 7) : '—' }}</span>
                </div>
                <div class="h-[2.4cqw]"></div>
            </div>
        </Frame>
    </div>

    <!-- the live eBay.de listing -->
    <div class="absolute left-[38cqw] top-[29cqw] w-[31cqw] rotate-[2deg] overflow-hidden rounded-[1.4cqw] bg-white text-[#14161a] shadow-[0_1.8cqw_4.4cqw_rgb(0_0_0/.5)]">
        <div class="flex items-center gap-[0.8cqw] border-b border-black/[.07] px-[1.4cqw] py-[0.6cqw]">
            <svg viewBox="0 0 24 24" class="h-[2.6cqw] w-[2.6cqw]" :fill="EBAY.hex"><path :d="EBAY.path" /></svg>
            <span class="text-[1.05cqw] text-black/45">Audio &amp; Hi-Fi › Verstärker</span>
            <span class="ml-auto flex items-center gap-[0.4cqw] rounded-full bg-[#dcfce7] px-[0.8cqw] py-[0.15cqw] text-[0.95cqw] font-semibold text-[#166534]"><span class="h-[0.7cqw] w-[0.7cqw] rounded-full bg-[#16a34a]"></span>live</span>
        </div>
        <div class="flex gap-[1.2cqw] px-[1.4cqw] py-[1.1cqw]">
            <!-- amp sketch -->
            <div class="grid h-[8.6cqw] w-[9cqw] shrink-0 place-items-center rounded-[0.8cqw] bg-[#f1f3f6]">
                <svg viewBox="0 0 60 36" class="w-[88%]" aria-hidden="true">
                    <rect x="2" y="6" width="56" height="24" rx="2" fill="#cfd5dd" stroke="#8b95a3" stroke-width="1" />
                    <rect x="2" y="6" width="56" height="4" fill="#aeb6c1" />
                    <rect x="7" y="13" width="11" height="7" rx="1" fill="#fde68a" stroke="#8b95a3" stroke-width=".6" />
                    <rect x="20" y="13" width="11" height="7" rx="1" fill="#fde68a" stroke="#8b95a3" stroke-width=".6" />
                    <path d="M9.5 19 13 15M22.5 19 26 15.5" stroke="#b91c1c" stroke-width=".7" />
                    <circle cx="40" cy="19" r="5" fill="#e5e7eb" stroke="#6b7280" stroke-width=".8" />
                    <circle cx="51" cy="19" r="3.5" fill="#e5e7eb" stroke="#6b7280" stroke-width=".8" />
                    <circle cx="9" cy="25" r="1" fill="#6b7280" /><circle cx="14" cy="25" r="1" fill="#6b7280" /><circle cx="19" cy="25" r="1" fill="#6b7280" />
                    <rect x="6" y="30" width="5" height="2" fill="#6b7280" /><rect x="49" y="30" width="5" height="2" fill="#6b7280" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-[1.3cqw] font-semibold">Vintage Verstärker 2×60 W</p>
                <p class="mt-[0.2cqw] whitespace-nowrap text-[1.8cqw] font-bold">EUR 289,00</p>
                <p class="whitespace-nowrap text-[1cqw] text-black/50">Versand: EUR 6,99 · 30 Tage Rücknahme</p>
                <p class="mt-[0.3cqw] text-[1cqw] text-black/55"><span class="text-black/40">Zustand:</span> Gebraucht · <span class="text-black/40">Leistung:</span> 2×60 W</p>
                <span class="mt-[0.7cqw] block rounded-full bg-[#3665f3] py-[0.45cqw] text-center text-[1.15cqw] font-semibold text-white">Sofort-Kaufen</span>
            </div>
        </div>
    </div>
</template>

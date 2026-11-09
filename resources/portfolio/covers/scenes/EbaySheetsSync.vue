<script setup>
// eBay Sheets Sync: eBay orders mirrored into a sheet. One column per item specific,
// stock down / sold up on every paid order, and the resumable 60-day-window backfill.
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const EBAY = logo('ebay');
const SHEETS = logo('sheets');
const AIRTABLE = logo('airtable');

const COLS = ['Order ID', 'Item', 'Qty', 'Stock', 'Sold', 'Material', 'Size', 'Colour'];
const SPECIFIC = new Set([5, 6, 7]); // item-specific columns
const ROWS = [
    ['14-11203-48291', 'Ceramic mug set', '1', '3', '12', 'Ceramic', '350 ml', 'Sage', true],
    ['27-11198-30554', 'Wool beanie', '2', '18', '41', 'Merino', 'M', 'Navy'],
    ['09-11187-62013', 'Canvas tote bag', '1', '25', '67', 'Cotton', '38 cm', 'Natural'],
    ['22-11176-11840', 'Desk lamp', '1', '6', '9', 'Brass', '45 cm', 'Gold'],
    ['14-11169-90372', 'Linen napkins', '1', '14', '23', 'Linen', '45 cm', 'Oat'],
    ['31-11152-47716', 'Card holder', '3', '9', '58', 'Leather', 'Slim', 'Tan'],
];
const LETTERS = 'ABCDEFGH'.split('');
const GRID = 'grid-cols-[2cqw_9.4cqw_10.2cqw_3.6cqw_4.6cqw_4.2cqw_6cqw_5cqw_5.6cqw]';
const WINDOWS = 13; // ~2 years in 60-day windows
</script>

<template>
    <div class="absolute right-[-1.5cqw] top-[5cqw] w-[53cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="Sheets · eBay orders">
            <!-- toolbar with sync badges -->
            <div class="flex items-center gap-[0.8cqw] px-[1.4cqw] py-[0.9cqw]">
                <svg viewBox="0 0 24 24" class="h-[2.2cqw] w-[2.2cqw]" :fill="SHEETS.hex"><path :d="SHEETS.path" /></svg>
                <span class="text-[1.4cqw] font-semibold">eBay orders</span>
                <span class="ml-[1cqw] flex items-center gap-[0.5cqw] rounded-full bg-[#f1f5f9] px-[0.8cqw] py-[0.3cqw] text-[1cqw] font-semibold text-black/60 ring-1 ring-black/[.06]">
                    <svg viewBox="0 0 24 24" class="h-[1.9cqw] w-[1.9cqw]" :fill="EBAY.hex"><path :d="EBAY.path" /></svg>
                    <span class="text-black/35">⇄</span>
                    <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" :fill="AIRTABLE.hex"><path :d="AIRTABLE.path" /></svg>
                    <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" :fill="SHEETS.hex"><path :d="SHEETS.path" /></svg>
                    <span class="ml-[0.2cqw] h-[0.7cqw] w-[0.7cqw] rounded-full bg-[#16a34a]"></span>
                    synced 2 min ago
                </span>
            </div>
            <!-- sheet -->
            <div class="border-t border-black/10 font-[system-ui] text-[1.05cqw]">
                <div class="grid border-b border-black/10 bg-[#f8f9fa] text-center text-[0.9cqw] text-black/45" :class="GRID">
                    <span class="border-r border-black/10"></span>
                    <span v-for="(l, i) in LETTERS" :key="l" class="border-r border-black/10 py-[0.2cqw]" :class="SPECIFIC.has(i) ? 'bg-[#fef3c7] text-[#92400e]' : ''">{{ l }}</span>
                </div>
                <div class="grid border-b border-black/10 font-semibold" :class="GRID">
                    <span class="border-r border-black/10 bg-[#f8f9fa] py-[0.45cqw] text-center text-[0.9cqw] font-normal text-black/45">1</span>
                    <span v-for="(c, i) in COLS" :key="c" class="truncate border-r border-black/10 px-[0.5cqw] py-[0.45cqw]" :class="SPECIFIC.has(i) ? 'bg-[#fffbeb] text-[#92400e]' : ''">{{ c }}</span>
                </div>
                <div v-for="(r, ri) in ROWS" :key="r[0]" class="grid border-b border-black/[.07]" :class="[GRID, r[8] ? 'bg-[#f0fdf4]' : '']">
                    <span class="border-r border-black/10 bg-[#f8f9fa] py-[0.45cqw] text-center text-[0.9cqw] text-black/45">{{ ri + 2 }}</span>
                    <span class="truncate border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw] font-mono text-[0.85cqw] text-black/60">{{ r[0] }}</span>
                    <span class="truncate border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw]">{{ r[1] }}</span>
                    <span class="border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw] text-right tabular-nums">{{ r[2] }}</span>
                    <span class="border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw] text-right tabular-nums" :class="r[8] ? 'font-bold text-[#b91c1c]' : ''">{{ r[8] ? '↓ ' : '' }}{{ r[3] }}</span>
                    <span class="border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw] text-right tabular-nums" :class="r[8] ? 'font-bold text-[#15803d]' : ''">{{ r[8] ? '↑ ' : '' }}{{ r[4] }}</span>
                    <span v-for="k in [5, 6, 7]" :key="k" class="truncate border-r border-black/[.07] px-[0.5cqw] py-[0.45cqw] text-black/70">{{ r[k] }}</span>
                </div>
                <div class="h-[5cqw]"></div>
            </div>
        </Frame>
    </div>

    <!-- resumable backfill -->
    <div class="absolute left-[33cqw] top-[35cqw] w-[24cqw] -rotate-[2deg] rounded-[1.5cqw] bg-white p-[1.4cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.5)]">
        <p class="flex items-center gap-[0.7cqw]">
            <span class="grid h-[2.6cqw] w-[2.6cqw] place-items-center rounded-full bg-[#d97706] text-white">
                <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
            </span>
            <span class="leading-tight">
                <span class="block text-[1.5cqw] font-bold">Backfill complete</span>
                <span class="block text-[1.1cqw] text-black/50">2,278 orders · 60-day windows</span>
            </span>
        </p>
        <div class="mt-[1cqw] flex gap-[0.3cqw]">
            <span v-for="i in WINDOWS" :key="i" class="h-[0.9cqw] flex-1 rounded-[0.25cqw] bg-[#f59e0b]" :style="{ opacity: 0.45 + (i / WINDOWS) * 0.55 }"></span>
        </div>
        <p class="mt-[0.6cqw] flex justify-between text-[0.95cqw] text-black/45"><span>~2 yrs ago</span><span>skips cancelled · de-duped</span></p>
    </div>
</template>

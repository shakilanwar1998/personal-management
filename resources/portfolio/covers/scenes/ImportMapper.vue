<script setup>
// Private Import Mapper: the CSV importer's column mapping — real values on the left, the masked
// shape that is all the model ever sees in the middle, and the CRM field it suggests on the right.
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const claude = logo('claude');
const ROWS = [
    { col: 'col_1', real: 'Lena Fischer', mask: 'Aaaa Aaaaaaa', field: 'Full name' },
    { col: 'col_2', real: 'lena@example.com', mask: 'aaaa@aaaaaaa.aaa', field: 'Email' },
    { col: 'col_3', real: '+49 151 2345 6789', mask: '+99 999 9999 9999', field: 'Phone' },
    { col: 'col_4', real: '2026-03-14', mask: '9999-99-99', field: 'Signup date' },
];
</script>

<template>
    <div class="absolute left-[45.5cqw] top-[5.5cqw] w-[51cqw] rotate-[1.5deg]">
        <Frame kind="window" url="Import contacts · contacts.csv">
            <div class="p-[1.6cqw]">
                <div class="grid grid-cols-[1fr_1fr_1.5cqw_0.8fr] items-center gap-x-[1cqw] text-[1.05cqw] font-semibold uppercase tracking-[0.06em] text-black/40">
                    <span>Your file</span>
                    <span class="flex items-center gap-[0.5cqw] text-[#b85c3c]">
                        <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw] shrink-0" :fill="claude.hex"><path :d="claude.path" /></svg>
                        What the AI sees
                    </span>
                    <span></span>
                    <span>CRM field</span>
                </div>
                <div class="mt-[0.9cqw] space-y-[0.7cqw]">
                    <div v-for="r in ROWS" :key="r.col" class="grid grid-cols-[1fr_1fr_1.5cqw_0.8fr] items-center gap-x-[1cqw]">
                        <span class="min-w-0 rounded-[0.8cqw] border border-black/[.08] px-[1cqw] py-[0.6cqw] leading-tight">
                            <span class="block font-mono text-[0.95cqw] text-black/35">{{ r.col }}</span>
                            <span class="block truncate text-[1.3cqw] font-semibold">{{ r.real }}</span>
                        </span>
                        <span class="min-w-0 rounded-[0.8cqw] bg-[#14161a] px-[1cqw] py-[0.6cqw] leading-tight text-white">
                            <span class="block font-mono text-[0.95cqw] text-white/40">{{ r.col }}</span>
                            <span class="block truncate font-mono text-[1.2cqw] font-medium text-[#f0b59c]">{{ r.mask }}</span>
                        </span>
                        <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw] text-black/35" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h15M13 6l6 6-6 6" /></svg>
                        <span class="flex min-w-0 items-center gap-[0.5cqw] rounded-full bg-[#e8eef7] px-[1cqw] py-[0.55cqw] text-[1.2cqw] font-semibold text-[#334155]">
                            <svg viewBox="0 0 24 24" class="h-[1.2cqw] w-[1.2cqw] shrink-0 text-[#30a46c]" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                            <span class="truncate">{{ r.field }}</span>
                        </span>
                    </div>
                </div>
                <div class="mt-[1.4cqw] flex items-center justify-end gap-[1cqw]">
                    <span class="text-[1.1cqw] text-black/45">You confirm every mapping</span>
                    <span class="rounded-[0.7cqw] bg-[#334155] px-[1.4cqw] py-[0.6cqw] text-[1.2cqw] font-semibold text-white">Import 1,240 rows</span>
                </div>
            </div>
            <div class="h-[2.4cqw]"></div>
        </Frame>
    </div>

    <!-- the guarantee -->
    <div class="absolute bottom-[8cqw] left-[27cqw] flex -rotate-[2deg] items-center gap-[1.2cqw] rounded-[1.6cqw] bg-white px-[1.6cqw] py-[1.2cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <span class="grid h-[3.6cqw] w-[3.6cqw] place-items-center rounded-full bg-[#334155] text-white">
            <svg viewBox="0 0 24 24" class="h-[58%] w-[58%]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4.5 6v5.5c0 4.6 3.2 8.4 7.5 9.5 4.3-1.1 7.5-4.9 7.5-9.5V6z" /><path d="m8.8 12 2.3 2.3 4.2-4.6" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-[1.6cqw] font-bold">Real values never leave the server</span>
            <span class="block text-[1.25cqw] text-black/50">The model only sees the shape of each column</span>
        </span>
    </div>
</template>

<script setup>
// Early work: the first projects, 2019 → 2021, climbing a dotted timeline: a PHP student portal,
// a travel guide (places by country and city, with reviews), the Shikhun e-learning back end,
// and an Ionic pharmacy app. All data is illustrative.
import Frame from '../Frame.vue';

const ROLLS = [
    { r: 'Roll 01', p: [1, 1, 1, 0, 1] },
    { r: 'Roll 02', p: [1, 0, 1, 1, 1] },
    { r: 'Roll 03', p: [1, 1, 1, 1, 1] },
];
const PLACES = [
    { name: 'Tea gardens', city: 'Sylhet', stars: 5, rev: 24, from: '#7fb69a', to: '#2f6f55' },
    { name: 'Old fort', city: 'Dhaka', stars: 4, rev: 17, from: '#e0c38c', to: '#9c6b2f' },
];
const MEDS = [
    { n: 'Paracetamol', p: '$2.40' },
    { n: 'Vitamin C', p: '$4.10' },
    { n: 'Antacid', p: '$3.75' },
];
</script>

<template>
    <!-- the dotted timeline, behind the windows -->
    <svg class="absolute left-0 top-0 h-[56.25cqw] w-[100cqw]" viewBox="0 0 100 56.25" fill="none" aria-hidden="true">
        <path d="M29 28.5 C 36 22, 42 19, 49.5 15.5 S 64 6, 73.5 3.2" stroke="#ffba52" stroke-opacity=".7" stroke-width=".35" stroke-dasharray="1 1" stroke-linecap="round" />
    </svg>

    <!-- 2021 · Shikhun e-learning -->
    <div class="absolute left-[71cqw] top-[4.5cqw] w-[25cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="shikhun · courses">
            <div class="p-[1.3cqw]">
                <div class="flex items-center gap-[1cqw] rounded-[0.9cqw] border border-black/[.07] p-[0.9cqw]">
                    <span class="grid h-[3.6cqw] w-[4.8cqw] shrink-0 place-items-center rounded-[0.6cqw] bg-gradient-to-br from-[#38bdf8] to-[#1d4ed8] text-white">
                        <svg viewBox="0 0 24 24" class="h-[50%] w-[50%]" fill="currentColor"><path d="M8 5v14l11-7z" /></svg>
                    </span>
                    <span class="min-w-0 flex-1 leading-tight">
                        <span class="block truncate text-[1.2cqw] font-semibold">Physics for beginners</span>
                        <span class="block text-[0.95cqw] text-black/45">Lesson 7 of 12</span>
                        <span class="mt-[0.4cqw] block h-[0.5cqw] overflow-hidden rounded-full bg-black/[.07]"><span class="block h-full w-[58%] rounded-full bg-[#1d4ed8]"></span></span>
                    </span>
                </div>
                <div class="mt-[0.8cqw] flex gap-[0.7cqw]">
                    <span class="flex flex-1 items-center gap-[0.5cqw] rounded-[0.7cqw] bg-[#e5484d]/[.08] px-[0.8cqw] py-[0.5cqw] text-[1cqw] font-semibold text-[#c2262b]">
                        <span class="h-[0.8cqw] w-[0.8cqw] rounded-full bg-[#e5484d]"></span>Live lesson · booked
                    </span>
                    <span class="flex items-center gap-[0.4cqw] rounded-[0.7cqw] bg-[#f59e0b]/[.12] px-[0.8cqw] py-[0.5cqw] text-[1cqw] font-semibold text-[#b45309]">
                        <svg viewBox="0 0 24 24" class="h-[1.2cqw] w-[1.2cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6" /><path d="m8.5 14-1.5 8 5-3 5 3-1.5-8" /></svg>
                        Certificate
                    </span>
                </div>
            </div>
        </Frame>
        <span class="absolute -left-[1.6cqw] -top-[1.4cqw] rounded-full bg-[#ffba52] px-[1cqw] py-[0.3cqw] text-[1.2cqw] font-bold text-[#3d2c06] shadow-[0_0.5cqw_1.2cqw_rgb(0_0_0/.4)]">2021</span>
    </div>

    <!-- 2020 · travel guide -->
    <div class="absolute left-[48cqw] top-[17cqw] w-[23cqw] rotate-[-1deg]">
        <Frame kind="browser" url="travel guide">
            <div class="p-[1.3cqw]">
                <p class="text-[1.05cqw] text-black/45">Bangladesh <span class="text-black/25">›</span> <span class="font-semibold text-black/70">All cities</span></p>
                <div class="mt-[0.8cqw] grid grid-cols-2 gap-[0.8cqw]">
                    <div v-for="p in PLACES" :key="p.name" class="overflow-hidden rounded-[0.8cqw] border border-black/[.07]">
                        <span class="block h-[4.2cqw]" :style="{ background: `linear-gradient(150deg, ${p.from}, ${p.to})` }"></span>
                        <span class="block px-[0.7cqw] py-[0.6cqw] leading-tight">
                            <span class="block truncate text-[1.1cqw] font-semibold">{{ p.name }}</span>
                            <span class="block text-[0.9cqw] text-black/45">{{ p.city }}</span>
                            <span class="mt-[0.3cqw] flex items-center gap-[0.4cqw] text-[0.9cqw] text-black/45">
                                <span class="tracking-[-0.1em] text-[#f59e0b]">{{ '★'.repeat(p.stars) }}<span class="text-black/15">{{ '★'.repeat(5 - p.stars) }}</span></span>
                                {{ p.rev }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </Frame>
        <span class="absolute -left-[1.6cqw] -top-[1.4cqw] rounded-full bg-[#ffba52] px-[1cqw] py-[0.3cqw] text-[1.2cqw] font-bold text-[#3d2c06] shadow-[0_0.5cqw_1.2cqw_rgb(0_0_0/.4)]">2020</span>
    </div>

    <!-- 2019 · student portal -->
    <div class="absolute left-[27.5cqw] top-[30cqw] w-[20cqw] rotate-[-2.5deg]">
        <Frame kind="window" url="student portal">
            <div class="px-[1.2cqw] pb-[1.2cqw] pt-[0.9cqw]">
                <p class="flex items-center justify-between text-[1.15cqw] font-semibold">
                    Class 8 · Attendance
                    <span class="text-[0.9cqw] font-medium text-black/40">Mon – Fri</span>
                </p>
                <div class="mt-[0.6cqw] space-y-[0.45cqw]">
                    <p v-for="r in ROLLS" :key="r.r" class="flex items-center gap-[0.4cqw] text-[1cqw]">
                        <span class="w-[5cqw] text-black/55">{{ r.r }}</span>
                        <span
                            v-for="(d, i) in r.p"
                            :key="i"
                            class="grid h-[1.6cqw] flex-1 place-items-center rounded-[0.3cqw] text-[0.85cqw] font-bold"
                            :class="d ? 'bg-[#30a46c]/15 text-[#1f7a4d]' : 'bg-[#e5484d]/15 text-[#c2262b]'"
                        >{{ d ? 'P' : 'A' }}</span>
                    </p>
                </div>
            </div>
        </Frame>
        <span class="absolute -left-[1.6cqw] -top-[1.4cqw] rounded-full bg-[#ffba52] px-[1cqw] py-[0.3cqw] text-[1.2cqw] font-bold text-[#3d2c06] shadow-[0_0.5cqw_1.2cqw_rgb(0_0_0/.4)]">2019</span>
    </div>

    <!-- the Ionic pharmacy app -->
    <div class="absolute left-[79.5cqw] top-[30cqw] w-[15cqw] rotate-[4deg]">
        <Frame kind="phone">
            <div class="px-[1.2cqw] pb-[3cqw]">
                <p class="text-[1.3cqw] font-bold">Pharmacy</p>
                <p class="mt-[0.5cqw] flex items-center gap-[0.4cqw] rounded-full bg-black/[.05] px-[0.8cqw] py-[0.35cqw] text-[0.9cqw] text-black/45">
                    <svg viewBox="0 0 24 24" class="h-[1cqw] w-[1cqw]" fill="none" stroke="currentColor" stroke-width="2.6"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                    Search medicine
                </p>
                <div class="mt-[0.6cqw] space-y-[0.45cqw]">
                    <p v-for="m in MEDS" :key="m.n" class="flex items-center gap-[0.5cqw] rounded-[0.6cqw] border border-black/[.07] px-[0.6cqw] py-[0.4cqw] text-[0.9cqw]">
                        <span class="h-[1.6cqw] w-[1.6cqw] shrink-0 rounded-[0.4cqw] bg-[#14b8a6]/20"></span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block truncate font-semibold">{{ m.n }}</span>
                            <span class="block text-black/45">{{ m.p }}</span>
                        </span>
                        <span class="grid h-[1.5cqw] w-[1.5cqw] place-items-center rounded-full bg-[#14b8a6] text-[1.1cqw] leading-none text-white">+</span>
                    </p>
                </div>
            </div>
        </Frame>
        <span class="absolute -left-[1.6cqw] -top-[1.4cqw] rounded-full bg-white px-[1cqw] py-[0.3cqw] text-[1.1cqw] font-bold text-[#3d2c06] shadow-[0_0.5cqw_1.2cqw_rgb(0_0_0/.4)]">Ionic</span>
    </div>
</template>

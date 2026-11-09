<script setup>
// Ghurun: the phone app's district map filling in as you travel, plus the "district complete" moment.
import Frame from '../Frame.vue';
import BrandMark from '../BrandMark.vue';
import { BD_DISTRICTS, BD_VIEWBOX } from '../bdDistricts.js';

const VISITED = new Set(['Dhaka', 'Sylhet', 'Maulvibazar', 'Sunamganj', "Cox's Bazar", 'Bandarban', 'Rangamati', 'Chittagong', 'Dinajpur', 'Rangpur', 'Rajshahi', 'Bogra', 'Khulna', 'Bagerhat']);
const PINS = ['Sylhet', "Cox's Bazar", 'Dinajpur'].map((n) => BD_DISTRICTS.find((d) => d.name === n)?.c).filter(Boolean);
</script>

<template>
    <!-- phone -->
    <div class="absolute right-[8cqw] top-[5cqw] w-[25cqw] rotate-[3deg]">
        <Frame kind="phone">
            <div class="flex items-center gap-[0.8cqw] px-[1.8cqw] pb-[1cqw]">
                <span class="block h-[2.6cqw] w-[2.6cqw]"><BrandMark :brand="{ mark: 'ghurun' }" name="Ghurun" /></span>
                <span class="text-[1.9cqw] font-bold" lang="bn">ঘুরুন</span>
                <span class="ml-auto rounded-full bg-[#006a4e]/10 px-[1cqw] py-[0.3cqw] text-[1.2cqw] font-semibold text-[#006a4e]">14 / 64</span>
            </div>
            <div class="relative mx-[1.2cqw] rounded-[1.6cqw] bg-[#eef4f0] p-[1cqw]">
                <svg :viewBox="BD_VIEWBOX.join(' ')" class="mx-auto block h-[30cqw] w-auto" aria-hidden="true">
                    <path
                        v-for="d in BD_DISTRICTS"
                        :key="d.name"
                        :d="d.d"
                        :fill="VISITED.has(d.name) ? '#006a4e' : '#d6e3da'"
                        stroke="#fff"
                        stroke-width=".45"
                        stroke-linejoin="round"
                    />
                    <g v-for="(c, i) in PINS" :key="i" :transform="`translate(${c[0] - 4.5}, ${c[1] - 10})`">
                        <path d="M4.5 0A4.5 4.5 0 0 0 0 4.5c0 3.2 3.5 6.1 4.1 6.8a.5.5 0 0 0 .8 0C5.5 10.6 9 7.7 9 4.5A4.5 4.5 0 0 0 4.5 0z" fill="#f42a41" stroke="#fff" stroke-width=".8" />
                        <circle cx="4.5" cy="4.5" r="1.6" fill="#fff" />
                    </g>
                </svg>
            </div>
            <div class="mx-[1.2cqw] mt-[1.2cqw] rounded-[1.4cqw] border border-black/[.07] p-[1.2cqw]">
                <p class="flex items-baseline justify-between text-[1.4cqw] font-semibold">Sylhet <span class="text-[1.15cqw] font-medium text-black/45">9 / 14 places</span></p>
                <span class="mt-[0.8cqw] block h-[0.7cqw] overflow-hidden rounded-full bg-black/[.07]"><span class="block h-full w-[64%] rounded-full bg-[#006a4e]"></span></span>
                <span class="mt-[1cqw] flex gap-[0.6cqw]">
                    <span class="h-[4cqw] flex-1 rounded-[0.8cqw] bg-gradient-to-br from-[#7fb69a] to-[#2f6f55]"></span>
                    <span class="h-[4cqw] flex-1 rounded-[0.8cqw] bg-gradient-to-br from-[#9cc3d6] to-[#3b6f8a]"></span>
                    <span class="h-[4cqw] flex-1 rounded-[0.8cqw] bg-gradient-to-br from-[#e0c38c] to-[#9c6b2f]"></span>
                </span>
            </div>
            <div class="h-[4cqw]"></div>
        </Frame>
    </div>

    <!-- the moment a district fills in -->
    <div class="absolute bottom-[7cqw] right-[30cqw] flex -rotate-[2deg] items-center gap-[1.2cqw] rounded-[1.6cqw] bg-white px-[1.6cqw] py-[1.2cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <span class="grid h-[3.6cqw] w-[3.6cqw] place-items-center rounded-full bg-[#006a4e] text-white">
            <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-[1.6cqw] font-bold">Cox’s Bazar complete</span>
            <span class="block text-[1.25cqw] text-black/50" lang="bn">জেলা পূর্ণ · ১৪ / ৬৪</span>
        </span>
    </div>
</template>

<script setup>
// Express: the society's admin dashboard (navigation mirrors the real app; the numbers are illustrative).
import Frame from '../Frame.vue';

const NAV = ['Dashboard', 'Members', 'Capital Deposits', 'Investments', 'Profit & Dividends', 'Fines', 'Ledger', 'Meetings', 'PDF Report Center'];
const CARDS = [
    { label: 'Total capital', value: '৳ 18.4L', note: '+৳ 42,000 this month', tone: '#047857' },
    { label: 'Invested', value: '৳ 12.9L', note: '6 active investments', tone: '#1d4ed8' },
    { label: 'Profit held', value: '৳ 1.36L', note: 'until the year closes', tone: '#b45309' },
];
const MONTHS = [62, 70, 68, 74, 80, 77, 85, 88, 84, 92, 96, 100];
</script>

<template>
    <div class="absolute right-[-4cqw] top-[5cqw] w-[60cqw] rotate-[-1.5deg]">
        <Frame kind="browser" url="Express · Dashboard">
            <div class="flex">
                <!-- sidebar -->
                <div class="w-[14cqw] shrink-0 border-r border-black/[.06] bg-[#f7f8f7] px-[1cqw] py-[1.2cqw]">
                    <p class="mb-[1cqw] flex items-center gap-[0.6cqw] px-[0.6cqw] text-[1.4cqw] font-bold">
                        <span class="grid h-[2.2cqw] w-[2.2cqw] place-items-center rounded-[0.6cqw] bg-gradient-to-br from-[#3ddc97] to-[#047857] text-[0.95cqw] text-white">EX</span>Express
                    </p>
                    <p
                        v-for="(n, i) in NAV"
                        :key="n"
                        class="truncate rounded-[0.6cqw] px-[0.8cqw] py-[0.45cqw] text-[1.1cqw]"
                        :class="i === 0 ? 'bg-[#047857] font-semibold text-white' : 'text-black/55'"
                    >{{ n }}</p>
                </div>

                <!-- main -->
                <div class="min-w-0 flex-1 p-[1.6cqw]">
                    <p class="text-[1.7cqw] font-bold">Society overview</p>
                    <p class="text-[1.1cqw] text-black/45">FY 2026</p>

                    <div class="mt-[1.2cqw] grid grid-cols-3 gap-[1cqw]">
                        <div v-for="c in CARDS" :key="c.label" class="rounded-[1cqw] border border-black/[.06] p-[1cqw]">
                            <p class="text-[1.05cqw] text-black/50">{{ c.label }}</p>
                            <p class="mt-[0.3cqw] text-[1.8cqw] font-bold tracking-tight" :style="{ color: c.tone }">{{ c.value }}</p>
                            <p class="mt-[0.2cqw] truncate text-[0.95cqw] text-black/45">{{ c.note }}</p>
                        </div>
                    </div>

                    <div class="mt-[1.2cqw] rounded-[1cqw] border border-black/[.06] p-[1.2cqw]">
                        <p class="flex items-center justify-between text-[1.2cqw] font-semibold">Monthly deposits <span class="text-[1cqw] font-normal text-black/40">Jan – Dec</span></p>
                        <div class="mt-[1cqw] flex h-[8cqw] items-end gap-[0.6cqw]">
                            <span v-for="(h, i) in MONTHS" :key="i" class="flex-1 rounded-t-[0.4cqw]" :class="i === MONTHS.length - 1 ? 'bg-[#047857]' : 'bg-[#3ddc97]/45'" :style="{ height: h + '%' }"></span>
                        </div>
                    </div>

                    <div class="mt-[1.2cqw] grid grid-cols-2 gap-[1cqw]">
                        <div class="rounded-[1cqw] border border-black/[.06] p-[1cqw]">
                            <p class="text-[1.1cqw] font-semibold">Fines · Article 9</p>
                            <p class="mt-[0.5cqw] flex justify-between text-[1cqw] text-black/60"><span>Late monthly</span><span class="font-semibold text-[#b91c1c]">৳ 20 / month</span></p>
                            <p class="mt-[0.3cqw] flex justify-between text-[1cqw] text-black/60"><span>Late yearly</span><span class="font-semibold text-[#b91c1c]">৳ 50 / month</span></p>
                        </div>
                        <div class="rounded-[1cqw] border border-black/[.06] p-[1cqw]">
                            <p class="text-[1.1cqw] font-semibold">Profit split</p>
                            <p class="mt-[0.5cqw] flex justify-between text-[1cqw] text-black/60"><span>Fi Sabilillah</span><span class="font-semibold">2.5%</span></p>
                            <p class="mt-[0.3cqw] flex justify-between text-[1cqw] text-black/60"><span>Risk Fund</span><span class="font-semibold">5%</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- an official report, as a PDF -->
    <div class="absolute bottom-[8cqw] left-[30cqw] w-[15cqw] rotate-[-4deg]">
        <div class="absolute inset-0 translate-x-[0.8cqw] translate-y-[0.8cqw] rounded-[0.8cqw] bg-white/70"></div>
        <div class="relative rounded-[0.8cqw] bg-white p-[1.2cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]" lang="bn">
            <p class="flex items-center justify-between">
                <span class="text-[1.15cqw] font-bold text-[#047857]">এক্সপ্রেস গ্রুপ</span>
                <span class="rounded-[0.3cqw] bg-[#dc2626] px-[0.5cqw] text-[0.85cqw] font-bold text-white">PDF</span>
            </p>
            <p class="mt-[0.4cqw] text-[1cqw] font-semibold">বার্ষিক হিসাব ২০২৬</p>
            <span v-for="w in [92, 80, 86, 70, 88, 64]" :key="w" class="mt-[0.6cqw] block h-[0.45cqw] rounded-full bg-black/10" :style="{ width: w + '%' }"></span>
            <p class="mt-[1cqw] flex justify-between text-[0.8cqw] text-black/40"><span>সভাপতি</span><span>কোষাধ্যক্ষ</span></p>
        </div>
    </div>
</template>

<script setup>
// Quick Annotation: an equipment photo whose nameplate GPT-4o vision has read, next to the
// review queue where a person approves or rejects each extracted field before it hits the catalog.
import Frame from '../Frame.vue';

const FIELDS = [
    { k: 'Model', v: 'PRX 450 hydraulic press', s: 'ok' },
    { k: 'Voltage', v: '400 V · 3-phase · 50 Hz', s: 'ok' },
    { k: 'Manual', v: 'PRX-450_manual.pdf', s: 'ok', pdf: true },
    { k: 'Year', v: '2009', s: 'no' },
    { k: 'Price range', v: '€2,400 – €3,100', s: 'open' },
];
</script>

<template>
    <!-- review queue -->
    <div class="absolute left-[45.5cqw] top-[5.5cqw] w-[51cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="review queue · item 0412">
            <div class="flex gap-[1.6cqw] p-[1.6cqw]">
                <!-- the equipment photo, nameplate boxed by the vision step -->
                <div class="relative w-[17cqw] shrink-0 overflow-hidden rounded-[1cqw] bg-gradient-to-b from-[#dfe3e8] to-[#b9c0c9]">
                    <svg viewBox="0 0 100 150" class="block h-auto w-full" aria-hidden="true">
                        <rect x="0" y="128" width="100" height="22" fill="#9aa3ad" />
                        <!-- frame + ram -->
                        <rect x="14" y="10" width="72" height="16" rx="2" fill="#3f6f9e" />
                        <rect x="18" y="26" width="10" height="96" fill="#356089" />
                        <rect x="72" y="26" width="10" height="96" fill="#356089" />
                        <rect x="44" y="26" width="12" height="26" fill="#c7ccd2" />
                        <rect x="34" y="52" width="32" height="8" rx="1" fill="#2d4f72" />
                        <rect x="30" y="86" width="40" height="8" fill="#2d4f72" />
                        <rect x="12" y="118" width="76" height="12" rx="2" fill="#2d4f72" />
                        <!-- control box -->
                        <rect x="58" y="64" width="20" height="16" rx="1.5" fill="#e9ecef" />
                        <circle cx="63" cy="72" r="2.6" fill="#e5484d" />
                        <circle cx="71" cy="72" r="2.6" fill="#30a46c" />
                        <!-- nameplate -->
                        <rect x="21" y="64" width="30" height="17" rx="1" fill="#d9dde2" stroke="#8c959f" stroke-width=".6" />
                        <circle cx="23" cy="66" r=".8" fill="#8c959f" /><circle cx="49" cy="66" r=".8" fill="#8c959f" />
                        <circle cx="23" cy="79" r=".8" fill="#8c959f" /><circle cx="49" cy="79" r=".8" fill="#8c959f" />
                        <rect x="25" y="67.5" width="16" height="2.2" fill="#46505a" />
                        <rect x="25" y="71.5" width="22" height="1.4" fill="#6b7580" />
                        <rect x="25" y="74.3" width="19" height="1.4" fill="#6b7580" />
                        <rect x="25" y="77" width="12" height="1.4" fill="#6b7580" />
                        <!-- vision box -->
                        <rect x="18" y="61" width="36" height="23" rx="1.5" fill="none" stroke="#8b7cff" stroke-width="1.3" stroke-dasharray="3 2" />
                    </svg>
                    <span class="absolute left-[0.8cqw] top-[0.8cqw] rounded-full bg-[#14161a]/80 px-[0.8cqw] py-[0.25cqw] text-[1.05cqw] font-semibold text-white">IMG_0412.jpg</span>
                    <span class="absolute left-[3cqw] top-[14.7cqw] rounded-[0.4cqw] bg-[#8b7cff] px-[0.6cqw] py-[0.15cqw] text-[0.95cqw] font-bold text-white">nameplate</span>
                </div>

                <!-- extracted fields, each signed off by a person -->
                <div class="min-w-0 flex-1">
                    <p class="flex items-center gap-[0.8cqw] text-[1.15cqw] font-semibold uppercase tracking-[0.08em] text-black/40">
                        Extracted fields
                        <span class="ml-auto rounded-full bg-[#8b7cff]/15 px-[0.9cqw] py-[0.2cqw] text-[1.05cqw] normal-case tracking-normal text-[#5b4bd6]">3 / 5 approved</span>
                    </p>
                    <div class="mt-[1cqw] space-y-[0.7cqw]">
                        <div
                            v-for="f in FIELDS"
                            :key="f.k"
                            class="flex items-center gap-[1cqw] rounded-[0.9cqw] border px-[1.1cqw] py-[0.75cqw]"
                            :class="f.s === 'ok' ? 'border-[#30a46c]/30 bg-[#30a46c]/[.06]' : f.s === 'no' ? 'border-[#e5484d]/30 bg-[#e5484d]/[.05]' : 'border-[#8b7cff]/50 bg-white ring-[0.25cqw] ring-[#8b7cff]/15'"
                        >
                            <span class="min-w-0 flex-1 leading-tight">
                                <span class="block text-[1.05cqw] font-medium text-black/45">{{ f.k }}</span>
                                <span class="flex items-center gap-[0.5cqw] truncate text-[1.4cqw] font-semibold" :class="f.s === 'no' ? 'text-black/35 line-through' : ''">
                                    <svg v-if="f.pdf" viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw] shrink-0 text-[#e5484d]" fill="currentColor"><path d="M6 2h8l6 6v14H6z" opacity=".25" /><path d="M14 2v6h6" fill="none" stroke="currentColor" stroke-width="2" /><path d="M6 2h8l6 6v14H6z" fill="none" stroke="currentColor" stroke-width="2" /></svg>
                                    {{ f.v }}
                                </span>
                            </span>
                            <span
                                class="grid h-[2.5cqw] w-[2.5cqw] place-items-center rounded-[0.6cqw]"
                                :class="f.s === 'ok' ? 'bg-[#30a46c] text-white' : 'border border-black/10 text-[#30a46c]'"
                            >
                                <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                            </span>
                            <span
                                class="grid h-[2.5cqw] w-[2.5cqw] place-items-center rounded-[0.6cqw]"
                                :class="f.s === 'no' ? 'bg-[#e5484d] text-white' : 'border border-black/10 text-[#e5484d]'"
                            >
                                <svg viewBox="0 0 24 24" class="h-[55%] w-[55%]" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round"><path d="M6 6l12 12M18 6 6 18" /></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-[3cqw]"></div>
        </Frame>
    </div>

    <!-- the AI steps that ran before the reviewer -->
    <div class="absolute bottom-[8cqw] left-[24cqw] flex -rotate-[2deg] items-center gap-[0.8cqw] rounded-[1.6cqw] bg-white px-[1.4cqw] py-[1.1cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <span class="flex items-center gap-[0.6cqw] rounded-full bg-[#8b7cff]/15 px-[1cqw] py-[0.45cqw] text-[1.25cqw] font-semibold text-[#5b4bd6]">
            <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z" /><circle cx="12" cy="12" r="3" /></svg>
            GPT-4o vision
        </span>
        <span class="text-[1.3cqw] text-black/30">→</span>
        <span class="flex items-center gap-[0.6cqw] rounded-full bg-[#8b7cff]/15 px-[1cqw] py-[0.45cqw] text-[1.25cqw] font-semibold text-[#5b4bd6]">
            <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="11" cy="11" r="6.5" /><path d="m16 16 5 5" /></svg>
            Search agent
        </span>
        <span class="text-[1.3cqw] text-black/30">→</span>
        <span class="flex items-center gap-[0.6cqw] rounded-full bg-[#14161a] px-[1cqw] py-[0.45cqw] text-[1.25cqw] font-semibold text-white">
            <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4" /><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6" /></svg>
            You approve
        </span>
    </div>
</template>

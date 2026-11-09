<script setup>
// Progressive Math Test: a question from the IGCSE bank, filed by level, topic and syllabus,
// and the home page's top-ranked students. Names and scores are illustrative.
import Frame from '../Frame.vue';

const FILTERS = [
    { k: 'Level', v: 'Extended' },
    { k: 'Topic', v: 'Algebra' },
    { k: 'Syllabus', v: 'IGCSE 0580' },
];
const OPTIONS = [
    { l: 'A', v: 'x = 3' },
    { l: 'B', v: 'x = 5', on: true },
    { l: 'C', v: 'x = 7' },
    { l: 'D', v: 'x = 15' },
];
const RANKS = [
    { n: 'Rafi H.', s: 'Greenfield School', p: 980 },
    { n: 'Nadia K.', s: 'Lakeside Academy', p: 955 },
    { n: 'Leo M.', s: 'Hillview College', p: 940 },
];
const DOTS = Array.from({ length: 20 }, (_, i) => i);
</script>

<template>
    <!-- the test site -->
    <div class="absolute right-[-2cqw] top-[5cqw] w-[55cqw] rotate-[-1.5deg]">
        <Frame kind="browser" url="progressive math test · demo">
            <!-- nav -->
            <div class="flex items-center gap-[1.4cqw] border-b border-black/[.06] px-[1.8cqw] py-[1cqw]">
                <span class="flex items-center gap-[0.6cqw] text-[1.3cqw] font-bold">
                    <span class="grid h-[2.2cqw] w-[2.2cqw] place-items-center rounded-[0.6cqw] bg-gradient-to-br from-[#8b7cff] to-[#4338ca] text-[1.2cqw] text-white">∑</span>
                    PMT
                </span>
                <span class="text-[1.1cqw] text-black/50">Home</span>
                <span class="text-[1.1cqw] font-semibold text-[#4338ca]">Tests</span>
                <span class="text-[1.1cqw] text-black/50">Results</span>
                <span class="ml-auto flex items-center gap-[0.5cqw] rounded-full bg-[#4338ca]/10 px-[1cqw] py-[0.3cqw] text-[1.1cqw] font-semibold text-[#4338ca]">
                    <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><circle cx="12" cy="13" r="8" /><path d="M12 9v4l2.5 2M9 2h6" /></svg>
                    18:42
                </span>
            </div>

            <div class="px-[1.8cqw] pb-[2cqw] pt-[1.3cqw]">
                <!-- filters -->
                <div class="flex gap-[0.8cqw]">
                    <span v-for="f in FILTERS" :key="f.k" class="flex items-center gap-[0.5cqw] rounded-[0.6cqw] border border-black/[.08] px-[0.9cqw] py-[0.4cqw] text-[1.05cqw]">
                        <span class="text-black/45">{{ f.k }}</span>
                        <span class="font-semibold">{{ f.v }}</span>
                        <svg viewBox="0 0 24 24" class="h-[1.1cqw] w-[1.1cqw] text-black/35" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6" /></svg>
                    </span>
                </div>

                <!-- question -->
                <div class="mt-[1.2cqw] rounded-[1.2cqw] border border-black/[.07] p-[1.6cqw]">
                    <p class="flex items-center justify-between text-[1.05cqw] font-semibold uppercase tracking-[0.1em] text-black/40">
                        Question 12
                        <span class="normal-case tracking-normal">2 marks</span>
                    </p>
                    <p class="mt-[0.6cqw] text-[1.7cqw] font-semibold">Solve for <i class="font-serif">x</i>:&nbsp; <span class="font-serif">3<i>x</i> + 7 = 22</span></p>
                    <div class="mt-[1.2cqw] grid grid-cols-2 gap-[0.9cqw]">
                        <span
                            v-for="o in OPTIONS"
                            :key="o.l"
                            class="flex items-center gap-[1cqw] rounded-[0.8cqw] border px-[1cqw] py-[0.7cqw] text-[1.3cqw]"
                            :class="o.on ? 'border-[#6d5dfc] bg-[#6d5dfc]/[.07] font-semibold ring-[0.25cqw] ring-[#6d5dfc]/15' : 'border-black/[.08]'"
                        >
                            <span
                                class="grid h-[2cqw] w-[2cqw] place-items-center rounded-full text-[1.05cqw] font-bold"
                                :class="o.on ? 'bg-[#6d5dfc] text-white' : 'border border-black/15 text-black/45'"
                            >{{ o.l }}</span>
                            <span class="font-serif">{{ o.v }}</span>
                        </span>
                    </div>
                    <div class="mt-[1.3cqw] flex items-center gap-[0.35cqw]">
                        <span
                            v-for="d in DOTS"
                            :key="d"
                            class="h-[0.6cqw] flex-1 rounded-full"
                            :class="d < 11 ? 'bg-[#6d5dfc]/45' : d === 11 ? 'bg-[#6d5dfc]' : 'bg-black/[.08]'"
                        ></span>
                        <span class="ml-[0.8cqw] rounded-[0.5cqw] bg-[#4338ca] px-[1.1cqw] py-[0.4cqw] text-[1.1cqw] font-semibold text-white">Next</span>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- top ranks, from the home page -->
    <div class="absolute bottom-[8.5cqw] left-[25cqw] w-[20cqw] rotate-[-2.5deg] rounded-[1.4cqw] bg-white p-[1.4cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <p class="flex items-center gap-[0.6cqw] text-[1.3cqw] font-bold">
            <svg viewBox="0 0 24 24" class="h-[1.6cqw] w-[1.6cqw] text-[#f59e0b]" fill="currentColor"><path d="M7 3h10v4a5 5 0 0 1-10 0V3zM4 4h3v3a2 2 0 0 1-3-1.7V4zm13 0h3v1.3A2 2 0 0 1 17 7V4zM10 14h4v3h3v3H7v-3h3z" /></svg>
            Top ranks
            <span class="ml-auto text-[0.95cqw] font-medium text-black/40">Points</span>
        </p>
        <div class="mt-[0.8cqw] space-y-[0.5cqw]">
            <div v-for="(r, i) in RANKS" :key="r.n" class="flex items-center gap-[0.8cqw]">
                <span
                    class="grid h-[2cqw] w-[2cqw] shrink-0 place-items-center rounded-full text-[0.95cqw] font-bold"
                    :class="i === 0 ? 'bg-[#f59e0b] text-white' : 'bg-black/[.06] text-black/55'"
                >{{ i + 1 }}</span>
                <span class="min-w-0 flex-1 leading-tight">
                    <span class="block truncate text-[1.15cqw] font-semibold">{{ r.n }}</span>
                    <span class="block truncate text-[0.9cqw] text-black/45">{{ r.s }}</span>
                </span>
                <span class="text-[1.2cqw] font-bold text-[#4338ca]">{{ r.p }}</span>
            </div>
        </div>
    </div>
</template>

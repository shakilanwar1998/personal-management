<script setup>
// Fin Knowledge Connector: the support chat over the product, answering with a lesson the customer
// can actually open — and pointing to the upgrade when the course isn't in their plan.
import Frame from '../Frame.vue';
</script>

<template>
    <!-- the product, faded behind the messenger -->
    <div class="absolute left-[44cqw] top-[8cqw] w-[48cqw] opacity-60">
        <Frame kind="browser" url="app · invoices">
            <div class="flex h-[44cqw]">
                <div class="w-[9cqw] space-y-[1cqw] border-r border-black/[.06] bg-[#f6f7f9] p-[1.2cqw]">
                    <span class="block h-[1.6cqw] w-[5cqw] rounded-[0.4cqw] bg-[#5aa9ff]/50"></span>
                    <span v-for="i in 6" :key="i" class="block h-[1cqw] rounded-full" :class="i === 2 ? 'w-[6cqw] bg-[#1d4ed8]/40' : 'w-[5cqw] bg-black/10'"></span>
                </div>
                <div class="flex-1 p-[1.6cqw]">
                    <span class="block h-[1.6cqw] w-[12cqw] rounded-full bg-black/15"></span>
                    <div class="mt-[1.6cqw] space-y-[1cqw]">
                        <div v-for="i in 7" :key="i" class="flex items-center gap-[1.2cqw]">
                            <span class="h-[1cqw] w-[7cqw] rounded-full bg-black/10"></span>
                            <span class="h-[1cqw] flex-1 rounded-full bg-black/[.06]"></span>
                            <span class="h-[1.4cqw] w-[4cqw] rounded-full" :class="i % 3 === 0 ? 'bg-[#f59e0b]/35' : 'bg-[#30a46c]/30'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- the support messenger -->
    <div class="absolute right-[5cqw] top-[4.5cqw] w-[30cqw] rotate-[1.5deg] overflow-hidden rounded-[2cqw] bg-white text-[#14161a] shadow-[0_2.5cqw_6cqw_rgb(0_0_0/.55)] ring-1 ring-white/20">
        <div class="flex items-center gap-[1cqw] bg-gradient-to-br from-[#2563eb] to-[#1d4ed8] px-[1.6cqw] py-[1.3cqw] text-white">
            <span class="grid h-[3cqw] w-[3cqw] place-items-center rounded-full bg-white/20">
                <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="currentColor"><path d="M12 2l2.2 6.8L21 11l-6.8 2.2L12 20l-2.2-6.8L3 11l6.8-2.2z" /></svg>
            </span>
            <span class="leading-tight">
                <span class="block text-[1.5cqw] font-bold">Support</span>
                <span class="block text-[1.1cqw] text-white/70">AI agent · answers from your courses</span>
            </span>
        </div>

        <div class="space-y-[1cqw] bg-[#f7f8fa] p-[1.4cqw]">
            <p class="ml-auto w-fit max-w-[80%] rounded-[1.2cqw] rounded-br-[0.3cqw] bg-[#2563eb] px-[1.2cqw] py-[0.8cqw] text-[1.25cqw] leading-snug text-white">How do I pause payment reminders for one customer?</p>

            <div class="max-w-[94%] rounded-[1.2cqw] rounded-bl-[0.3cqw] bg-white p-[1cqw] shadow-[0_0.2cqw_0.6cqw_rgb(0_0_0/.06)] ring-1 ring-black/[.05]">
                <p class="px-[0.2cqw] text-[1.25cqw] leading-snug">There’s a 3-minute lesson on exactly that:</p>
                <div class="mt-[0.8cqw] flex items-center gap-[1cqw] rounded-[0.9cqw] border border-black/[.08] p-[0.7cqw]">
                    <span class="relative grid h-[4.4cqw] w-[6cqw] shrink-0 place-items-center rounded-[0.6cqw] bg-gradient-to-br from-[#5aa9ff] to-[#1e3a8a]">
                        <span class="grid h-[2.2cqw] w-[2.2cqw] place-items-center rounded-full bg-white text-[#1d4ed8]">
                            <svg viewBox="0 0 24 24" class="ml-[8%] h-[55%] w-[55%]" fill="currentColor"><path d="M7 4.5v15l12-7.5z" /></svg>
                        </span>
                        <span class="absolute bottom-[0.3cqw] right-[0.4cqw] rounded-[0.3cqw] bg-black/60 px-[0.4cqw] text-[0.9cqw] font-semibold text-white">3:04</span>
                    </span>
                    <span class="min-w-0 leading-tight">
                        <span class="block truncate text-[1.3cqw] font-bold">Pausing reminders</span>
                        <span class="mt-[0.2cqw] block text-[1.05cqw] text-black/45">Lesson · 3 min</span>
                        <span class="mt-[0.3cqw] inline-flex items-center gap-[0.3cqw] rounded-full bg-[#30a46c]/15 px-[0.8cqw] py-[0.15cqw] text-[1.05cqw] font-semibold text-[#2f8a5b]"><svg viewBox="0 0 24 24" class="h-[1.1cqw] w-[1.1cqw]" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>Included in your plan</span>
                    </span>
                </div>
            </div>

            <p class="ml-auto w-fit max-w-[80%] rounded-[1.2cqw] rounded-br-[0.3cqw] bg-[#2563eb] px-[1.2cqw] py-[0.8cqw] text-[1.25cqw] leading-snug text-white">And automating follow-up emails?</p>

            <div class="max-w-[94%] rounded-[1.2cqw] rounded-bl-[0.3cqw] bg-white p-[1cqw] shadow-[0_0.2cqw_0.6cqw_rgb(0_0_0/.06)] ring-1 ring-black/[.05]">
                <p class="px-[0.2cqw] text-[1.25cqw] leading-snug">That’s in the Sequences course, which comes with the next plan up. Want me to show you what’s included?</p>
            </div>
            <div class="h-[2cqw]"></div>
        </div>
    </div>
</template>

<script setup>
// Science Fest: the fest's event site (a hero, then one card per event with its own registration),
// plus the one form that takes both solo and team entries. Wording follows the real pages.
import Frame from '../Frame.vue';

const EVENTS = [
    { name: 'Science Olympiad', type: 'Solo', icon: 'atom', tone: '#26a9e0' },
    { name: 'Math Olympiad', type: 'Solo', icon: 'pi', tone: '#7c5cff' },
    { name: 'Project Showcase', type: 'Team', icon: 'bulb', tone: '#f59e0b' },
    { name: 'CTF Contest', type: 'Team', icon: 'flag', tone: '#d0232c' },
];
const SIZES = ['S', 'M', 'L', 'XL', 'XXL'];
</script>

<template>
    <!-- the fest site -->
    <div class="absolute right-[-2.5cqw] top-[5cqw] w-[54cqw] rotate-[-1.5deg]">
        <Frame kind="browser" url="sciencefest · events">
            <!-- hero -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#0f4c75] via-[#1683c2] to-[#26a9e0] px-[2cqw] py-[1.8cqw] text-white">
                <svg viewBox="0 0 200 80" class="absolute -right-[2cqw] -top-[1cqw] h-[13cqw] w-auto opacity-25" fill="none" stroke="#fff" stroke-width="1.2" aria-hidden="true">
                    <ellipse cx="140" cy="40" rx="40" ry="14" />
                    <ellipse cx="140" cy="40" rx="40" ry="14" transform="rotate(60 140 40)" />
                    <ellipse cx="140" cy="40" rx="40" ry="14" transform="rotate(120 140 40)" />
                    <circle cx="140" cy="40" r="4" fill="#fff" />
                </svg>
                <p class="text-[1.05cqw] font-semibold uppercase tracking-[0.16em] text-white/75">National science fest</p>
                <p class="mt-[0.3cqw] text-[1.5cqw] font-semibold leading-tight text-white/90">Al-Khwarizmi</p>
                <p class="text-[2.6cqw] font-extrabold leading-[1.05] tracking-tight">Science Fest 2025</p>
                <p class="mt-[0.9cqw] flex items-center gap-[1.4cqw] text-[1.1cqw] text-white/85">
                    <span class="flex items-center gap-[0.4cqw]">
                        <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4M8 2v4M3 10h18" /></svg>
                        November 2025
                    </span>
                    <span class="flex items-center gap-[0.4cqw]">
                        <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5" /><path d="M2.5 20c.8-3.5 3.4-5.5 6.5-5.5s5.7 2 6.5 5.5M16 4.5a3.5 3.5 0 0 1 0 7M18 14.8c2 .7 3.2 2.5 3.6 5.2" /></svg>
                        Solo &amp; team events
                    </span>
                </p>
                <span class="mt-[1.1cqw] inline-block rounded-[0.6cqw] bg-[#d0232c] px-[1.4cqw] py-[0.5cqw] text-[1.2cqw] font-semibold shadow-[0_0.4cqw_1cqw_rgb(0_0_0/.25)]">Participate</span>
            </div>

            <!-- events -->
            <div class="px-[2cqw] pb-[2cqw] pt-[1.4cqw]">
                <p class="flex items-center justify-between text-[1.4cqw] font-bold">
                    Events
                    <span class="rounded-full bg-[#26a9e0]/12 px-[0.9cqw] py-[0.2cqw] text-[1.05cqw] font-semibold text-[#1683c2]">Registration open</span>
                </p>
                <div class="mt-[1cqw] grid grid-cols-2 gap-[1cqw]">
                    <div v-for="e in EVENTS" :key="e.name" class="flex items-center gap-[1cqw] rounded-[1cqw] border border-black/[.07] p-[1cqw]">
                        <span class="grid h-[3.4cqw] w-[3.4cqw] shrink-0 place-items-center rounded-[0.8cqw] text-white" :style="{ background: e.tone }">
                            <svg v-if="e.icon === 'atom'" viewBox="0 0 24 24" class="h-[62%] w-[62%]" fill="none" stroke="currentColor" stroke-width="1.8"><ellipse cx="12" cy="12" rx="10" ry="4" /><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(60 12 12)" /><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(120 12 12)" /><circle cx="12" cy="12" r="1.6" fill="currentColor" /></svg>
                            <span v-else-if="e.icon === 'pi'" class="text-[2cqw] font-bold leading-none">π</span>
                            <svg v-else-if="e.icon === 'bulb'" viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6M10 22h4M12 2a7 7 0 0 0-4 12.7V16h8v-1.3A7 7 0 0 0 12 2z" /></svg>
                            <svg v-else viewBox="0 0 24 24" class="h-[58%] w-[58%]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 22V3M5 4h12l-2.5 4L17 12H5" /></svg>
                        </span>
                        <span class="min-w-0 flex-1 leading-tight">
                            <span class="block truncate text-[1.3cqw] font-semibold">{{ e.name }}</span>
                            <span class="mt-[0.5cqw] flex items-center justify-between">
                                <span
                                    class="rounded-full px-[0.7cqw] py-[0.1cqw] text-[0.95cqw] font-semibold"
                                    :class="e.type === 'Team' ? 'bg-[#d0232c]/10 text-[#b91c1c]' : 'bg-[#26a9e0]/12 text-[#1683c2]'"
                                >{{ e.type === 'Team' ? 'Team · 3' : 'Solo' }}</span>
                                <span class="rounded-[0.5cqw] bg-[#d0232c] px-[0.9cqw] py-[0.3cqw] text-[1.05cqw] font-semibold text-white">Register</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- one form for solo and team entries -->
    <div class="absolute bottom-[8.5cqw] left-[26cqw] w-[21cqw] rotate-[-2.5deg] rounded-[1.4cqw] bg-white p-[1.4cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <p class="flex items-center justify-between text-[1.3cqw] font-bold">
            Registration Form
            <span class="rounded-full bg-[#d0232c]/10 px-[0.7cqw] py-[0.1cqw] text-[0.95cqw] font-semibold text-[#b91c1c]">CTF · Team</span>
        </p>
        <p class="mt-[0.8cqw] text-[0.95cqw] font-medium text-black/45">Team Name *</p>
        <p class="mt-[0.2cqw] rounded-[0.5cqw] border border-[#26a9e0]/60 px-[0.8cqw] py-[0.35cqw] text-[1.1cqw] font-medium ring-[0.2cqw] ring-[#26a9e0]/15">Null Pointers</p>
        <p class="mt-[0.7cqw] flex items-center text-[0.95cqw] font-medium text-black/45">
            Team Members
            <span class="ml-auto flex -space-x-[0.4cqw]">
                <span v-for="c in ['#26a9e0', '#7c5cff', '#f59e0b']" :key="c" class="h-[1.7cqw] w-[1.7cqw] rounded-full ring-[0.2cqw] ring-white" :style="{ background: c }"></span>
            </span>
            <span class="ml-[0.5cqw] font-semibold text-black/60">3 / 3</span>
        </p>
        <div class="mt-[0.7cqw] flex items-center gap-[0.8cqw]">
            <span class="flex flex-1 items-center gap-[0.5cqw] rounded-[0.5cqw] border border-dashed border-black/20 px-[0.7cqw] py-[0.4cqw] text-[0.95cqw] text-black/50">
                <svg viewBox="0 0 24 24" class="h-[1.3cqw] w-[1.3cqw] text-[#30a46c]" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                ID card photo
            </span>
            <span class="flex gap-[0.25cqw]">
                <span
                    v-for="s in SIZES.slice(1, 4)"
                    :key="s"
                    class="grid h-[1.9cqw] min-w-[1.9cqw] place-items-center rounded-[0.4cqw] px-[0.3cqw] text-[0.9cqw] font-semibold"
                    :class="s === 'L' ? 'bg-[#26a9e0] text-white' : 'border border-black/10 text-black/50'"
                >{{ s }}</span>
            </span>
        </div>
    </div>
</template>

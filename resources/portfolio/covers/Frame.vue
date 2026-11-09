<script setup>
// A device frame for cover scenes. Everything is sized in cqw (cover width), so a scene
// looks the same in a small project card and in the big feed cover.
defineProps({
    kind: { type: String, default: 'browser' }, // browser | phone | window
    url: { type: String, default: '' }, // browser address bar / window title
    dark: { type: Boolean, default: false }, // dark app UI (e.g. a code tool)
});
</script>

<template>
    <div
        v-if="kind === 'phone'"
        class="overflow-hidden rounded-[4.2cqw] border-[0.7cqw] border-[#0b0d12] shadow-[0_2.5cqw_6cqw_rgb(0_0_0/.55)] ring-1 ring-white/15"
        :class="dark ? 'bg-[#111418] text-white' : 'bg-white text-[#14161a]'"
    >
        <div class="relative flex h-[3.2cqw] items-center justify-between px-[2.4cqw] text-[1.3cqw] font-semibold">
            <span>9:41</span>
            <span class="absolute left-1/2 top-[0.6cqw] h-[1.6cqw] w-[9cqw] -translate-x-1/2 rounded-full bg-[#0b0d12]"></span>
            <span class="flex items-center gap-[0.4cqw]"><span class="h-[0.9cqw] w-[1.8cqw] rounded-[0.3cqw] border border-current opacity-70"></span></span>
        </div>
        <slot />
    </div>

    <div
        v-else
        class="overflow-hidden rounded-[1.6cqw] shadow-[0_2.5cqw_6cqw_rgb(0_0_0/.55)] ring-1"
        :class="dark ? 'bg-[#15181d] text-[#e6e8ec] ring-white/10' : 'bg-white text-[#14161a] ring-white/25'"
    >
        <div class="flex h-[3.4cqw] items-center gap-[0.7cqw] border-b px-[1.4cqw]" :class="dark ? 'border-white/10 bg-[#1b1f25]' : 'border-black/[.07] bg-[#f3f3f1]'">
            <span class="h-[1cqw] w-[1cqw] rounded-full bg-[#ff5f57]"></span>
            <span class="h-[1cqw] w-[1cqw] rounded-full bg-[#febc2e]"></span>
            <span class="h-[1cqw] w-[1cqw] rounded-full bg-[#28c840]"></span>
            <span
                v-if="url"
                class="ml-[1.2cqw] truncate text-[1.3cqw]"
                :class="kind === 'browser' ? (dark ? 'rounded-full bg-white/10 px-[1.4cqw] py-[0.3cqw] text-white/60' : 'rounded-full bg-white px-[1.4cqw] py-[0.3cqw] text-black/45') : 'font-medium opacity-60'"
            >{{ url }}</span>
        </div>
        <slot />
    </div>
</template>

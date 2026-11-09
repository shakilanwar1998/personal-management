<script setup>
import { profile } from '../data.js';

defineProps({
    size: { type: Number, default: 40 },
    ring: { type: Boolean, default: false },
    online: { type: Boolean, default: false },
});
</script>

<template>
    <span class="relative inline-grid shrink-0 place-items-center rounded-full" :style="{ width: size + 'px', height: size + 'px' }">
        <span v-if="ring" class="absolute inset-0 rounded-full bg-[conic-gradient(from_180deg,rgb(var(--accent)),rgb(var(--ai)),rgb(var(--human)),rgb(var(--accent)))] p-[3px]">
            <span class="block h-full w-full rounded-full bg-surface"></span>
        </span>
        <img
            v-if="profile.avatar"
            :src="profile.avatar"
            :alt="profile.name"
            class="relative rounded-full object-cover"
            :style="{ width: (ring ? size - 10 : size) + 'px', height: (ring ? size - 10 : size) + 'px' }"
        />
        <span
            v-else
            class="relative grid place-items-center rounded-full bg-[radial-gradient(120%_120%_at_20%_10%,#5b84ff_0%,#1d3aa8_55%,#0b1433_100%)] ring-1 ring-inset ring-white/10 font-bold tracking-tight text-white"
            :style="{ width: (ring ? size - 10 : size) + 'px', height: (ring ? size - 10 : size) + 'px', fontSize: size * 0.32 + 'px' }"
            role="img"
            :aria-label="profile.name"
        >{{ profile.initials }}</span>
        <span v-if="online" class="absolute bottom-[6%] right-[6%] grid h-[22%] w-[22%] place-items-center rounded-full bg-surface">
            <span class="absolute h-[70%] w-[70%] animate-ping2 rounded-full bg-ok"></span>
            <span class="relative h-[70%] w-[70%] rounded-full bg-ok"></span>
        </span>
    </span>
</template>

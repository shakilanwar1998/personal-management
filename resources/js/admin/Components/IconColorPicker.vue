<script setup>
import { ICON_NAMES, iconFor } from '../lib/icons';
import { COLOR_NAMES, swatch, chipStyle } from '../lib/colors';

const icon = defineModel('icon', { type: [String, null], default: null });
const color = defineModel('color', { type: [String, null], default: null });
</script>

<template>
    <div class="space-y-3">
        <div>
            <p class="label">Colour</p>
            <div class="flex flex-wrap gap-1.5">
                <button
                    v-for="c in COLOR_NAMES"
                    :key="c"
                    type="button"
                    :title="c"
                    class="h-6 w-6 rounded-full ring-offset-2 transition dark:ring-offset-zinc-900"
                    :class="color === c ? 'ring-2 ring-zinc-900 dark:ring-white' : 'hover:scale-110'"
                    :style="{ backgroundColor: swatch(c) }"
                    @click="color = c"
                />
            </div>
        </div>
        <div>
            <p class="label">Icon</p>
            <div class="grid max-h-40 grid-cols-8 gap-1 overflow-y-auto rounded-lg border border-zinc-200 p-1.5 dark:border-zinc-700 sm:grid-cols-10">
                <button
                    v-for="n in ICON_NAMES"
                    :key="n"
                    type="button"
                    :title="n"
                    class="flex h-8 items-center justify-center rounded-md transition"
                    :class="icon === n ? 'ring-2 ring-brand-500' : 'text-zinc-500 hover:bg-zinc-100 dark:hover:bg-zinc-800'"
                    :style="icon === n ? chipStyle(color) : {}"
                    @click="icon = n"
                >
                    <component :is="iconFor(n)" class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>
</template>

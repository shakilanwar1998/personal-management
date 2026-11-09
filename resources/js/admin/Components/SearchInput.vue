<script setup>
import { Search, X } from 'lucide-vue-next';
const model = defineModel({ type: String, default: '' });
defineProps({ placeholder: { type: String, default: 'Search…' } });
</script>

<template>
    <div class="relative">
        <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
        <input v-model="model" type="search" :placeholder="placeholder" class="input pl-9 pr-8 [&::-webkit-search-cancel-button]:hidden" />
        <button v-if="model" type="button" class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-0.5 text-zinc-400 hover:text-zinc-600" aria-label="Clear search" @click="model = ''">
            <X class="h-3.5 w-3.5" />
        </button>
    </div>
</template>

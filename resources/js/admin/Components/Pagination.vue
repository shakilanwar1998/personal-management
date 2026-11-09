<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { number } from '../lib/format';

defineProps({ meta: { type: Object, required: true } });
</script>

<template>
    <div v-if="meta.total > 0" class="flex flex-col items-center justify-between gap-3 border-t border-zinc-100 px-4 py-3 text-sm dark:border-zinc-800 sm:flex-row">
        <p class="text-zinc-500 dark:text-zinc-400">
            Showing <span class="num font-medium text-zinc-700 dark:text-zinc-200">{{ number(meta.from) }}–{{ number(meta.to) }}</span>
            of <span class="num font-medium text-zinc-700 dark:text-zinc-200">{{ number(meta.total) }}</span>
        </p>
        <nav v-if="meta.last_page > 1" class="flex items-center gap-1">
            <template v-for="(l, i) in meta.links" :key="i">
                <Link
                    v-if="l.url"
                    :href="l.url"
                    preserve-scroll
                    preserve-state
                    class="num inline-flex h-8 min-w-[2rem] items-center justify-center rounded-lg px-2 text-[13px] font-medium transition"
                    :class="l.active ? 'bg-brand-600 text-white shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                >
                    <ChevronLeft v-if="i === 0" class="h-4 w-4" />
                    <ChevronRight v-else-if="i === meta.links.length - 1" class="h-4 w-4" />
                    <span v-else v-html="l.label" />
                </Link>
                <span v-else class="inline-flex h-8 min-w-[2rem] items-center justify-center px-1 text-zinc-300 dark:text-zinc-600">
                    <ChevronLeft v-if="i === 0" class="h-4 w-4" />
                    <ChevronRight v-else-if="i === meta.links.length - 1" class="h-4 w-4" />
                    <span v-else v-html="l.label" />
                </span>
            </template>
        </nav>
    </div>
</template>

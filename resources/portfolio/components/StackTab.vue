<script setup>
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';
import { posts, skillGroups } from '../data.js';

const emit = defineEmits(['tag']);

// "Used in N projects" is computed from post tags, so it can't drift from the feed.
// Clicking filters the feed by the same tag list, so the count and the feed always agree.
function usage(skill) {
    const tags = skill.tag || [];
    return posts.filter((p) => p.tags.some((t) => tags.includes(t))).length;
}
function pick(s) {
    if (usage(s)) emit('tag', { label: s.name, tags: s.tag });
}

const accentBar = { ai: 'bg-ai', accent: 'bg-accent', ink: 'bg-ink/70' };
</script>

<template>
    <div class="space-y-4">
        <section v-for="g in skillGroups" :key="g.id" class="card overflow-hidden" :aria-labelledby="`sg-${g.id}`">
            <header class="flex items-start gap-3 p-4 pb-3 sm:p-5 sm:pb-3">
                <span class="mt-1.5 h-2.5 w-2.5 shrink-0 rounded-full" :class="accentBar[g.accent]"></span>
                <div class="min-w-0 flex-1">
                    <h2 :id="`sg-${g.id}`" class="text-lg font-semibold tracking-tight">{{ g.title }}</h2>
                    <p v-if="g.note" class="mt-0.5 text-sm text-muted">{{ g.note }}</p>
                </div>
                <span class="font-mono text-xs text-faint">{{ g.skills.length }}</span>
            </header>
            <ul class="grid gap-2 px-3 pb-3 sm:grid-cols-2 sm:px-4 sm:pb-4">
                <li v-for="s in g.skills" :key="s.name">
                    <component
                        :is="usage(s) ? 'button' : 'div'"
                        :type="usage(s) ? 'button' : undefined"
                        class="flex h-full w-full items-center gap-3 rounded-2xl border border-line bg-raised/30 p-2.5 text-left"
                        :class="usage(s) ? 'transition hover:border-ai/40 hover:bg-raised/70' : ''"
                        :title="usage(s) ? `Show projects using ${s.name}` : undefined"
                        @click="pick(s)"
                    >
                        <span class="flex shrink-0 -space-x-2">
                            <TechLogo v-for="l in (s.logos || []).slice(0, 3)" :key="l" :name="l" :size="17" tile class="ring-2 ring-surface" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-[14px] font-medium leading-snug">{{ s.name }}</span>
                            <span v-if="usage(s)" class="mt-0.5 inline-flex items-center gap-0.5 font-mono text-[11px] text-faint">
                                {{ usage(s) }} {{ usage(s) === 1 ? 'project' : 'projects' }} <Icon name="chevronRight" :size="12" />
                            </span>
                        </span>
                    </component>
                </li>
            </ul>
            <footer v-if="g.learning" class="flex flex-wrap items-center gap-1.5 border-t border-dashed border-line bg-raised/30 px-4 py-3 sm:px-5">
                <span class="mr-1 inline-flex items-center gap-1.5 text-xs font-medium text-muted">
                    <Icon name="sparkle" :size="14" class="text-ai" /> Learning now
                </span>
                <span v-for="l in g.learning" :key="l" class="rounded-full border border-dashed border-ai/40 px-2.5 py-0.5 text-xs text-ai-text">{{ l }}</span>
            </footer>
        </section>
    </div>
</template>

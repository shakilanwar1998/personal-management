<script setup>
import { computed, ref } from 'vue';
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';
import ProjectCover from './ProjectCover.vue';
import { posts } from '../data.js';
import { logo } from '../logos.js';
import { caseUrl, hasCase } from '../cases/index.js';

const props = defineProps({ tag: { type: Object, default: null } }); // { label, tags[] } from a skill badge
const emit = defineEmits(['open', 'case', 'clear-tag']);

const groups = [
    { id: 'all', label: 'All projects' },
    { id: 'client', label: 'Client work', kinds: ['Client project', 'Prototype'] },
    { id: 'okkhor', label: 'At Okkhor', kinds: ['At Okkhor'] },
    { id: 'dropmatix', label: 'At Dropmatix', kinds: ['At Dropmatix'] },
    { id: 'own', label: 'Own & community', kinds: ['Own product', 'Open source', 'Side project', 'Community', 'Early work'] },
];
const group = ref('all');

const sorted = [...posts].sort((a, b) => (b.pinned ? 1 : 0) - (a.pinned ? 1 : 0) || b.sort.localeCompare(a.sort));
const list = computed(() => {
    const g = groups.find((x) => x.id === group.value);
    let l = g.kinds ? sorted.filter((p) => g.kinds.includes(p.kind)) : sorted;
    if (props.tag) l = l.filter((p) => p.tags.some((t) => props.tag.tags.includes(t)));
    return l;
});
const featured = computed(() => (group.value === 'all' && !props.tag ? list.value.find((p) => p.pinned) : null));
const rest = computed(() => list.value.filter((p) => p !== featured.value));
const count = (g) => (g.kinds ? sorted.filter((p) => g.kinds.includes(p.kind)).length : sorted.length);

// What a click does, so the card can say so.
function target(p) {
    if (!p) return null;
    if (p.url) return { href: p.url, external: /^https?:/.test(p.url), label: /github\.com/.test(p.url) ? 'View on GitHub' : 'Visit project' };
    if (hasCase(p.id)) return { href: caseUrl(p.id), external: false, label: 'Read the case study', case: true };
    const l = (p.links || []).find((x) => x.primary) || p.links?.[0];
    if (l) return { href: l.href, external: !!l.external, label: l.label };
    return null;
}
const brandTags = (p) => p.tags.filter((t) => logo(t)?.kind === 'brand').slice(0, 5);

// Plain click on a case-study card opens the modal; Cmd/Ctrl/Shift/middle click opens the page.
function onCard(e, p) {
    const t = target(p);
    if (!t) return emit('open', p);
    if (t.case && !(e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1)) {
        e.preventDefault();
        emit('case', p);
    }
}
</script>

<template>
    <div>
        <header class="mb-4 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="eyebrow">Portfolio</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight">Projects</h2>
                <p class="mt-1 text-sm text-muted">{{ sorted.length }} projects. Live products open their site; everything else opens a case study. Most client names are withheld.</p>
            </div>
        </header>

        <div class="no-scrollbar -mx-3 mb-4 flex gap-2 overflow-x-auto px-3 py-1 sm:mx-0 sm:px-0" role="group" aria-label="Filter projects">
            <button
                v-for="g in groups"
                :key="g.id"
                type="button"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-[13.5px] font-medium transition focus-visible:outline-offset-[-2px]"
                :class="group === g.id ? 'border-ink bg-ink text-bg' : 'border-line bg-surface text-ink hover:bg-raised'"
                :aria-pressed="group === g.id"
                @click="group = g.id"
            >
                {{ g.label }} <span class="font-mono text-[11px] opacity-60">{{ count(g) }}</span>
            </button>
        </div>

        <p v-if="tag" class="mb-4 flex items-center justify-between gap-2 rounded-2xl border border-ai/30 bg-ai/10 px-3.5 py-2 text-sm" role="status">
            <span>Projects using <strong class="text-ai-text">{{ tag.label }}</strong> · {{ list.length }}</span>
            <button type="button" class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium hover:bg-ai/15" @click="emit('clear-tag')">Show all <Icon name="close" :size="13" /></button>
        </p>

        <!-- Featured -->
        <component
            :is="target(featured) ? 'a' : 'button'"
            v-if="featured"
            :href="target(featured)?.href"
            :target="target(featured)?.external ? '_blank' : undefined"
            :rel="target(featured)?.external ? 'noopener noreferrer' : undefined"
            type="button"
            class="card group mb-4 grid w-full overflow-hidden text-left transition hover:border-ai/40 hover:shadow-[0_18px_40px_-24px_rgb(0_0_0/.45)] md:grid-cols-[1.25fr_1fr]"
            @click="onCard($event, featured)"
        >
            <div class="overflow-hidden md:border-r md:border-line">
                <ProjectCover :post="featured" class="transition duration-500 group-hover:scale-[1.02]" />
            </div>
            <div class="flex flex-col p-5">
                <p class="flex items-center gap-2 text-xs font-medium text-faint"><Icon name="pin" :size="13" /> Featured · {{ featured.when }}</p>
                <h3 class="mt-2 text-2xl font-bold leading-tight tracking-[-0.02em]">{{ featured.name }}</h3>
                <p class="mt-1 text-balance text-[15px] font-medium leading-snug text-ink/90">{{ featured.title }}</p>
                <p class="mt-1 text-sm text-muted">{{ featured.kind }}<template v-if="featured.client"> · {{ featured.client }}</template></p>
                <p class="mt-3 line-clamp-3 text-[14.5px] leading-relaxed text-ink/80">{{ featured.body[0] }}</p>
                <div class="mt-auto flex flex-wrap items-center justify-between gap-3 pt-4">
                    <span class="flex items-center gap-1.5"><TechLogo v-for="t in brandTags(featured)" :key="t" :name="t" :size="15" tile /></span>
                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-ai-text">
                        {{ target(featured)?.label || 'View project' }}
                        <Icon :name="target(featured)?.external ? 'external' : 'arrow'" :size="15" class="transition group-hover:translate-x-0.5" />
                    </span>
                </div>
            </div>
        </component>

        <!-- Grid -->
        <ul class="grid gap-4 sm:grid-cols-2">
            <li v-for="p in rest" :key="p.id">
                <component
                    :is="target(p) ? 'a' : 'button'"
                    :href="target(p)?.href"
                    :target="target(p)?.external ? '_blank' : undefined"
                    :rel="target(p)?.external ? 'noopener noreferrer' : undefined"
                    type="button"
                    class="card group flex h-full w-full flex-col overflow-hidden text-left transition hover:-translate-y-0.5 hover:border-ai/40 hover:shadow-[0_18px_40px_-24px_rgb(0_0_0/.45)]"
                    :aria-label="`${p.name} — ${target(p)?.label || 'View details'}`"
                    @click="onCard($event, p)"
                >
                    <div class="overflow-hidden border-b border-line">
                        <ProjectCover :post="p" compact class="transition duration-500 group-hover:scale-[1.03]" />
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <p class="flex items-center justify-between gap-2 text-xs text-faint">
                            <span class="truncate font-medium text-muted">{{ p.kind }}</span>
                            <span class="shrink-0 font-mono">{{ p.when }}</span>
                        </p>
                        <h3 class="mt-1.5 text-[17px] font-bold leading-tight tracking-[-0.015em]">{{ p.name }}</h3>
                        <p class="mt-1 line-clamp-2 text-[13.5px] leading-snug text-ink/80">{{ p.title }}</p>
                        <p v-if="p.client" class="mt-1 truncate text-[13px] text-muted">{{ p.client }}</p>
                        <div class="mt-auto flex items-center justify-between gap-2 pt-3.5">
                            <span class="flex items-center gap-1"><TechLogo v-for="t in brandTags(p).slice(0, 4)" :key="t" :name="t" :size="13" tile /></span>
                            <span class="inline-flex shrink-0 items-center gap-1 text-[13px] font-semibold text-ai-text">
                                {{ target(p) ? (target(p).external ? (target(p).label.includes('GitHub') ? 'GitHub' : 'Visit') : 'Case study') : 'Details' }}
                                <Icon :name="target(p)?.external ? 'external' : 'arrow'" :size="14" class="transition group-hover:translate-x-0.5" />
                            </span>
                        </div>
                    </div>
                </component>
            </li>
        </ul>
    </div>
</template>

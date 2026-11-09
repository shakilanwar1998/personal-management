<script setup>
import { ref } from 'vue';
import Icon from './Icon.vue';
import Avatar from './Avatar.vue';
import PostAttachment from './PostAttachment.vue';
import ProjectCover from './ProjectCover.vue';
import TechLogo from './TechLogo.vue';
import { logo } from '../logos.js';
import { profile } from '../data.js';
import { caseUrl, hasCase } from '../cases/index.js';

const props = defineProps({
    post: { type: Object, required: true },
    activeTag: { type: String, default: null },
    expandedDefault: { type: Boolean, default: false },
});
const emit = defineEmits(['tag', 'ask', 'case']);

// Footer links: the case study first (when there is one), then the post's other links.
// A live site stays the main button; otherwise the case study is.
const hasStudy = hasCase(props.post.id);
const links = [
    ...(hasStudy ? [{ label: 'Read the case study', href: caseUrl(props.post.id), primary: !props.post.url, case: true }] : []),
    ...(props.post.links || [])
        .filter((l) => !l.href.startsWith('/case-studies/'))
        .map((l) => ({ ...l, primary: l.primary && (!hasStudy || !!props.post.url) })),
];
function onLink(e, l) {
    if (l.case && !(e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button === 1)) {
        e.preventDefault();
        emit('case', props.post);
    }
}

// Long posts start folded; shared links (#post-id) and the project dialog open them in full.
const expanded = ref(props.expandedDefault || location.hash === `#post-${props.post.id}`);
const long = props.post.body.length > 1 || props.post.body[0].length > 240;
const hasLogo = (t) => !!logo(t);

const statCols = { 1: 'grid-cols-1', 2: 'grid-cols-2', 3: 'grid-cols-3', 4: 'grid-cols-2 sm:grid-cols-4' };

const copied = ref(false);
async function copyLink() {
    const url = `${location.origin}${location.pathname}#post-${props.post.id}`;
    try {
        if (navigator.share && matchMedia('(pointer: coarse)').matches) {
            await navigator.share({ title: `${props.post.name} · ${props.post.title}`, url });
            return;
        }
        await navigator.clipboard.writeText(url);
        copied.value = true;
        setTimeout(() => (copied.value = false), 1600);
    } catch (e) {
        /* user cancelled share or clipboard blocked */
    }
}

const kindTone = {
    'Client project': 'text-accent-text',
    'At Dropmatix': 'text-ai-text',
    'At Okkhor': 'text-human-text',
    Community: 'text-ok-text',
    'Early work': 'text-muted',
    'Own product': 'text-human-text',
    'Open source': 'text-ok-text',
    Prototype: 'text-muted',
    'Side project': 'text-muted',
};
</script>

<template>
    <article :id="`post-${post.id}`" tabindex="-1" class="card scroll-mt-[120px] p-4 outline-none sm:p-5 md:scroll-mt-16" :aria-labelledby="`post-${post.id}-title`">
        <p v-if="post.pinned" class="mb-3 flex items-center gap-1.5 text-xs font-medium text-faint">
            <Icon name="pin" :size="14" /> Pinned
        </p>

        <header class="flex items-start gap-3">
            <Avatar :size="40" />
            <div class="min-w-0 flex-1">
                <p class="flex flex-wrap items-center gap-x-1.5 text-sm leading-tight">
                    <span class="font-semibold">{{ profile.name }}</span>
                    <span class="text-faint">·</span>
                    <time class="text-faint">{{ post.when }}</time>
                </p>
                <p class="mt-1 truncate text-xs text-muted">
                    <span class="font-medium" :class="kindTone[post.kind] || 'text-muted'">{{ post.kind }}</span>
                    <template v-if="post.client"><span class="text-faint"> · </span>{{ post.client }}</template>
                </p>
            </div>
        </header>

        <h3 :id="`post-${post.id}-title`" class="mt-3.5 text-[20px] font-bold leading-tight tracking-[-0.02em] sm:text-[22px]">
            {{ post.name }}
        </h3>
        <p class="mt-1 text-balance text-[15.5px] font-medium leading-snug text-ink/90 sm:text-base">{{ post.title }}</p>
        <div class="mt-2 space-y-2.5 text-pretty text-[15px] leading-relaxed text-ink/85">
            <p :class="{ 'line-clamp-3': long && !expanded }">{{ post.body[0] }}</p>
            <template v-if="expanded">
                <p v-for="(p, i) in post.body.slice(1)" :key="i">{{ p }}</p>
            </template>
        </div>
        <button v-if="long" type="button" class="mt-1 text-sm font-medium text-ai-text hover:underline" :aria-expanded="expanded" @click="expanded = !expanded">
            {{ expanded ? 'Show less' : post.attachments?.length ? 'Read more & see how it works' : 'Read more' }}
        </button>

        <div class="mt-4 overflow-hidden rounded-2xl border border-line">
            <ProjectCover :post="post" />
        </div>

        <div v-if="post.attachments?.length && (expanded || !long)" class="mt-4 space-y-3">
            <PostAttachment v-for="(a, i) in post.attachments" :key="i" :a="a" />
        </div>

        <dl v-if="post.stats?.length" class="mt-4 grid gap-px overflow-hidden rounded-2xl border border-line bg-line" :class="statCols[post.stats.length] || statCols[4]">
            <div v-for="s in post.stats" :key="s.label" class="min-w-0 bg-surface px-3 py-2.5">
                <dt class="sr-only">{{ s.label }}</dt>
                <dd class="text-lg font-semibold tabular-nums tracking-tight">{{ s.value }}</dd>
                <dd class="text-[11.5px] leading-snug text-muted">{{ s.label }}</dd>
            </div>
        </dl>

        <p class="mt-3.5 flex flex-wrap gap-1.5" aria-label="Technologies">
            <button
                v-for="t in post.tags"
                :key="t"
                type="button"
                class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[12.5px] font-medium transition"
                :class="activeTag === t ? 'border-ai/50 bg-ai/15 text-ai-text' : 'border-line bg-raised/40 text-ink/85 hover:border-ai/40 hover:text-ink'"
                :aria-pressed="activeTag === t"
                :title="`Show posts tagged ${t}`"
                @click="emit('tag', t)"
            >
                <TechLogo v-if="hasLogo(t)" :name="t" :size="13" />
                {{ t }}
            </button>
        </p>

        <footer class="mt-4 flex flex-wrap items-center gap-2 border-t border-line pt-3">
            <a
                v-for="l in links"
                :key="l.href"
                :href="l.href"
                :target="l.external ? '_blank' : undefined"
                :rel="l.external ? 'noopener noreferrer' : undefined"
                :class="l.primary ? 'btn-primary h-9' : 'btn-ghost h-9'"
                @click="onLink($event, l)"
            >
                {{ l.label }} <Icon :name="l.external ? 'external' : 'arrow'" :size="15" />
            </a>
            <button type="button" class="btn h-9 px-3 text-muted hover:bg-raised hover:text-ink" @click="emit('ask', post)">
                <Icon name="chat" :size="16" /> Want this?
            </button>
            <button type="button" class="btn ml-auto h-9 px-3 text-muted hover:bg-raised hover:text-ink" @click="copyLink" :aria-label="copied ? 'Link copied' : `Share link to ${post.name}`">
                <Icon :name="copied ? 'check' : 'share'" :size="16" />
                <span class="sr-only sm:not-sr-only sm:text-xs">{{ copied ? 'Copied' : 'Share' }}</span>
            </button>
        </footer>
    </article>
</template>

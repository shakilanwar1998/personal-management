<script setup>
// A project's case study: used in the modal and on the standalone /case-studies/<id> page.
import { computed } from 'vue';
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';
import ProjectCover from './ProjectCover.vue';
import PostAttachment from './PostAttachment.vue';
import ClientAvatar from './ClientAvatar.vue';
import { testimonials } from '../data.js';
import { logo } from '../logos.js';

const props = defineProps({
    post: { type: Object, required: true },
    study: { type: Object, required: true },
});
const emit = defineEmits(['ask']);

const results = computed(() => props.study.results || props.post.stats || []);
const statCols = { 1: 'sm:grid-cols-1', 2: 'sm:grid-cols-2', 3: 'sm:grid-cols-3', 4: 'sm:grid-cols-4' };

// Client reviews tied to this project: from the reviews list and from the post's own review cards.
const reviews = computed(() => {
    const fromList = testimonials.filter((t) => t.post === props.post.id);
    const fromPost = (props.post.attachments || [])
        .filter((a) => a.type === 'review')
        .map((a) => ({ quote: a.quote, name: a.name, platform: a.platform, rating: a.rating, date: a.date, company: a.job, anonymous: a.name.endsWith('client') }));
    const seen = new Set();
    return [...fromList, ...fromPost].filter((r) => !seen.has(r.quote) && seen.add(r.quote));
});
const hasLogo = (t) => !!logo(t);
</script>

<template>
    <article class="space-y-8" :aria-labelledby="`case-${post.id}-title`">
        <!-- hero -->
        <header>
            <div class="overflow-hidden rounded-2xl border border-line">
                <ProjectCover :post="post" />
            </div>
            <p class="eyebrow mt-6">Case study · {{ post.kind }}</p>
            <h1 :id="`case-${post.id}-title`" class="mt-1.5 text-balance text-[28px] font-bold leading-tight tracking-[-0.025em] sm:text-[36px]">{{ post.name }}</h1>
            <p class="mt-2 text-balance text-[17px] font-medium leading-snug text-ink/90 sm:text-[19px]">{{ post.title }}</p>
            <p v-if="study.summary" class="mt-4 text-pretty text-[16px] leading-relaxed text-ink/80">{{ study.summary }}</p>

            <dl v-if="study.facts?.length" class="mt-5 grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-line bg-line sm:grid-cols-4">
                <div v-for="f in study.facts" :key="f.label" class="bg-surface px-3.5 py-3">
                    <dt class="font-mono text-[10.5px] uppercase tracking-[0.14em] text-faint">{{ f.label }}</dt>
                    <dd class="mt-1 text-[13.5px] font-medium leading-snug">{{ f.value }}</dd>
                </div>
            </dl>

            <p class="mt-4 flex flex-wrap gap-1.5" aria-label="Technologies">
                <span v-for="t in post.tags" :key="t" class="inline-flex items-center gap-1.5 rounded-full border border-line bg-raised/40 px-2.5 py-1 text-[12.5px] font-medium text-ink/85">
                    <TechLogo v-if="hasLogo(t)" :name="t" :size="13" />{{ t }}
                </span>
            </p>
        </header>

        <!-- story -->
        <section v-for="(sec, i) in study.sections" :key="i" class="space-y-3">
            <h2 class="flex items-baseline gap-3 text-[20px] font-semibold tracking-tight sm:text-[22px]">
                <span class="font-mono text-[12px] font-medium text-faint">{{ String(i + 1).padStart(2, '0') }}</span>{{ sec.heading }}
            </h2>
            <p v-for="(p, j) in sec.body || []" :key="j" class="text-pretty text-[15.5px] leading-relaxed text-ink/85">{{ p }}</p>
            <ul v-if="sec.bullets?.length" class="space-y-1.5">
                <li v-for="b in sec.bullets" :key="b" class="flex gap-2.5 text-[15px] leading-relaxed text-ink/85">
                    <span class="mt-[9px] h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>{{ b }}
                </li>
            </ul>
            <ol v-if="sec.steps?.length" class="grid gap-2.5 sm:grid-cols-2">
                <li v-for="(s, k) in sec.steps" :key="s.title" class="rounded-2xl border border-line bg-raised/30 p-3.5">
                    <p class="flex items-center gap-2 text-[14.5px] font-semibold">
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-ink font-mono text-[11px] text-bg">{{ k + 1 }}</span>{{ s.title }}
                    </p>
                    <p class="mt-1.5 text-[14px] leading-snug text-muted">{{ s.text }}</p>
                </li>
            </ol>
            <PostAttachment v-if="sec.figure" :a="sec.figure" />
            <aside v-if="sec.callout" class="rounded-2xl border border-ai/30 bg-ai/10 p-4">
                <p class="text-[14.5px] font-semibold text-ai-text">{{ sec.callout.title }}</p>
                <p class="mt-1 text-[14.5px] leading-relaxed text-ink/85">{{ sec.callout.text }}</p>
            </aside>
        </section>

        <!-- results -->
        <section v-if="results.length" class="space-y-3">
            <h2 class="text-[20px] font-semibold tracking-tight sm:text-[22px]">Results</h2>
            <dl class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-line bg-line" :class="statCols[results.length] || statCols[4]">
                <div v-for="s in results" :key="s.label" class="bg-surface px-4 py-3.5">
                    <dt class="sr-only">{{ s.label }}</dt>
                    <dd class="text-[22px] font-semibold tabular-nums tracking-tight">{{ s.value }}</dd>
                    <dd class="mt-0.5 text-[12.5px] leading-snug text-muted">{{ s.label }}</dd>
                </div>
            </dl>
        </section>

        <!-- reviews -->
        <section v-if="reviews.length" class="space-y-3">
            <h2 class="text-[20px] font-semibold tracking-tight sm:text-[22px]">What the client said</h2>
            <figure v-for="r in reviews" :key="r.quote" class="rounded-2xl border border-line bg-raised/30 p-4">
                <blockquote class="text-[15.5px] leading-relaxed text-ink/90">“{{ r.quote }}”</blockquote>
                <figcaption class="mt-3 flex items-center gap-2.5 text-sm">
                    <ClientAvatar :review="r" :size="34" />
                    <span class="leading-tight">
                        <span class="block font-semibold">{{ r.name }}</span>
                        <span class="block text-xs text-muted">{{ r.platform }} review<template v-if="r.date"> · {{ r.date }}</template><template v-if="r.rating"> · ★ {{ r.rating }}</template></span>
                    </span>
                </figcaption>
            </figure>
        </section>

        <!-- lessons -->
        <section v-if="study.lessons?.length" class="space-y-3">
            <h2 class="text-[20px] font-semibold tracking-tight sm:text-[22px]">What I learned</h2>
            <ul class="space-y-1.5">
                <li v-for="l in study.lessons" :key="l" class="flex gap-2.5 text-[15px] leading-relaxed text-ink/85">
                    <Icon name="sparkle" :size="16" class="mt-[3px] shrink-0 text-ai-text" />{{ l }}
                </li>
            </ul>
        </section>

        <!-- call to action -->
        <section class="rounded-2xl border border-line bg-raised/40 p-5 text-center">
            <p class="text-[17px] font-semibold tracking-tight">Have a process like this?</p>
            <p class="mt-1 text-sm text-muted">Tell me what you do today and I’ll reply with a plan.</p>
            <button type="button" class="btn-primary mx-auto mt-3" @click="emit('ask', post)"><Icon name="send" :size="16" /> Message Shakil</button>
        </section>
    </article>
</template>

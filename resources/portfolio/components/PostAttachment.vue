<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';

const props = defineProps({ a: { type: Object, required: true } });

const kindStyle = {
    trigger: { dot: 'bg-ink', label: 'Trigger' },
    ai: { dot: 'bg-ai', label: 'AI' },
    rule: { dot: 'bg-muted', label: 'Rule' },
    human: { dot: 'bg-human', label: 'Human' },
    out: { dot: 'bg-accent', label: 'Output' },
};

const sourceStyle = {
    Vinted: 'border-line bg-raised text-muted',
    Rule: 'border-line bg-raised text-ink',
    AI: 'border-ai/30 bg-ai/10 text-ai-text',
    Manual: 'border-human/30 bg-human/10 text-human-text',
};

const toneBar = { ok: 'bg-ok', warn: 'bg-warn', muted: 'bg-faint/60' };

const barTotal = computed(() => (props.a.rows || []).reduce((s, r) => s + (r.value || 0), 0));
const kindsUsed = computed(() =>
    props.a.type === 'pipeline' ? [...new Set(props.a.steps.map((s) => s.kind))] : [],
);
</script>

<template>
    <figure class="overflow-hidden rounded-2xl border border-line bg-raised/40">
        <!-- Pipeline: horizontal flow of steps, scrolls on small screens -->
        <div v-if="a.type === 'pipeline'" class="p-3 sm:p-4">
            <ol class="no-scrollbar -mx-1 flex snap-x items-stretch gap-0 overflow-x-auto px-1 pb-1 [mask-image:linear-gradient(to_right,black_90%,transparent)]" aria-label="Workflow steps">
                <li v-for="(s, i) in a.steps" :key="i" class="flex snap-start items-center">
                    <div class="min-w-[104px] whitespace-nowrap rounded-xl border border-line bg-surface px-3 py-2.5 shadow-[0_1px_0_rgb(0_0_0/.03)]">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 shrink-0 rounded-full" :class="kindStyle[s.kind].dot"></span>
                            <span class="text-[13px] font-semibold leading-tight">{{ s.label }}</span>
                        </div>
                        <p class="mt-1 font-mono text-[10.5px] text-faint">{{ s.sub }}</p>
                    </div>
                    <svg v-if="i < a.steps.length - 1" width="22" height="10" viewBox="0 0 22 10" class="shrink-0 text-faint" aria-hidden="true">
                        <path d="M1 5h17" stroke="currentColor" stroke-width="1.5" stroke-dasharray="3 3" class="animate-flow" />
                        <path d="M15 1.5 19 5l-4 3.5" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" />
                    </svg>
                </li>
            </ol>
            <figcaption class="mt-2.5 flex flex-wrap gap-x-3 gap-y-1">
                <span v-for="k in kindsUsed" :key="k" class="inline-flex items-center gap-1.5 font-mono text-[10.5px] text-faint">
                    <span class="h-1.5 w-1.5 rounded-full" :class="kindStyle[k].dot"></span>{{ kindStyle[k].label }}
                </span>
            </figcaption>
        </div>

        <!-- Fields with their source -->
        <div v-else-if="a.type === 'fields'" class="p-3 sm:p-4">
            <figcaption class="mb-2.5 flex items-center justify-between gap-2">
                <span class="eyebrow">{{ a.caption }}</span>
                <span v-if="a.illustrative" class="font-mono text-[10px] text-faint">illustrative</span>
            </figcaption>
            <dl class="divide-y divide-line overflow-hidden rounded-xl border border-line bg-surface">
                <div v-for="r in a.rows" :key="r.field" class="flex items-center gap-3 px-3 py-2">
                    <dt class="w-24 shrink-0 text-xs text-faint sm:w-28">{{ r.field }}</dt>
                    <dd class="min-w-0 flex-1 truncate text-sm font-medium">{{ r.value }}</dd>
                    <dd class="shrink-0">
                        <span class="inline-flex items-center rounded-md border px-1.5 py-0.5 font-mono text-[10.5px]" :class="sourceStyle[r.source]">{{ r.source }}</span>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Stacked bar -->
        <div v-else-if="a.type === 'bars'" class="p-3 sm:p-4">
            <figcaption class="eyebrow mb-2.5">{{ a.caption }}</figcaption>
            <div class="flex h-2.5 overflow-hidden rounded-full bg-line" role="img" :aria-label="a.rows.map((r) => `${r.label}: ${r.value}`).join(', ')">
                <span v-for="r in a.rows" :key="r.label" :class="toneBar[r.tone]" :style="{ width: (r.value / barTotal) * 100 + '%' }"></span>
            </div>
            <ul class="mt-2.5 flex flex-wrap gap-x-4 gap-y-1">
                <li v-for="r in a.rows" :key="r.label" class="inline-flex items-center gap-1.5 text-xs text-muted">
                    <span class="h-2 w-2 rounded-sm" :class="toneBar[r.tone]"></span>
                    <span class="font-semibold tabular-nums text-ink">{{ r.value }}</span> {{ r.label }}
                </li>
            </ul>
        </div>

        <!-- Score card -->
        <div v-else-if="a.type === 'score'" class="grid gap-4 p-3 sm:grid-cols-[auto_1fr] sm:p-4">
            <div class="flex items-center gap-3 sm:flex-col sm:items-start sm:gap-1">
                <div class="relative grid h-20 w-20 place-items-center">
                    <svg viewBox="0 0 36 36" class="absolute inset-0 -rotate-90" aria-hidden="true">
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="rgb(var(--line))" stroke-width="3" />
                        <circle cx="18" cy="18" r="15.5" fill="none" stroke="rgb(var(--accent))" stroke-width="3" stroke-linecap="round" :stroke-dasharray="`${(a.score / 10) * 97.4} 97.4`" />
                    </svg>
                    <span class="text-2xl font-semibold tabular-nums">{{ a.score }}</span>
                </div>
                <div>
                    <p class="eyebrow">Buy score</p>
                    <p class="text-sm font-medium">{{ a.item }}</p>
                </div>
            </div>
            <div class="space-y-2.5">
                <div v-for="p in a.parts" :key="p.label">
                    <div class="flex justify-between text-xs"><span class="text-muted">{{ p.label }}</span><span class="font-mono tabular-nums text-faint">{{ Math.round(p.value * 100) }}</span></div>
                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-line"><div class="h-full rounded-full bg-ink/80" :style="{ width: p.value * 100 + '%' }"></div></div>
                </div>
                <ul class="space-y-1 pt-1">
                    <li v-for="r in a.rejected" :key="r" class="flex items-start gap-1.5 text-xs text-muted">
                        <span class="mt-px inline-flex shrink-0 items-center rounded border border-ai/30 bg-ai/10 px-1 font-mono text-[10px] text-ai-text">AI set aside</span>
                        <span class="line-through decoration-faint/60">{{ r }}</span>
                    </li>
                </ul>
                <p v-if="a.illustrative" class="font-mono text-[10px] text-faint">illustrative example</p>
            </div>
        </div>

        <!-- Chat -->
        <div v-else-if="a.type === 'chat'" class="space-y-2 p-3 sm:p-4">
            <div v-for="(m, i) in a.messages" :key="i" class="flex" :class="m.from === 'user' ? 'justify-end' : 'justify-start'">
                <div class="max-w-[85%] rounded-2xl px-3 py-2 text-sm" :class="m.from === 'user' ? 'rounded-br-md bg-ink text-bg' : 'rounded-bl-md border border-line bg-surface'">
                    <p>{{ m.text }}</p>
                    <div v-if="m.card" class="mt-2 flex items-center gap-2.5 rounded-xl border border-line bg-raised/60 p-2">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg" :class="m.card.locked ? 'bg-line text-faint' : 'bg-accent text-accent-ink'">
                            <Icon :name="m.card.locked ? 'lock' : 'play'" :size="14" />
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-[13px] font-semibold">{{ m.card.title }}</span>
                            <span class="block truncate text-[11px] text-faint">{{ m.card.meta }}</span>
                        </span>
                    </div>
                </div>
            </div>
            <p v-if="a.illustrative" class="pt-1 font-mono text-[10px] text-faint">illustrative conversation</p>
        </div>

        <!-- Masking before/after -->
        <div v-else-if="a.type === 'mask'" class="p-3 sm:p-4">
            <figcaption class="mb-2.5 flex items-center justify-between gap-2">
                <span class="eyebrow">{{ a.caption }}</span>
                <span v-if="a.illustrative" class="font-mono text-[10px] text-faint">illustrative</span>
            </figcaption>
            <ul class="space-y-1.5">
                <li v-for="r in a.rows" :key="r.from" class="grid grid-cols-[1fr_auto_1fr] items-center gap-2 font-mono text-[12px]">
                    <span class="truncate rounded-lg border border-line bg-surface px-2 py-1.5 text-muted line-through decoration-human/70">{{ r.from }}</span>
                    <Icon name="arrow" :size="14" class="text-faint" />
                    <span class="truncate rounded-lg border border-ai/30 bg-ai/10 px-2 py-1.5 text-ai-text">{{ r.to }}</span>
                </li>
            </ul>
        </div>

        <!-- Client review from a freelance platform (copied word for word) -->
        <div v-else-if="a.type === 'review'" class="p-3 sm:p-4">
            <figcaption class="mb-2 flex items-center justify-between gap-2">
                <span class="eyebrow inline-flex items-center gap-1.5"><TechLogo :name="a.platform" :size="12" /> Client review · {{ a.platform }}</span>
                <span class="inline-flex items-center gap-1 text-[12.5px] font-semibold text-ink">
                    <span class="flex text-warn" aria-hidden="true"><Icon v-for="n in 5" :key="n" name="star" :size="12" /></span>
                    <span class="sr-only">Rated</span> {{ a.rating }}
                </span>
            </figcaption>
            <blockquote class="text-[15px] font-medium leading-relaxed text-ink/90">“{{ a.quote }}”</blockquote>
            <p class="mt-2 text-xs text-muted">{{ a.name }}<span class="text-faint"> · {{ a.job }} · {{ a.date }}</span></p>
        </div>

        <!-- Terminal / log -->
        <div v-else-if="a.type === 'terminal'" class="bg-[#0b0d10] p-3 text-[#e6e8ec] sm:p-4">
            <div class="mb-2.5 flex items-center gap-1.5">
                <span class="h-2.5 w-2.5 rounded-full bg-[#ff5f57]"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-[#febc2e]"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-[#28c840]"></span>
                <span class="ml-2 font-mono text-[11px] text-[#8a93a3]">{{ a.title }}</span>
                <span v-if="a.illustrative" class="ml-auto font-mono text-[10px] text-[#6b7280]">illustrative</span>
            </div>
            <pre class="no-scrollbar overflow-x-auto font-mono text-[12px] leading-relaxed"><code><span v-for="(l, i) in a.lines" :key="i" class="block whitespace-pre"><span class="inline-block w-12 select-none text-[#6b7280]">{{ l.t }}</span><span :class="{ 'text-[#ffba52]': l.tone === 'warn', 'text-[#3ddc97]': l.tone === 'ok', 'text-[#6b7280]': l.tone === 'muted', 'text-[#b0a6ff]': l.tone === 'ai' }">{{ l.text }}</span></span></code></pre>
        </div>
    </figure>
</template>

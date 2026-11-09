<script setup>
import Icon from './Icon.vue';
import { posts, process, recipes } from '../data.js';

const emit = defineEmits(['open-post', 'ask']);
const title = (id) => posts.find((p) => p.id === id)?.name || id;
</script>

<template>
    <div class="space-y-4">
        <div class="card p-4 sm:p-5">
            <p class="eyebrow">Automations I build</p>
            <h2 class="mt-1.5 text-balance text-xl font-semibold tracking-tight">Pick a starting point. I’ll fit it to your stack.</h2>
            <p class="mt-1.5 text-[15px] text-muted">Each one is built from work I’ve shipped. Tap a template to see where.</p>
        </div>

        <article v-for="r in recipes" :key="r.id" class="card group overflow-hidden">
            <div class="p-4 sm:p-5">
                <h3 class="text-lg font-semibold tracking-tight">{{ r.title }}</h3>
                <p class="mt-1 text-pretty text-[15px] leading-relaxed text-muted">{{ r.pitch }}</p>

                <!-- the template as a mini workflow -->
                <ol class="mt-4 grid gap-1.5 sm:grid-cols-[auto_1fr]" :aria-label="`${r.title} workflow`">
                    <li class="contents">
                        <span class="flex items-center gap-2 font-mono text-[10.5px] uppercase tracking-[0.12em] text-faint sm:justify-end sm:pt-2.5">
                            <Icon name="bolt" :size="13" class="text-accent-text" /> When
                        </span>
                        <span class="rounded-xl border border-line bg-raised/60 px-3 py-2 text-sm font-medium">{{ r.trigger }}</span>
                    </li>
                    <li class="contents">
                        <span class="flex items-center gap-2 font-mono text-[10.5px] uppercase tracking-[0.12em] text-faint sm:justify-end sm:pt-2.5">
                            <Icon name="flow" :size="13" /> Then
                        </span>
                        <span class="flex flex-wrap items-center gap-1.5">
                            <!-- each chevron travels with the step after it, so it never dangles at a line end -->
                            <span v-for="(s, i) in r.steps" :key="s" class="inline-flex items-center gap-1.5">
                                <Icon v-if="i > 0" name="chevronRight" :size="14" class="text-faint" />
                                <span class="rounded-lg border border-line bg-surface px-2.5 py-1.5 text-[13px]">{{ s }}</span>
                            </span>
                        </span>
                    </li>
                    <li class="contents">
                        <span class="flex items-center gap-2 font-mono text-[10.5px] uppercase tracking-[0.12em] text-faint sm:justify-end sm:pt-2.5">
                            <Icon name="check" :size="13" class="text-ok-text" /> Result
                        </span>
                        <span class="rounded-xl bg-accent/90 px-3 py-2 text-sm font-semibold text-accent-ink">{{ r.outcome }}</span>
                    </li>
                </ol>
            </div>
            <footer class="flex flex-col gap-3 border-t border-line bg-raised/30 px-4 py-3 sm:flex-row sm:items-center sm:px-5">
                <div class="min-w-0 flex-1">
                    <p class="eyebrow mb-1">Shipped in</p>
                    <div class="flex flex-wrap gap-1.5">
                        <button v-for="id in r.posts" :key="id" type="button" class="max-w-full truncate rounded-full border border-line bg-surface px-2.5 py-1 text-xs font-medium hover:border-ai/50" @click="emit('open-post', id)">
                            {{ title(id) }}
                        </button>
                    </div>
                </div>
                <button type="button" class="btn-ghost shrink-0" @click="emit('ask', r)">I need this <Icon name="arrow" :size="15" /></button>
            </footer>
        </article>

        <section class="card p-4 sm:p-5" aria-labelledby="process-title">
            <p class="eyebrow">How a project runs</p>
            <h2 id="process-title" class="mt-1.5 text-lg font-semibold tracking-tight">Small pilot first. Then make it boring.</h2>
            <ol class="mt-4 grid gap-3 sm:grid-cols-2">
                <li v-for="(p, i) in process" :key="p.step" class="flex gap-3 rounded-2xl border border-line p-3">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ink font-mono text-xs font-semibold text-bg">{{ i + 1 }}</span>
                    <span>
                        <span class="block text-sm font-semibold">{{ p.step }}</span>
                        <span class="block text-[13.5px] leading-snug text-muted">{{ p.text }}</span>
                    </span>
                </li>
            </ol>
        </section>
    </div>
</template>

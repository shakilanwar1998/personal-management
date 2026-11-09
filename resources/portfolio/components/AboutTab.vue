<script setup>
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';
import ClientAvatar from './ClientAvatar.vue';
import ProjectCover from './ProjectCover.vue';
import { caseUrl, hasCase } from '../cases/index.js';
import { education, experience, marketplaces, posts, profile, skillGroups, testimonials } from '../data.js';
import { logo } from '../logos.js';

// Company / school badge: a real logo when one is set in data.js, otherwise a monogram tile.
const TILE = {
    violet: 'from-[#8b7cff] to-[#3b2f99]',
    amber: 'from-[#fcd34d] to-[#b45309]',
    blue: 'from-[#93c5fd] to-[#1d4ed8]',
};

const emit = defineEmits(['case']);

// Projects done at each place, as thumbnails. A live site opens in a new tab; otherwise the case study modal.
const projectsOf = (e) => (e.projects || []).map((id) => posts.find((p) => p.id === id)).filter(Boolean);
const hrefOf = (p) => p.url || (hasCase(p.id) ? caseUrl(p.id) : null);
function openThumb(ev, p) {
    if (p.url || !hasCase(p.id) || ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.altKey) return;
    ev.preventDefault();
    emit('case', p);
}

// Every brand logo used across the skills, once.
const tools = [...new Set(skillGroups.flatMap((g) => g.skills.flatMap((s) => s.logos || [])))].filter((k) => logo(k)?.kind === 'brand');
</script>

<template>
    <div class="space-y-4">
        <section class="card p-4 sm:p-5" aria-labelledby="about-title">
            <p class="eyebrow">About</p>
            <h2 id="about-title" class="mt-1.5 text-balance text-[24px] font-bold leading-tight tracking-[-0.02em] sm:text-[28px]">
                Software engineer by trade. Automation engineer by obsession.
            </h2>
            <div class="mt-3 space-y-3 text-pretty text-[15px] leading-relaxed text-ink/85">
                <p>
                    I’ve spent seven years building on Laravel and Node.js, mostly in e-commerce: marketplaces, suppliers, stock, orders and the integrations that tie them together. Somewhere along the way I noticed that the most valuable code I write is code that lets people stop doing something by hand.
                </p>
                <p>
                    Now I focus on business automation: n8n and custom pipelines, AI that drafts and a person who approves, and integrations that keep every system telling the same story. I build with AI coding agents like Claude Code every day, and I care just as much about the boring parts: logs, retries, tests and a handover your team can actually run.
                </p>
            </div>
            <blockquote class="mt-4 border-l-2 border-accent pl-3 text-[14.5px] font-medium text-muted">“{{ profile.principle }}”</blockquote>
        </section>

        <section class="card p-4 sm:p-5" aria-labelledby="exp-title">
            <h2 id="exp-title" class="flex items-center gap-2 text-lg font-semibold tracking-tight"><Icon name="briefcase" /> Experience</h2>
            <ol class="mt-5 space-y-7">
                <li v-for="e in experience" :key="e.org + e.role" class="relative pl-[60px]">
                    <component :is="e.href ? 'a' : 'span'" :href="e.href" target="_blank" rel="noopener noreferrer" class="absolute left-0 top-0 block" :title="e.org">
                        <img v-if="e.logo" :src="e.logo" :alt="`${e.org} logo`" class="h-11 w-11 rounded-xl border border-line bg-white object-contain p-1" />
                        <span v-else class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br font-mono text-[13px] font-bold text-white shadow-sm ring-1 ring-inset ring-white/20" :class="TILE[e.tone] || TILE.violet" aria-hidden="true">{{ e.mono }}</span>
                    </component>
                    <span v-if="e !== experience[experience.length - 1]" class="absolute bottom-[-24px] left-[21.5px] top-[52px] w-px bg-line"></span>

                    <p class="flex flex-wrap items-baseline justify-between gap-x-3">
                        <span class="text-[15.5px] font-semibold">{{ e.role }}</span>
                        <span class="font-mono text-xs text-faint">{{ e.when }}</span>
                    </p>
                    <p class="text-sm text-muted">
                        <a v-if="e.href" :href="e.href" target="_blank" rel="noopener noreferrer" class="font-medium text-ink/90 hover:underline">{{ e.org }}</a><template v-else>{{ e.org }}</template> · {{ e.place }}<span v-if="e.current" class="ml-2 rounded-full bg-accent/20 px-1.5 py-px text-[11px] font-medium text-accent-text">Current</span>
                    </p>

                    <!-- achievements -->
                    <dl v-if="e.achievements?.length" class="mt-3 grid grid-cols-2 gap-px overflow-hidden rounded-xl border border-line bg-line" :class="e.achievements.length > 3 ? 'sm:grid-cols-4' : 'sm:grid-cols-3'">
                        <div v-for="a in e.achievements" :key="a.label" class="bg-surface px-3 py-2.5">
                            <dt class="sr-only">{{ a.label }}</dt>
                            <dd class="text-[16px] font-semibold tabular-nums leading-tight tracking-tight">{{ a.value }}</dd>
                            <dd class="mt-0.5 text-[11.5px] leading-snug text-muted">{{ a.label }}</dd>
                        </div>
                    </dl>

                    <!-- the work -->
                    <ul class="mt-3 space-y-1">
                        <li v-for="p in e.points" :key="p" class="flex gap-2 text-[14px] leading-snug text-ink/80">
                            <span class="mt-[7px] h-1 w-1 shrink-0 rounded-full bg-faint"></span>{{ p }}
                        </li>
                    </ul>

                    <!-- projects there -->
                    <ul v-if="projectsOf(e).length" class="mt-3.5 grid grid-cols-2 gap-2.5 sm:grid-cols-3" :aria-label="`Projects at ${e.org}`">
                        <li v-for="p in projectsOf(e)" :key="p.id">
                            <component
                                :is="hrefOf(p) ? 'a' : 'span'"
                                :href="hrefOf(p)"
                                :target="p.url ? '_blank' : undefined"
                                :rel="p.url ? 'noopener noreferrer' : undefined"
                                class="group block overflow-hidden rounded-xl border border-line bg-surface transition hover:-translate-y-0.5 hover:border-ai/40"
                                :title="p.url ? `Visit ${p.name}` : `${p.name} · case study`"
                                @click="openThumb($event, p)"
                            >
                                <ProjectCover :post="p" compact />
                                <span class="flex items-center justify-between gap-2 px-2.5 py-1.5">
                                    <span class="truncate text-[12.5px] font-semibold">{{ p.name }}</span>
                                    <Icon :name="p.url ? 'external' : 'arrow'" :size="13" class="shrink-0 text-faint group-hover:text-ai-text" />
                                </span>
                            </component>
                        </li>
                    </ul>
                </li>
            </ol>
        </section>

        <div class="grid gap-4 sm:grid-cols-2">
            <section class="card p-4 sm:p-5" aria-labelledby="edu-title">
                <h2 id="edu-title" class="flex items-center gap-2 text-base font-semibold"><Icon name="cap" /> Education</h2>
                <div class="mt-3 flex gap-3">
                    <img v-if="education.logo" :src="education.logo" :alt="education.school" class="h-11 w-11 shrink-0 rounded-xl border border-line bg-white object-contain p-1" />
                    <span v-else class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br font-mono text-[11px] font-bold text-white ring-1 ring-inset ring-white/20" :class="TILE.blue" aria-hidden="true">{{ education.mono }}</span>
                    <div class="min-w-0">
                        <p class="text-[15px] font-medium">{{ education.degree }}</p>
                        <p class="text-sm text-muted">{{ education.school }}</p>
                        <p class="mt-1 font-mono text-xs text-faint">{{ education.when }} · {{ education.detail }}</p>
                    </div>
                </div>
            </section>
            <section class="card p-4 sm:p-5" aria-labelledby="where-title">
                <h2 id="where-title" class="flex items-center gap-2 text-base font-semibold"><Icon name="globe" /> Worked with teams in</h2>
                <ul class="mt-3 flex flex-wrap gap-1.5">
                    <li v-for="c in profile.clientCountries" :key="c" class="chip">{{ c }}</li>
                </ul>
                <p class="mt-3 text-xs text-faint">Based in {{ profile.location }} · Bangla (native), English (professional)</p>
            </section>
        </div>

        <section class="card p-4 sm:p-5" aria-labelledby="tools-title">
            <h2 id="tools-title" class="flex items-center gap-2 text-base font-semibold"><Icon name="grid" /> Tools & platforms I ship with</h2>
            <ul class="mt-4 grid grid-cols-4 gap-2 sm:grid-cols-6 lg:grid-cols-8">
                <li v-for="t in tools" :key="t" class="flex flex-col items-center gap-1.5 rounded-xl border border-line bg-raised/30 px-1 py-3">
                    <TechLogo :name="t" :size="22" />
                    <span class="w-full truncate text-center text-[10.5px] text-muted">{{ logo(t).title }}</span>
                </li>
            </ul>
        </section>

        <section class="card p-4 sm:p-5" aria-labelledby="free-title">
            <h2 id="free-title" class="flex items-center gap-2 text-base font-semibold"><Icon name="star" /> Freelance profiles</h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li v-for="m in marketplaces" :key="m.id">
                    <a :href="m.href" target="_blank" rel="noopener noreferrer" class="group flex h-full flex-col rounded-2xl border border-line bg-raised/30 p-4 transition hover:border-ai/40 hover:bg-raised/60">
                        <span class="flex items-center gap-2.5">
                            <TechLogo :name="m.label" :size="18" tile />
                            <span class="min-w-0 flex-1">
                                <span class="block text-[15px] font-semibold">{{ m.label }}</span>
                                <span class="flex items-center gap-1 text-sm">
                                    <Icon name="star" :size="13" class="text-warn" />
                                    <span class="font-semibold tabular-nums">{{ m.rating }}</span>
                                    <span v-if="m.reviews" class="text-muted">· {{ m.reviews }} reviews</span>
                                </span>
                            </span>
                            <Icon name="external" :size="15" class="text-faint group-hover:text-ink" />
                        </span>
                        <span class="mt-2.5 text-[13px] leading-snug text-muted">{{ m.title }}</span>
                        <span v-if="m.badges.length || m.facts.length" class="mt-3 flex flex-wrap gap-1.5">
                            <span v-for="b in m.badges" :key="b" class="chip">{{ b }}</span>
                            <span v-for="f in m.facts" :key="f.label" class="chip"><strong class="font-semibold">{{ f.value }}</strong>{{ f.label }}</span>
                        </span>
                    </a>
                </li>
            </ul>
        </section>

        <section v-if="testimonials.length" class="card p-4 sm:p-5" aria-labelledby="refs-title">
            <h2 id="refs-title" class="flex items-center gap-2 text-base font-semibold"><Icon name="chat" /> What clients say</h2>
            <ul class="mt-4 gap-3 sm:columns-2">
                <li v-for="(t, i) in testimonials" :key="i" class="mb-3 break-inside-avoid rounded-2xl border border-line bg-raised/30 p-4">
                    <p v-if="t.platform" class="mb-2 flex items-center justify-between gap-2 text-xs text-muted">
                        <span class="inline-flex items-center gap-1.5"><TechLogo :name="t.platform" :size="12" /> {{ t.platform }} review<template v-if="t.date"> · {{ t.date }}</template></span>
                        <span v-if="t.rating" class="inline-flex items-center gap-0.5 font-semibold text-ink"><Icon name="star" :size="12" class="text-warn" />{{ t.rating }}</span>
                    </p>
                    <p class="text-[14.5px] leading-relaxed text-ink/85">“{{ t.quote }}”</p>
                    <div class="mt-3 flex items-center gap-2.5">
                        <ClientAvatar :review="t" :size="38" />
                        <div class="min-w-0 leading-tight">
                            <component :is="t.url ? 'a' : 'p'" :href="t.url" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold hover:underline">{{ t.name }}</component>
                            <p class="truncate text-xs text-muted">{{ [t.anonymous ? `Name hidden by ${t.platform}` : t.role, t.country, t.company || (t.duration && `Delivered in ${t.duration}`)].filter(Boolean).join(' · ') }}</p>
                        </div>
                    </div>
                </li>
            </ul>
        </section>
    </div>
</template>

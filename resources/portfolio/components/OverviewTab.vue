<script setup>
// The landing tab: who I am, what I do, proof, selected work, where I've worked, and how to reach me.
import Icon from './Icon.vue';
import TechLogo from './TechLogo.vue';
import ProjectCover from './ProjectCover.vue';
import ClientAvatar from './ClientAvatar.vue';
import { experience, marketplaces, overview, posts, profile, recipes, testimonials } from '../data.js';
import { caseUrl, hasCase } from '../cases/index.js';

const emit = defineEmits(['tab', 'case', 'message']);

const years = new Date().getFullYear() - profile.since;
const upwork = marketplaces.find((m) => m.id === 'upwork');
const numbers = [
    { value: `${years}+`, label: 'years building for e-commerce' },
    { value: posts.length, label: 'projects, with case studies' },
    { value: `${upwork?.rating} ★`, label: `${upwork?.badges[0]} on Upwork · ${upwork?.badges[1]}` },
    { value: profile.clientCountries.length, label: 'countries I’ve shipped for' },
];

const featured = overview.featured.map((id) => posts.find((p) => p.id === id)).filter(Boolean);
const hrefOf = (p) => p.url || (hasCase(p.id) ? caseUrl(p.id) : null);
function openWork(e, p) {
    if (p.url || !hasCase(p.id) || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    e.preventDefault();
    emit('case', p);
}

const jobs = experience.filter((e) => e.logo || e.mono);
const services = recipes.slice(0, 3);
const quotes = testimonials.filter((t) => t.quote.length > 60).slice(0, 2);
</script>

<template>
    <div class="space-y-4">
        <!-- intro -->
        <section class="card p-5 sm:p-7" aria-labelledby="ov-title">
            <p class="eyebrow">Overview</p>
            <h2 id="ov-title" class="mt-2 text-balance text-[26px] font-bold leading-[1.15] tracking-[-0.025em] sm:text-[32px]">{{ overview.heading }}</h2>
            <p class="mt-3 max-w-[62ch] text-pretty text-[16px] leading-relaxed text-ink/80">{{ overview.intro }}</p>
            <div class="mt-5 flex flex-wrap gap-2">
                <button type="button" class="btn-primary" @click="emit('message')"><Icon name="send" :size="16" /> Start a conversation</button>
                <!-- CV hidden until the new format is ready -->
                <!-- <a href="/cv" target="_blank" rel="noopener" class="btn-ghost"><Icon name="external" :size="16" /> View CV</a> -->
                <button type="button" class="btn h-10 px-3 text-sm text-muted hover:bg-raised hover:text-ink" @click="emit('tab', 'projects')">All projects <Icon name="arrow" :size="15" /></button>
            </div>

            <dl class="mt-6 grid grid-cols-2 gap-px overflow-hidden rounded-2xl border border-line bg-line sm:grid-cols-4">
                <div v-for="n in numbers" :key="n.label" class="bg-surface px-4 py-3.5">
                    <dt class="sr-only">{{ n.label }}</dt>
                    <dd class="text-[24px] font-bold tabular-nums leading-none tracking-tight">{{ n.value }}</dd>
                    <dd class="mt-1.5 text-[12px] leading-snug text-muted">{{ n.label }}</dd>
                </div>
            </dl>
        </section>

        <!-- what I do -->
        <section class="grid gap-3 sm:grid-cols-3" aria-label="What I do">
            <div v-for="p in overview.pillars" :key="p.title" class="card p-4 sm:p-5">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-ai/10 text-ai-text ring-1 ring-inset ring-ai/20"><Icon :name="p.icon" :size="19" /></span>
                <h3 class="mt-3 text-[16px] font-semibold tracking-tight">{{ p.title }}</h3>
                <p class="mt-1 text-[14px] leading-relaxed text-muted">{{ p.text }}</p>
            </div>
        </section>

        <!-- selected work -->
        <section class="card p-4 sm:p-6" aria-labelledby="ov-work">
            <div class="flex items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Selected work</p>
                    <h2 id="ov-work" class="mt-1 text-[20px] font-semibold tracking-tight">Where to start</h2>
                </div>
                <button type="button" class="hidden text-sm font-medium text-ai-text hover:underline sm:inline-flex sm:items-center sm:gap-1" @click="emit('tab', 'projects')">All {{ posts.length }} projects <Icon name="arrow" :size="15" /></button>
            </div>
            <ul class="mt-4 grid gap-4 sm:grid-cols-2">
                <li v-for="p in featured" :key="p.id">
                    <a
                        :href="hrefOf(p)"
                        :target="p.url ? '_blank' : undefined"
                        :rel="p.url ? 'noopener noreferrer' : undefined"
                        class="group block overflow-hidden rounded-2xl border border-line bg-surface transition hover:-translate-y-0.5 hover:border-ai/40 hover:shadow-[0_18px_40px_-24px_rgb(0_0_0/.45)]"
                        @click="openWork($event, p)"
                    >
                        <div class="overflow-hidden border-b border-line"><ProjectCover :post="p" compact class="transition duration-500 group-hover:scale-[1.03]" /></div>
                        <div class="p-4">
                            <p class="flex items-center justify-between gap-2 text-xs text-faint">
                                <span class="truncate font-medium text-muted">{{ p.kind }}</span><span class="shrink-0 font-mono">{{ p.when }}</span>
                            </p>
                            <h3 class="mt-1.5 text-[17px] font-bold leading-tight tracking-[-0.015em]">{{ p.name }}</h3>
                            <p class="mt-1 line-clamp-2 text-[14px] leading-snug text-ink/80">{{ p.title }}</p>
                            <p class="mt-3 inline-flex items-center gap-1 text-[13px] font-semibold text-ai-text">
                                {{ p.url ? `Visit ${p.url.replace(/^https?:\/\//, '').replace(/\/$/, '')}` : 'Read the case study' }}
                                <Icon :name="p.url ? 'external' : 'arrow'" :size="14" class="transition group-hover:translate-x-0.5" />
                            </p>
                        </div>
                    </a>
                </li>
            </ul>
            <button type="button" class="btn-ghost mt-4 w-full sm:hidden" @click="emit('tab', 'projects')">All {{ posts.length }} projects <Icon name="arrow" :size="15" /></button>
        </section>

        <!-- where I've worked -->
        <section class="card p-4 sm:p-6" aria-labelledby="ov-exp">
            <div class="flex items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Experience</p>
                    <h2 id="ov-exp" class="mt-1 text-[20px] font-semibold tracking-tight">Where I’ve worked</h2>
                </div>
                <button type="button" class="inline-flex items-center gap-1 text-sm font-medium text-ai-text hover:underline" @click="emit('tab', 'about')">Full story <Icon name="arrow" :size="15" /></button>
            </div>
            <ul class="mt-4 divide-y divide-line">
                <li v-for="e in jobs" :key="e.org + e.role" class="flex items-center gap-3 py-3">
                    <img v-if="e.logo" :src="e.logo" :alt="`${e.org} logo`" class="h-10 w-10 shrink-0 rounded-xl border border-line bg-white object-contain p-1" />
                    <span v-else class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-[#93c5fd] to-[#1d4ed8] font-mono text-[12px] font-bold text-white">{{ e.mono }}</span>
                    <div class="min-w-0 flex-1 leading-tight">
                        <p class="truncate text-[15px] font-semibold">{{ e.role }} <span class="font-normal text-muted">· {{ e.org }}</span></p>
                        <p class="mt-0.5 truncate text-[13px] text-muted">{{ e.achievements?.[0] ? `${e.achievements[0].value} ${e.achievements[0].label}` : e.place }}</p>
                    </div>
                    <span class="hidden shrink-0 font-mono text-xs text-faint sm:block">{{ e.when }}</span>
                </li>
            </ul>
        </section>

        <!-- services -->
        <section class="card p-4 sm:p-6" aria-labelledby="ov-services">
            <div class="flex items-end justify-between gap-3">
                <div>
                    <p class="eyebrow">Services</p>
                    <h2 id="ov-services" class="mt-1 text-[20px] font-semibold tracking-tight">What I can build for you</h2>
                </div>
                <button type="button" class="inline-flex items-center gap-1 text-sm font-medium text-ai-text hover:underline" @click="emit('tab', 'automations')">All services <Icon name="arrow" :size="15" /></button>
            </div>
            <ul class="mt-4 grid gap-3 sm:grid-cols-3">
                <li v-for="r in services" :key="r.id" class="rounded-2xl border border-line bg-raised/30 p-4">
                    <p class="text-[15px] font-semibold tracking-tight">{{ r.title }}</p>
                    <p class="mt-1 text-[13.5px] leading-snug text-muted">{{ r.pitch }}</p>
                    <p class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-accent/10 px-2.5 py-1 text-[12px] font-medium text-accent-text"><Icon name="check" :size="13" />{{ r.outcome }}</p>
                </li>
            </ul>
        </section>

        <!-- reviews (the sidebar shows them on wide screens) -->
        <section v-if="quotes.length" class="card p-4 sm:p-6 xl:hidden" aria-labelledby="ov-reviews">
            <p class="eyebrow">Client reviews</p>
            <h2 id="ov-reviews" class="mt-1 text-[20px] font-semibold tracking-tight">What clients say</h2>
            <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                <li v-for="t in quotes" :key="t.quote" class="rounded-2xl border border-line bg-raised/30 p-4">
                    <p class="line-clamp-5 text-[14.5px] leading-relaxed text-ink/85">“{{ t.quote }}”</p>
                    <p class="mt-3 flex items-center gap-2.5 text-sm">
                        <ClientAvatar :review="t" :size="32" />
                        <span class="leading-tight"><span class="block font-semibold">{{ t.name }}</span><span class="block text-xs text-muted">{{ t.platform }} · {{ t.country || t.date }}</span></span>
                    </p>
                </li>
            </ul>
            <button type="button" class="btn-ghost mt-4 w-full" @click="emit('tab', 'about')">Read all {{ testimonials.length }} reviews <Icon name="arrow" :size="15" /></button>
        </section>

        <!-- contact -->
        <section class="card relative overflow-hidden p-5 sm:p-7" aria-labelledby="ov-contact">
            <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-accent/20 blur-3xl"></div>
            <p class="eyebrow relative">Contact</p>
            <h2 id="ov-contact" class="relative mt-1 text-balance text-[22px] font-bold tracking-tight sm:text-[26px]">Got a process that still runs on copy-paste?</h2>
            <p class="relative mt-2 max-w-[60ch] text-[15px] leading-relaxed text-muted">Tell me what your team does by hand today. I’ll reply with a plan, usually within a day ({{ profile.timezone }}).</p>
            <div class="relative mt-4 flex flex-wrap gap-2">
                <button type="button" class="btn-primary" @click="emit('message')"><Icon name="send" :size="16" /> Send a message</button>
                <a :href="`mailto:${profile.email}`" class="btn-ghost"><Icon name="mail" :size="16" /> {{ profile.email }}</a>
                <a :href="`https://wa.me/${profile.whatsapp}`" target="_blank" rel="noopener noreferrer" class="btn-ghost"><Icon name="whatsapp" :size="16" /> WhatsApp</a>
            </div>
            <p class="relative mt-4 flex flex-wrap gap-2">
                <a v-for="m in marketplaces" :key="m.id" :href="m.href" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-line px-2.5 py-1 text-[12.5px] hover:border-ai/40">
                    <TechLogo :name="m.label" :size="12" />{{ m.label }} <span class="font-semibold">★ {{ m.rating }}</span>
                </a>
            </p>
        </section>
    </div>
</template>

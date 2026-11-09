<script setup>
import Icon from './Icon.vue';
import ClientAvatar from './ClientAvatar.vue';
import TechLogo from './TechLogo.vue';
import { marketplaces, nowBuilding, profile, testimonials } from '../data.js';

const emit = defineEmits(['message', 'projects', 'reviews']);
const SHOWN = 4; // reviews in the sidebar; the rest are on the About tab
const shownReviews = testimonials.slice(0, SHOWN);
const year = new Date().getFullYear();
</script>

<template>
    <div class="space-y-4">
        <section class="card relative overflow-hidden p-4" aria-labelledby="hire-title">
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-accent/25 blur-2xl"></div>
            <p class="relative flex items-center gap-2 text-xs font-semibold text-ok-text">
                <span class="relative flex h-2 w-2"><span class="absolute h-full w-full animate-ping2 rounded-full bg-ok"></span><span class="relative h-2 w-2 rounded-full bg-ok"></span></span>
                Available now
            </p>
            <h2 id="hire-title" class="relative mt-2 text-[17px] font-semibold leading-snug tracking-tight">Got a process that still runs on copy-paste?</h2>
            <p class="relative mt-1.5 text-sm text-muted">n8n pipelines, AI features in apps you already have, and marketplace sync that stays correct.</p>
            <button type="button" class="btn-ghost relative mt-3 w-full" @click="emit('message')">
                <Icon name="send" :size="16" class="text-accent-text" /> Start a conversation
            </button>
            <a :href="`mailto:${profile.email}`" class="relative mt-2 block truncate text-center text-xs text-muted hover:text-ink">{{ profile.email }}</a>
        </section>

        <section class="card overflow-hidden" aria-labelledby="reviews-title">
            <div class="p-4 pb-3">
                <p class="eyebrow">Client reviews</p>
                <h2 id="reviews-title" class="mt-1 text-[17px] font-semibold leading-snug tracking-tight">What clients say</h2>
                <ul class="mt-2.5 flex flex-wrap gap-1.5" aria-label="Ratings by platform">
                    <li v-for="m in marketplaces" :key="m.id">
                        <a :href="m.href" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full border border-line bg-raised/40 px-2.5 py-1 text-[12.5px] transition hover:border-ai/40 hover:bg-raised" :title="`${m.label} profile`">
                            <TechLogo :name="m.label" :size="12" />
                            <span class="font-semibold">{{ m.label }}</span>
                            <span class="inline-flex items-center gap-0.5 font-semibold tabular-nums"><Icon name="star" :size="11" class="text-warn" />{{ m.rating }}</span>
                            <span v-if="m.reviews" class="text-muted">({{ m.reviews }})</span>
                            <span v-if="m.badges[0]" class="rounded-full bg-ok/15 px-1.5 text-[10.5px] font-medium text-ok-text">{{ m.badges[0] }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <ul class="divide-y divide-line border-t border-line">
                <li v-for="(t, i) in shownReviews" :key="i" class="px-4 py-3.5">
                    <div class="flex items-center gap-3">
                        <ClientAvatar :review="t" :size="40" />
                        <div class="min-w-0 flex-1 leading-tight">
                            <p class="flex items-center justify-between gap-2">
                                <component :is="t.url ? 'a' : 'span'" :href="t.url" target="_blank" rel="noopener noreferrer" class="truncate text-sm font-semibold hover:underline">{{ t.name }}</component>
                                <span class="flex shrink-0 text-warn" :aria-label="`Rated ${t.rating} out of 5`"><Icon v-for="n in 5" :key="n" name="star" :size="11" /></span>
                            </p>
                            <p class="mt-0.5 truncate text-xs text-muted">{{ t.anonymous ? `Name hidden by ${t.platform}` : `${t.platform} client` }}<template v-if="t.country"> · {{ t.country }}</template><template v-if="t.date"> · {{ t.date }}</template></p>
                        </div>
                    </div>
                    <p class="mt-2.5 line-clamp-5 text-[14px] leading-snug text-ink/90" :title="t.quote">“{{ t.quote }}”</p>
                    <p v-if="t.company || t.duration" class="mt-1.5 truncate font-mono text-[10.5px] text-faint" :title="t.company">{{ t.company || `Delivered in ${t.duration}` }}</p>
                </li>
            </ul>

            <button v-if="testimonials.length > SHOWN" type="button" class="flex w-full items-center justify-between border-t border-line px-4 py-2.5 text-sm text-muted transition hover:bg-raised/60 hover:text-ink" @click="emit('reviews')">
                Read all {{ testimonials.length }} reviews <Icon name="chevronRight" :size="15" />
            </button>
            <a v-else-if="marketplaces[0]" :href="marketplaces[0].href" target="_blank" rel="noopener noreferrer" class="flex w-full items-center justify-between border-t border-line px-4 py-2.5 text-sm text-muted transition hover:bg-raised/60 hover:text-ink">
                See all reviews on {{ marketplaces[0].label }} <Icon name="external" :size="14" />
            </a>
        </section>

        <section class="card p-4" aria-labelledby="now-title">
            <p class="eyebrow flex items-center gap-1.5"><span class="h-1.5 w-1.5 animate-blink rounded-full bg-human"></span> Now building</p>
            <h2 id="now-title" class="mt-1.5 font-semibold">{{ nowBuilding.name }}</h2>
            <p class="mt-1 text-sm leading-snug text-muted">{{ nowBuilding.text }}</p>
            <p class="mt-2 font-mono text-[11px] text-faint">{{ nowBuilding.status }}</p>
        </section>

        <footer class="space-y-3 px-1 pb-6">
            <nav class="flex flex-wrap gap-2" aria-label="Profiles">
                <a v-for="l in profile.links" :key="l.label" :href="l.href" target="_blank" rel="noopener noreferrer" class="btn-icon h-9 w-9" :aria-label="l.label" :title="l.label">
                    <TechLogo v-if="l.brand" :name="l.icon" :size="16" mono />
                    <Icon v-else :name="l.icon" :size="16" />
                </a>
                <a :href="`https://wa.me/${profile.whatsapp}`" target="_blank" rel="noopener noreferrer" class="btn-icon h-9 w-9" aria-label="WhatsApp" title="WhatsApp">
                    <Icon name="whatsapp" :size="16" />
                </a>
            </nav>
            <p class="text-xs leading-relaxed text-faint">© {{ year }} {{ profile.name }} · Built with Laravel, Vue and Tailwind. Most project clients are anonymised; reviews are quoted word for word from Upwork and Fiverr; illustrative examples are marked.</p>
        </footer>
    </div>
</template>

<script setup>
// /cv: a one-page-ish CV built from the portfolio data, so it never goes stale.
// "Download PDF" uses the browser's print-to-PDF; the print styles below lay it out on A4.
import { onMounted } from 'vue';
import Icon from './components/Icon.vue';
import { education, experience, marketplaces, overview, posts, profile, skillGroups } from './data.js';
import { caseStudies } from './cases/index.js';

const site = profile.site || location.origin;
const linkFor = (p) => p.url || (caseStudies[p.id] ? `${site}/case-studies/${p.id}` : `${site}/projects`);
const pretty = (u) => u.replace(/^https?:\/\//, '').replace(/\/$/, '');

const selected = [...overview.featured, 'vintbay', 'deal-finder', 'ai-support-agent', 'ebay-de', 'science-fest', 'sadb', 'ghurun']
    .filter((id, i, a) => a.indexOf(id) === i)
    .map((id) => posts.find((p) => p.id === id))
    .filter(Boolean)
    .slice(0, 6);

const skills = skillGroups.map((g) => ({ title: g.title, items: g.skills.map((s) => s.name) }));
const upwork = marketplaces.find((m) => m.id === 'upwork');
const fiverr = marketplaces.find((m) => m.id === 'fiverr');

const print = () => window.print();

onMounted(() => {
    document.title = `${profile.name} · CV`;
});
</script>

<template>
    <div class="cv-toolbar sticky top-0 z-10 border-b border-line bg-bg/90 backdrop-blur-xl print:hidden">
        <div class="mx-auto flex h-14 max-w-[820px] items-center gap-2 px-4">
            <a href="/" class="btn h-9 px-3 text-sm text-muted hover:bg-raised hover:text-ink"><Icon name="chevronLeft" :size="16" /> Portfolio</a>
            <span class="flex-1"></span>
            <button type="button" class="btn-primary h-9" @click="print"><Icon name="share" :size="15" /> Download PDF</button>
        </div>
    </div>

    <main class="cv mx-auto my-6 max-w-[820px] bg-white px-10 py-10 text-[#16181d] shadow-[0_10px_40px_-20px_rgb(0_0_0/.5)] print:m-0 print:max-w-none print:p-0 print:shadow-none sm:rounded-2xl">
        <!-- header -->
        <header class="flex items-start justify-between gap-6 border-b border-black/10 pb-5">
            <div class="min-w-0">
                <h1 class="text-[30px] font-bold leading-none tracking-[-0.02em]">{{ profile.name }}</h1>
                <p class="mt-2 text-[15px] font-semibold text-[#2b4fd8]">{{ profile.headline }}</p>
                <p class="mt-2 max-w-[60ch] text-[12.5px] leading-relaxed text-black/70">{{ overview.intro }}</p>
            </div>
            <img v-if="profile.avatar" :src="profile.avatar" alt="" class="h-20 w-20 shrink-0 rounded-full object-cover" />
        </header>
        <p class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-[11.5px] text-black/70">
            <span>{{ profile.location }} ({{ profile.timezone }})</span>
            <a :href="`mailto:${profile.email}`">{{ profile.email }}</a>
            <span>{{ profile.phone }}</span>
            <a :href="site">{{ pretty(site) }}</a>
            <a v-for="l in profile.links.filter((x) => ['GitHub', 'LinkedIn'].includes(x.label))" :key="l.label" :href="l.href">{{ pretty(l.href) }}</a>
        </p>
        <p class="mt-1.5 text-[11.5px] text-black/70">
            Upwork: {{ upwork.badges[0] }}, {{ upwork.badges[1] }}, {{ upwork.rating }} from {{ upwork.reviews }} reviews · Fiverr: {{ fiverr.rating }} ★
        </p>

        <!-- experience -->
        <section class="mt-6">
            <h2 class="cv-h2">Experience</h2>
            <article v-for="e in experience" :key="e.org + e.role" class="cv-block mt-3.5">
                <p class="flex items-baseline justify-between gap-3">
                    <span class="text-[14px] font-semibold">{{ e.role }} · {{ e.org }}</span>
                    <span class="shrink-0 text-[11.5px] text-black/55">{{ e.when }}</span>
                </p>
                <p class="text-[11.5px] text-black/55">{{ e.place }}</p>
                <ul class="mt-1.5 list-disc space-y-0.5 pl-4 text-[12px] leading-snug text-black/80">
                    <li v-for="pt in e.points" :key="pt">{{ pt }}</li>
                    <li v-if="e.achievements?.length">{{ e.achievements.map((a) => `${a.value} ${a.label}`).join(' · ') }}</li>
                </ul>
            </article>
        </section>

        <!-- selected projects -->
        <section class="mt-6">
            <h2 class="cv-h2">Selected projects</h2>
            <div class="mt-3 grid grid-cols-2 gap-x-6 gap-y-3">
                <article v-for="p in selected" :key="p.id" class="cv-block">
                    <p class="text-[13px] font-semibold">{{ p.name }} <span class="font-normal text-black/50">· {{ p.when }}</span></p>
                    <p class="text-[11.5px] leading-snug text-black/75">{{ p.title }}</p>
                    <p class="mt-0.5 text-[11px] text-black/50">{{ p.tags.slice(0, 5).join(', ') }}</p>
                    <a :href="linkFor(p)" class="text-[11px] text-[#2b4fd8]">{{ pretty(linkFor(p)) }}</a>
                </article>
            </div>
            <p class="mt-2 text-[11px] text-black/55">All {{ posts.length }} projects with case studies: {{ pretty(site) }}/projects</p>
        </section>

        <!-- skills -->
        <section class="mt-6">
            <h2 class="cv-h2">Skills</h2>
            <dl class="mt-2.5 space-y-1.5">
                <div v-for="g in skills" :key="g.title" class="cv-block grid grid-cols-[150px_1fr] gap-3 text-[11.5px] leading-snug">
                    <dt class="font-semibold">{{ g.title }}</dt>
                    <dd class="text-black/75">{{ g.items.join(' · ') }}</dd>
                </div>
            </dl>
        </section>

        <!-- education -->
        <section class="mt-6">
            <h2 class="cv-h2">Education</h2>
            <p class="mt-2.5 flex items-baseline justify-between gap-3 text-[13px]">
                <span><span class="font-semibold">{{ education.degree }}</span> · {{ education.school }}</span>
                <span class="shrink-0 text-[11.5px] text-black/55">{{ education.when }} · {{ education.detail }}</span>
            </p>
            <p class="mt-1 text-[11.5px] text-black/70">Languages: Bangla (native), English (professional)</p>
        </section>
    </main>
</template>

<style>
.cv-h2 {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: #2b4fd8;
    border-bottom: 1px solid rgb(0 0 0 / 0.1);
    padding-bottom: 4px;
}
.cv a { text-decoration: none; }
@media print {
    @page { size: A4; margin: 11mm 12mm; }
    .cv { font-size: 0.94em; }
    .cv section { margin-top: 14px !important; }
    .cv-block { margin-top: 8px !important; }
    html, body { background: #fff !important; }
    .cv-block { break-inside: avoid; }
}
</style>

<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import Icon from './components/Icon.vue';
import Avatar from './components/Avatar.vue';
import CoverCanvas from './components/CoverCanvas.vue';
import OverviewTab from './components/OverviewTab.vue';
import StoryViewer from './components/StoryViewer.vue';
import MessageDrawer from './components/MessageDrawer.vue';
import WorkflowsTab from './components/WorkflowsTab.vue';
import StackTab from './components/StackTab.vue';
import AboutTab from './components/AboutTab.vue';
import RightRail from './components/RightRail.vue';
import ProjectsTab from './components/ProjectsTab.vue';
import ProjectDialog from './components/ProjectDialog.vue';
import TechLogo from './components/TechLogo.vue';
import CaseStudyDialog from './components/CaseStudyDialog.vue';
import { caseStudies } from './cases/index.js';
import { highlights, marketplaces, posts, profile, skillBadges } from './data.js';

/* ---------- storage helpers (never throw) ---------- */
const store = {
    get(k) { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set(k, v) { try { localStorage.setItem(k, v); } catch (e) { /* private mode */ } },
};

/* ---------- theme ---------- */
const theme = ref(document.documentElement.getAttribute('data-theme') || 'dark');
function syncThemeColor() {
    document.getElementById('theme-color')?.setAttribute('content', theme.value === 'light' ? '#f4f3ee' : '#090b0e');
}
function toggleTheme() {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme.value);
    store.set('sa-theme', theme.value);
    syncThemeColor();
}

/* ---------- screen-reader status ---------- */
const status = ref('');
const announce = (msg) => { status.value = ''; nextTick(() => (status.value = msg)); };

/* ---------- tabs + URL routing ---------- */
const tabs = [
    { id: 'overview', label: 'Overview', icon: 'feed' },
    { id: 'projects', label: 'Projects', icon: 'folder' },
    { id: 'automations', label: 'Services', icon: 'flow' },
    { id: 'stack', label: 'Skills', icon: 'stack' },
    { id: 'about', label: 'About', icon: 'user' },
];
const tab = ref(location.pathname.replace(/\/$/, '') === '/projects' ? 'projects' : 'overview');
const tabsAnchor = ref(null);
const query = () => location.search;
const urlFor = (id) => (id === 'overview' ? `/${query()}` : id === 'projects' ? `/projects${query()}` : `/${query()}#${id}`);

function scrollToTabs({ smooth = true, force = false } = {}) {
    if (!tabsAnchor.value) return;
    const top = tabsAnchor.value.getBoundingClientRect().top + window.scrollY - (window.innerWidth < 768 ? 56 : 0);
    if (force || window.scrollY > top + 1) window.scrollTo({ top, behavior: smooth ? 'smooth' : 'instant' });
}

// Switching sections always brings the section's content to the top of the screen,
// whether the visitor was looking at the profile header or deep in another tab.
async function setTab(id, { scroll = true } = {}) {
    tab.value = id;
    history.replaceState(null, '', urlFor(id));
    if (scroll) {
        await nextTick();
        scrollToTabs({ force: true });
    }
}

// Arrow keys / Home / End move between tabs (roving tabindex).
function onTabKey(e, i) {
    const n = tabs.length;
    const next = { ArrowRight: (i + 1) % n, ArrowLeft: (i - 1 + n) % n, Home: 0, End: n - 1 }[e.key];
    if (next === undefined) return;
    e.preventDefault();
    setTab(tabs[next].id, { scroll: false });
    nextTick(() => document.getElementById(`tab-${tabs[next].id}`)?.focus());
}

// Old #post-<id> share links (from when projects were a feed) open the project's case study,
// or the Projects tab for projects that link to a live site.
function openPost(id) {
    id = { klr: 'ebay-shopify-seo' }[id] || id;
    story.value = null;
    if (caseStudies[id]) openCase(id);
    else setTab('projects');
}

function readHash({ initial = false } = {}) {
    const h = location.hash.replace('#', '');
    if (!h) return;
    if (h.startsWith('post-')) openPost(h.slice(5));
    else if (h.startsWith('case-')) openCase(h.slice(5));
    else if (h === 'contact') message();
    else if (tabs.some((t) => t.id === h)) {
        tab.value = h;
        nextTick(() => scrollToTabs({ smooth: false, force: true }));
    }
}

/* ---------- skill filter: badges and the Skills tab narrow the Projects tab ---------- */
const tag = ref(null); // { label, tags[] }
function pickTag(t) {
    tag.value = typeof t === 'string' ? { label: t, tags: [t] } : t;
    project.value = null;
    caseOpen.value = null;
    setTab('projects');
    const n = posts.filter((p) => p.tags.some((x) => tag.value.tags.includes(x))).length;
    nextTick(() => announce(`Showing ${n} projects for ${tag.value.label}`));
}

/* ---------- stories ---------- */
const story = ref(null);
const seen = ref(new Set((store.get('sa-seen') || '').split(',').filter(Boolean)));
function markSeen(id) {
    if (seen.value.has(id)) return;
    const n = new Set(seen.value);
    n.add(id);
    seen.value = n;
    store.set('sa-seen', [...n].join(','));
}

/* ---------- projects ---------- */
const project = ref(null);
function openProject(p) {
    project.value = p;
}

/* ---------- case studies: a modal, with #case-<id> in the URL so it can be shared ---------- */
const caseOpen = ref(null); // { post, study }
function openCase(idOrPost) {
    const post = typeof idOrPost === 'string' ? posts.find((p) => p.id === idOrPost) : idOrPost;
    const study = post && caseStudies[post.id];
    if (!study) return;
    project.value = null;
    caseOpen.value = { post, study };
    history.replaceState(null, '', `${urlFor(tab.value).split('#')[0]}#case-${post.id}`);
}
function closeCase() {
    caseOpen.value = null;
    if (location.hash.startsWith('#case-')) history.replaceState(null, '', urlFor(tab.value));
}

/* ---------- messages ---------- */
const dm = ref(null);
const lastDraft = ref({ message: '', name: '' });
function message(draft = '') {
    dm.value = { draft: draft || lastDraft.value.message, name: lastDraft.value.name };
}
function askAbout(item) {
    project.value = null;
    caseOpen.value = null;
    message(`Hi Shakil, I’m interested in something like “${item.name || item.title}”. Here’s what we do today:\n\n`);
}
function closeDm(d) {
    if (d) lastDraft.value = { message: d.message || '', name: d.name || '' };
    dm.value = null;
    if (location.hash === '#contact') history.replaceState(null, '', urlFor(tab.value));
}

/* ---------- reviews: open About and jump to them ---------- */
async function showReviews() {
    await setTab('about', { scroll: false });
    document.getElementById('refs-title')?.closest('section')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/* ---------- misc ---------- */
const shared = ref(false);
async function shareProfile() {
    const url = location.origin + '/';
    try {
        if (navigator.share && matchMedia('(pointer: coarse)').matches) await navigator.share({ title: `${profile.name} · ${profile.headline}`, url });
        else {
            await navigator.clipboard.writeText(url);
            shared.value = true;
            announce('Profile link copied');
            setTimeout(() => (shared.value = false), 1600);
        }
    } catch (e) { /* cancelled */ }
}

const scrolled = ref(false);
const onScroll = () => (scrolled.value = window.scrollY > 260);
const onHash = () => readHash();

onMounted(() => {
    syncThemeColor();
    readHash({ initial: true });
    if (tab.value === 'projects' && !location.hash) nextTick(() => scrollToTabs({ smooth: false, force: true }));
    window.addEventListener('hashchange', onHash);
    window.addEventListener('scroll', onScroll, { passive: true });
});
onBeforeUnmount(() => {
    window.removeEventListener('hashchange', onHash);
    window.removeEventListener('scroll', onScroll);
});
</script>

<template>
    <a href="#main" class="sr-only left-3 top-3 z-[70] rounded-full bg-accent px-4 py-2 text-sm font-semibold text-accent-ink focus:not-sr-only focus:fixed">Skip to content</a>
    <p class="sr-only" role="status" aria-live="polite">{{ status }}</p>

    <!-- Mobile top bar -->
    <header class="sticky top-0 z-40 flex h-14 items-center gap-3 border-b border-line bg-bg/85 px-4 backdrop-blur-xl md:hidden">
        <a href="/" class="flex min-w-0 flex-1 items-center gap-2.5" aria-label="Shakil Anwar, home">
            <Avatar :size="30" />
            <span class="min-w-0 leading-tight">
                <span class="block text-[15px] font-semibold">{{ profile.name }}</span>
                <span class="block overflow-hidden truncate text-[11px] text-faint transition-all duration-200" :class="scrolled ? 'max-h-4 opacity-100' : 'max-h-0 opacity-0'" :aria-hidden="!scrolled">{{ profile.headline }}</span>
            </span>
        </a>
        <button type="button" class="btn-icon h-9 w-9" :aria-label="theme === 'dark' ? 'Light mode' : 'Dark mode'" @click="toggleTheme">
            <Icon :name="theme === 'dark' ? 'sun' : 'moon'" :size="17" />
        </button>
    </header>

    <div class="mx-auto flex max-w-[1600px] justify-center gap-6 px-0 sm:px-4 lg:px-6 xl:gap-8">
        <!-- Left rail -->
        <aside class="sticky top-0 hidden h-dvh w-[76px] shrink-0 flex-col py-5 md:flex xl:w-[248px]">
            <a href="/" class="mb-6 flex items-center gap-2 px-3" aria-label="Shakil Anwar, home">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-accent text-sm font-bold tracking-tight text-accent-ink">SA</span>
                <span class="hidden text-[16px] font-semibold tracking-[-0.01em] xl:inline">shakilanwar<span class="text-faint">.com</span></span>
            </a>
            <nav class="space-y-1" aria-label="Sections">
                <button
                    v-for="t in tabs"
                    :key="t.id"
                    type="button"
                    class="flex w-full items-center justify-center gap-3.5 rounded-full px-3 py-2.5 text-[15px] transition hover:bg-raised xl:justify-start"
                    :class="tab === t.id ? 'font-semibold text-ink' : 'text-muted'"
                    :aria-current="tab === t.id ? 'page' : undefined"
                    :title="t.label"
                    @click="setTab(t.id)"
                >
                    <span class="relative"><Icon :name="t.icon" :size="22" /><span v-if="tab === t.id" class="absolute -right-1 -top-1 h-2 w-2 rounded-full bg-accent"></span></span>
                    <span class="hidden xl:inline">{{ t.label }}</span>
                </button>
            </nav>

            <div class="mt-auto space-y-2">
                <button type="button" class="flex w-full items-center justify-center gap-3 rounded-full px-3 py-2.5 text-sm text-muted transition hover:bg-raised xl:justify-start" :aria-label="theme === 'dark' ? 'Light mode' : 'Dark mode'" :title="theme === 'dark' ? 'Light mode' : 'Dark mode'" @click="toggleTheme">
                    <Icon :name="theme === 'dark' ? 'sun' : 'moon'" :size="20" />
                    <span class="hidden xl:inline" aria-hidden="true">{{ theme === 'dark' ? 'Light mode' : 'Dark mode' }}</span>
                </button>
                <div class="hidden items-center gap-2.5 rounded-2xl p-2 xl:flex">
                    <Avatar :size="36" />
                    <div class="min-w-0 leading-tight">
                        <p class="truncate text-sm font-semibold">{{ profile.name }}</p>
                        <p class="truncate text-xs text-faint">@{{ profile.handle }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main column -->
        <main id="main" class="w-full min-w-0 max-w-[860px] pb-24 md:border-x md:border-line md:pb-10">
            <!-- Profile header -->
            <section class="relative border-b border-line bg-surface" aria-labelledby="profile-name">
                <div class="relative h-36 border-b border-line bg-raised/40 sm:h-52">
                    <CoverCanvas />
                </div>

                <div class="px-4 pb-4 sm:px-6">
                    <div class="flex items-end justify-between gap-3">
                        <div class="-mt-12 rounded-full bg-surface p-1 sm:-mt-16">
                            <Avatar :size="120" ring />
                        </div>
                        <div class="flex items-center gap-2 pt-3">
                            <button type="button" class="btn-icon" :aria-label="shared ? 'Link copied' : 'Share profile'" :title="shared ? 'Link copied' : 'Share profile'" @click="shareProfile">
                                <Icon :name="shared ? 'check' : 'share'" :size="17" />
                            </button>
                            <a :href="profile.links[1].href" target="_blank" rel="noopener noreferrer" class="btn-icon max-[419px]:hidden" aria-label="LinkedIn" title="LinkedIn"><Icon name="linkedin" :size="17" /></a>
                            <a :href="profile.links[0].href" target="_blank" rel="noopener noreferrer" class="btn-icon max-[419px]:hidden" aria-label="GitHub" title="GitHub"><Icon name="github" :size="17" /></a>
                            <a
                                v-for="m in marketplaces"
                                :key="m.id"
                                :href="m.href"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-icon max-[519px]:hidden"
                                :aria-label="`${m.label} profile, rated ${m.rating}`"
                                :title="`${m.label} · ★ ${m.rating}${m.badges[0] ? ' · ' + m.badges[0] : ''}`"
                            ><TechLogo :name="m.label" :size="17" /></a>
                            <button type="button" class="btn-primary max-md:hidden md:border md:border-line md:bg-surface md:text-ink md:shadow-none md:hover:bg-raised" @click="message()"><Icon name="send" :size="16" /> Message</button>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                            <h1 id="profile-name" class="text-[26px] font-bold leading-tight tracking-[-0.02em] sm:text-[32px]">{{ profile.name }}</h1>
                            <p v-if="profile.available" class="inline-flex items-center gap-1.5 rounded-full border border-ok/30 bg-ok/10 px-2.5 py-0.5 text-[13px] font-medium text-ok-text" :title="profile.availability">
                                <span class="relative flex h-2 w-2"><span class="absolute h-full w-full animate-ping2 rounded-full bg-ok"></span><span class="relative h-2 w-2 rounded-full bg-ok"></span></span>
                                Available
                            </p>
                        </div>
                        <p class="mt-0.5 flex flex-wrap items-center gap-x-2 text-[15px] text-faint">
                            <span>@{{ profile.handle }}</span>
                            <span aria-hidden="true">·</span>
                            <span class="inline-flex items-center gap-1"><Icon name="location" :size="14" />{{ profile.location }}</span>
                            <span class="hidden sm:inline" aria-hidden="true">·</span>
                            <span class="hidden sm:inline">{{ profile.timezone }}</span>
                        </p>
                    </div>

                    <p class="mt-2 text-[17px] font-semibold tracking-tight sm:text-[18px]">{{ profile.headline }}</p>

                    <!-- skill badges: AI-era skills first, then the core stack. A click filters the feed. -->
                    <ul class="mt-3.5 flex flex-wrap gap-1.5" aria-label="Top skills">
                        <li v-for="b in skillBadges" :key="b.label">
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[13px] font-medium transition"
                                :class="b.future ? 'border-ai/30 bg-ai/10 text-ink hover:border-ai/60' : 'border-line bg-raised/40 text-ink/90 hover:border-ai/40'"
                                :title="`Show projects with ${b.label}`"
                                @click="pickTag({ label: b.label, tags: b.tags })"
                            >
                                <TechLogo :name="b.logo" :size="13" />{{ b.label }}
                            </button>
                        </li>
                    </ul>

                </div>

                <!-- story highlights -->
                <div class="no-scrollbar flex gap-4 overflow-x-auto px-4 pb-4 pt-1 sm:px-6" role="list" aria-label="Case study reels">
                    <div v-for="(hl, i) in highlights" :key="hl.id" role="listitem" class="shrink-0">
                        <button type="button" class="group flex w-[70px] flex-col items-center gap-1.5" :aria-label="`Open ${hl.label} highlights`" @click="story = i">
                            <span
                                class="grid h-[68px] w-[68px] place-items-center rounded-full p-[2.5px] transition group-hover:scale-[1.04] group-active:scale-95"
                                :class="seen.has(hl.id) ? 'bg-line' : 'bg-[conic-gradient(from_200deg,rgb(var(--accent)),rgb(var(--ai)),rgb(var(--human)),rgb(var(--accent)))]'"
                            >
                                <span class="grid h-full w-full place-items-center overflow-hidden rounded-full border-[3px] border-surface bg-raised">
                                    <img v-if="hl.image" :src="hl.image" alt="" class="h-full w-full object-cover" />
                                    <TechLogo v-else-if="hl.logo" :name="hl.logo" :size="26" />
                                    <span v-else class="font-mono text-[15px] font-semibold">{{ hl.glyph }}</span>
                                </span>
                            </span>
                            <span class="max-w-full truncate text-xs text-muted group-hover:text-ink">{{ hl.label }}</span>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Tabs -->
            <div ref="tabsAnchor" aria-hidden="true"></div>
            <div class="sticky top-14 z-30 border-b border-line bg-bg/85 backdrop-blur-xl md:top-0">
                <div class="no-scrollbar flex overflow-x-auto" role="tablist" aria-label="Profile sections">
                    <button
                        v-for="(t, i) in tabs"
                        :id="`tab-${t.id}`"
                        :key="t.id"
                        type="button"
                        role="tab"
                        :aria-selected="tab === t.id"
                        :aria-controls="`panel-${t.id}`"
                        :tabindex="tab === t.id ? 0 : -1"
                        class="relative shrink-0 whitespace-nowrap px-4 py-3.5 sm:min-w-[76px] sm:flex-1 sm:px-3 text-[14.5px] font-medium transition hover:bg-raised/50 focus-visible:outline-offset-[-2px]"
                        :class="tab === t.id ? 'text-ink' : 'text-muted'"
                        @click="setTab(t.id)"
                        @keydown="onTabKey($event, i)"
                    >
                        {{ t.label }}
                        <span v-if="tab === t.id" class="absolute inset-x-0 bottom-0 mx-auto h-[3px] w-12 rounded-full bg-accent"></span>
                    </button>
                </div>
            </div>

            <!-- Panels (all rendered, so search engines see every section) -->
            <div class="px-3 pt-3 sm:px-5 sm:pt-5">
                <div v-show="tab === 'overview'" id="panel-overview" role="tabpanel" tabindex="0" aria-labelledby="tab-overview" class="pb-6 outline-none">
                    <OverviewTab @tab="(t) => setTab(t)" @case="openCase" @message="message()" />
                </div>

                <div v-show="tab === 'projects'" id="panel-projects" role="tabpanel" tabindex="0" aria-labelledby="tab-projects" class="pb-6 outline-none">
                    <ProjectsTab :tag="tag" @open="openProject" @case="openCase" @clear-tag="tag = null" />
                </div>
                <div v-show="tab === 'automations'" id="panel-automations" role="tabpanel" tabindex="0" aria-labelledby="tab-automations" class="pb-6 outline-none">
                    <WorkflowsTab @open-post="openPost" @ask="askAbout" />
                </div>
                <div v-show="tab === 'stack'" id="panel-stack" role="tabpanel" tabindex="0" aria-labelledby="tab-stack" class="pb-6 outline-none">
                    <StackTab @tag="pickTag" />
                </div>
                <div v-show="tab === 'about'" id="panel-about" role="tabpanel" tabindex="0" aria-labelledby="tab-about" class="pb-6 outline-none">
                    <AboutTab @case="openCase" />
                    <RightRail class="mt-4 xl:hidden" @message="message()" @projects="setTab('projects')" @reviews="showReviews" />
                </div>
            </div>
        </main>

        <!-- Right rail -->
        <aside class="no-scrollbar sticky top-0 hidden h-dvh w-[340px] shrink-0 overflow-y-auto py-5 xl:block" aria-label="Sidebar">
            <RightRail @message="message()" @projects="setTab('projects')" @reviews="showReviews" />
        </aside>
    </div>

    <!-- Mobile: one floating Message button (the tab bar above is the only tab switcher) -->
    <button
        type="button"
        class="fixed bottom-[calc(1rem+env(safe-area-inset-bottom))] right-4 z-40 grid h-14 w-14 place-items-center rounded-full bg-accent text-accent-ink shadow-[0_8px_24px_-6px_rgb(var(--accent)/.8)] ring-4 ring-bg active:scale-95 md:hidden"
        aria-label="Message Shakil"
        @click="message()"
    >
        <Icon name="send" :size="22" />
    </button>

    <StoryViewer v-if="story !== null" :highlights="highlights" :start="story" @close="story = null" @seen="markSeen" @open-post="openPost" />
    <ProjectDialog v-if="project" :post="project" @close="project = null" @ask="askAbout" @tag="pickTag" />
    <CaseStudyDialog v-if="caseOpen" :post="caseOpen.post" :study="caseOpen.study" @close="closeCase" @ask="askAbout" />
    <MessageDrawer v-if="dm" :draft="dm.draft" :name="dm.name" @close="(d) => closeDm(d)" />
</template>

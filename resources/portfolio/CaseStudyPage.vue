<script setup>
// Standalone case study page at /case-studies/<id>, in the portfolio's theme.
import { onMounted, ref } from 'vue';
import Icon from './components/Icon.vue';
import Avatar from './components/Avatar.vue';
import CaseStudy from './components/CaseStudy.vue';
import MessageDrawer from './components/MessageDrawer.vue';
import { posts, profile } from './data.js';
import { caseStudies } from './cases/index.js';

const props = defineProps({ id: { type: String, required: true } });
const post = posts.find((p) => p.id === props.id);
const study = caseStudies[props.id];

const theme = ref(document.documentElement.getAttribute('data-theme') || 'dark');
function toggleTheme() {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', theme.value);
    try { localStorage.setItem('sa-theme', theme.value); } catch (e) { /* private mode */ }
    document.getElementById('theme-color')?.setAttribute('content', theme.value === 'light' ? '#f4f3ee' : '#090b0e');
}

const dm = ref(null);
const ask = (p) => (dm.value = { draft: `Hi Shakil, I read the “${p.name}” case study. Here’s what we do today:\n\n`, name: '' });

onMounted(() => {
    document.title = `${post.name} · Case study · ${profile.name}`;
    document.querySelector('meta[name="description"]')?.setAttribute('content', study.summary || post.title);
});
</script>

<template>
    <header class="sticky top-0 z-40 border-b border-line bg-bg/85 backdrop-blur-xl">
        <div class="mx-auto flex h-14 max-w-[860px] items-center gap-3 px-4 sm:px-6">
            <a href="/" class="flex min-w-0 flex-1 items-center gap-2.5" aria-label="Shakil Anwar, home">
                <Avatar :size="30" />
                <span class="min-w-0 leading-tight">
                    <span class="block truncate text-[15px] font-semibold">{{ profile.name }}</span>
                    <span class="block truncate text-[11px] text-faint">{{ profile.headline }}</span>
                </span>
            </a>
            <a href="/projects" class="btn hidden h-9 px-3 text-sm text-muted hover:bg-raised hover:text-ink sm:inline-flex"><Icon name="chevronLeft" :size="16" /> All projects</a>
            <button type="button" class="btn-icon h-9 w-9" :aria-label="theme === 'dark' ? 'Light mode' : 'Dark mode'" @click="toggleTheme">
                <Icon :name="theme === 'dark' ? 'sun' : 'moon'" :size="17" />
            </button>
        </div>
    </header>

    <main id="main" class="mx-auto max-w-[860px] px-4 pb-16 pt-6 sm:px-6 sm:pt-10">
        <CaseStudy :post="post" :study="study" @ask="ask" />
        <p class="mt-10 text-center text-xs text-faint">© {{ new Date().getFullYear() }} {{ profile.name }} · Most client names are withheld; illustrative examples are marked.</p>
    </main>

    <MessageDrawer v-if="dm" :draft="dm.draft" :name="dm.name" @close="dm = null" />
</template>

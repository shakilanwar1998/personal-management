import { createApp } from 'vue';
import App from './App.vue';
import CaseStudyPage from './CaseStudyPage.vue';
import CvPage from './CvPage.vue';
import { posts } from './data.js';
import { caseStudies } from './cases/index.js';
import './portfolio.css';

// /case-studies/<id> is its own page; everything else is the portfolio.
const path = location.pathname.replace(/\/$/, '');
const m = path.match(/^\/case-studies\/([a-z0-9-]+)$/);
if (path === '/cv') {
    createApp(CvPage).mount('#portfolio');
} else if (m) {
    const id = m[1];
    if (caseStudies[id] && posts.some((p) => p.id === id)) createApp(CaseStudyPage, { id }).mount('#portfolio');
    else location.replace('/projects');
} else {
    createApp(App).mount('#portfolio');
}

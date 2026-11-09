<script setup>
// SaDB: the desktop client. Schema tree on the left, a plain-English prompt, the SQL the AI wrote
// (from the live schema) sitting in the editor, and a results grid. You review, then run.
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const ENGINES = ['postgres', 'mysql', 'mongodb'].map((k) => logo(k));
const MODELS = [
    { l: logo('claude'), name: 'Claude', on: true },
    { l: logo('openai'), name: 'GPT' },
    { l: logo('gemini'), name: 'Gemini' },
];

const TABLES = [
    { name: 'customers', open: true, cols: ['id', 'name', 'email'] },
    { name: 'orders', open: true, cols: ['id', 'customer_id', 'created_at'] },
    { name: 'order_items' },
    { name: 'products' },
    { name: 'payments' },
];

// token colours: k keyword, f function, s string, n number, p plain, m muted
const SQL = [
    [['k', 'SELECT '], ['p', 'c.id, c.name, '], ['f', 'COUNT'], ['p', '(o.id) '], ['k', 'AS '], ['p', 'orders']],
    [['k', 'FROM '], ['p', 'customers c']],
    [['k', 'JOIN '], ['p', 'orders o '], ['k', 'ON '], ['p', 'o.customer_id = c.id']],
    [['k', 'WHERE '], ['p', 'o.created_at >= '], ['f', 'date_trunc'], ['p', '('], ['s', "'month'"], ['p', ', '], ['f', 'now'], ['p', '())']],
    [['k', 'GROUP BY '], ['p', 'c.id, c.name']],
    [['k', 'HAVING '], ['f', 'COUNT'], ['p', '(o.id) = '], ['n', '2'], ['p', ';']],
];
const TONE = { k: 'text-[#c4a7ff]', f: 'text-[#7cc4ff]', s: 'text-[#a5e887]', n: 'text-[#ffab70]', p: 'text-[#e6e8ec]', m: 'text-white/40' };

const ROWS = [
    ['1042', 'Nadia K.', '2'],
    ['1187', 'Tom B.', '2'],
    ['1263', 'Lena S.', '2'],
];
</script>

<template>
    <div class="absolute right-[-1cqw] top-[5.5cqw] w-[53cqw] rotate-[1deg]">
        <Frame kind="window" url="SaDB — shop · PostgreSQL" dark>
            <div class="flex">
                <!-- schema tree -->
                <aside class="w-[12.5cqw] shrink-0 border-r border-white/10 bg-[#111419] px-[1.1cqw] py-[1.2cqw] text-[1.1cqw]">
                    <div class="flex gap-[0.5cqw]">
                        <span v-for="(e, i) in ENGINES" :key="e.key" class="grid h-[2.2cqw] w-[2.2cqw] place-items-center rounded-[0.5cqw]" :class="i === 0 ? 'bg-white/15 ring-1 ring-white/20' : 'bg-white/[.05]'">
                            <svg viewBox="0 0 24 24" class="h-[62%] w-[62%]" :fill="i === 0 ? '#8fb8ff' : 'rgb(255 255 255 / .45)'"><path :d="e.path" /></svg>
                        </span>
                    </div>
                    <p class="mt-[1.2cqw] font-mono text-[0.9cqw] font-semibold uppercase tracking-[0.14em] text-white/35">Schema · public</p>
                    <ul class="mt-[0.6cqw] space-y-[0.45cqw] font-mono">
                        <li v-for="t in TABLES" :key="t.name">
                            <span class="flex items-center gap-[0.5cqw]" :class="t.open ? 'text-white' : 'text-white/60'">
                                <span class="w-[0.8cqw] text-[0.8cqw] text-white/35">{{ t.cols ? '▾' : '▸' }}</span>
                                <span class="grid h-[1.1cqw] w-[1.1cqw] grid-rows-2 overflow-hidden rounded-[0.2cqw] border border-current opacity-70"><span class="border-b border-current"></span></span>
                                {{ t.name }}
                            </span>
                            <ul v-if="t.cols" class="ml-[2.4cqw] mt-[0.3cqw] space-y-[0.25cqw] text-[1cqw] text-white/45">
                                <li v-for="c in t.cols" :key="c">{{ c }}</li>
                            </ul>
                        </li>
                    </ul>
                </aside>

                <!-- main -->
                <div class="min-w-0 flex-1 p-[1.3cqw]">
                    <!-- AI prompt -->
                    <div class="flex items-center gap-[0.8cqw] rounded-[0.9cqw] bg-[#1f2330] px-[1.1cqw] py-[0.9cqw] ring-1 ring-[#8b7cff]/60">
                        <svg viewBox="0 0 24 24" class="h-[1.7cqw] w-[1.7cqw] shrink-0" fill="#b3a8ff"><path d="M12 2l2.2 6.3L20.5 10l-6.3 2.2L12 18.5l-2.2-6.3L3.5 10l6.3-1.7zM19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9z" /></svg>
                        <span class="truncate text-[1.35cqw] text-white">customers who ordered twice this month</span>
                    </div>
                    <p class="mt-[0.7cqw] flex items-center gap-[0.6cqw] font-mono text-[0.95cqw] text-white/40">
                        <span class="text-[#4ade80]">✓</span> read schema: customers, orders · 0 guessed columns
                    </p>

                    <!-- editor -->
                    <div class="mt-[0.9cqw] rounded-[0.8cqw] bg-[#0f1216] py-[0.9cqw] font-mono text-[1.12cqw] leading-[1.75] ring-1 ring-white/[.06]">
                        <div v-for="(line, i) in SQL" :key="i" class="flex whitespace-pre">
                            <span class="w-[2.6cqw] shrink-0 pr-[0.8cqw] text-right text-white/20">{{ i + 1 }}</span>
                            <span><span v-for="(t, j) in line" :key="j" :class="TONE[t[0]]">{{ t[1] }}</span></span>
                        </div>
                    </div>

                    <!-- action -->
                    <div class="mt-[1cqw] flex items-center gap-[1cqw]">
                        <span class="font-mono text-[0.95cqw] text-white/40">↳ inserted into editor</span>
                        <span class="ml-auto flex items-center gap-[0.6cqw] rounded-[0.7cqw] bg-[#8b7cff] px-[1.2cqw] py-[0.6cqw] text-[1.15cqw] font-semibold text-white shadow-[0_0.6cqw_1.6cqw_rgb(139_124_255/.35)]">
                            Review, then run
                            <svg viewBox="0 0 24 24" class="h-[1.1cqw] w-[1.1cqw]" fill="currentColor"><path d="M8 5v14l11-7z" /></svg>
                        </span>
                    </div>

                    <!-- results -->
                    <div class="mt-[1cqw] overflow-hidden rounded-[0.8cqw] font-mono text-[1.05cqw] ring-1 ring-white/10">
                        <div class="grid grid-cols-[6cqw_1fr_6cqw] bg-white/[.06] px-[1cqw] py-[0.45cqw] text-white/45">
                            <span>id</span><span>name</span><span>orders</span>
                        </div>
                        <div v-for="r in ROWS" :key="r[0]" class="grid grid-cols-[6cqw_1fr_6cqw] border-t border-white/[.06] px-[1cqw] py-[0.45cqw] text-white/80">
                            <span class="text-[#ffab70]">{{ r[0] }}</span><span>{{ r[1] }}</span><span class="text-[#ffab70]">{{ r[2] }}</span>
                        </div>
                    </div>
                    <div class="h-[2cqw]"></div>
                </div>
            </div>
        </Frame>
    </div>
    <!-- bring your own model -->
    <div class="absolute bottom-[9cqw] left-[27cqw] -rotate-[2deg] rounded-[1.4cqw] bg-white px-[1.4cqw] py-[1.1cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.5)]">
        <p class="text-[1.05cqw] font-semibold uppercase tracking-[0.12em] text-black/40">Your model, your key</p>
        <div class="mt-[0.7cqw] flex gap-[0.6cqw]">
            <span v-for="m in MODELS" :key="m.name" class="flex items-center gap-[0.5cqw] rounded-full px-[0.9cqw] py-[0.4cqw] text-[1.2cqw] font-semibold" :class="m.on ? 'bg-[#8b7cff]/12 text-[#4c3fd1] ring-1 ring-[#8b7cff]/50' : 'bg-black/[.04] text-black/55'">
                <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw]" :fill="m.on ? m.l.hex : 'currentColor'"><path :d="m.l.path" /></svg>
                {{ m.name }}
            </span>
        </div>
        <p class="mt-[0.7cqw] flex items-center gap-[0.5cqw] text-[1.05cqw] text-black/50">
            <svg viewBox="0 0 24 24" class="h-[1.2cqw] w-[1.2cqw]" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><rect x="5" y="11" width="14" height="10" rx="2" /><path d="M8 11V8a4 4 0 0 1 8 0v3" /></svg>
            API key encrypted on this machine
        </p>
    </div>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { logo, useInk } from '../logos.js';

// Decorative cover: the kind of work I do, not one project.
// Messy inputs on the left → an AI agent drafts → a person approves → finished work on the right.
// The viewBox follows the container's aspect ratio so nodes are never cropped.
const INPUTS = [
    { label: 'New order', sub: 'shopify', logo: 'shopify' },
    { label: 'Customer email', sub: 'gmail', logo: 'gmail' },
    { label: 'Supplier sheet', sub: 'csv · xlsx', logo: 'sheets', wide: true },
];
const OUTPUTS = [
    { label: 'Listing live', sub: 'ebay', logo: 'ebay' },
    { label: 'Stock synced', sub: 'airtable', logo: 'airtable' },
    { label: 'Team alerted', sub: 'slack', logo: 'slack', wide: true },
    { label: 'Invoice paid', sub: 'stripe', logo: 'stripe', wide: true },
];
const HUB_TOOLS = ['n8n', 'openai', 'claude'];

const H_VB = 220;
const NH = 36; // node height
const HUB_W = 164;
const HUB_H = 70;
const GATE_H = 32;

const root = ref(null);
const vbW = ref(800);
let ro;

const wide = computed(() => vbW.value >= 700);
const NW = computed(() => (wide.value ? 142 : 124));
// Wide: a pill that says "You approve". Narrow: just the green check.
const GATE_W = computed(() => (wide.value ? 116 : 30));

// Spread a column of nodes over a vertical band. The left column stays high because
// the avatar covers the bottom-left corner of the cover.
function column(list, x, top, bottom) {
    const step = list.length > 1 ? (bottom - top) / (list.length - 1) : 0;
    return list.map((n, i) => ({ ...n, x, y: Math.round(top + i * step) }));
}

const layout = computed(() => {
    const w = vbW.value;
    const nw = NW.value;
    const ins = INPUTS.filter((n) => wide.value || !n.wide);
    const outs = OUTPUTS.filter((n) => wide.value || !n.wide);
    const inputs = column(ins, 14 + nw / 2, 34, ins.length > 2 ? 128 : 100);
    const outputs = column(outs, w - 14 - nw / 2, 30, outs.length > 2 ? 168 : 116);
    const hub = { x: Math.round(w * (wide.value ? 0.42 : 0.44)), y: 88 };
    const hubR = hub.x + HUB_W / 2;
    const outL = w - 14 - nw;
    // The gate sits between the hub and the outputs, a little closer to the hub.
    const gate = { x: Math.round(hubR + (outL - hubR) * (wide.value ? 0.42 : 0.5)), y: 88 };
    return { inputs, outputs, hub, gate };
});

const curve = (x1, y1, x2, y2) => {
    const mx = (x1 + x2) / 2;
    return `C${mx},${y1} ${mx},${y2} ${x2},${y2}`;
};

const edges = computed(() => {
    const { inputs, outputs, hub, gate } = layout.value;
    const nw = NW.value;
    const hubL = hub.x - HUB_W / 2, hubR = hub.x + HUB_W / 2;
    const gateL = gate.x - GATE_W.value / 2, gateR = gate.x + GATE_W.value / 2;
    const ins = inputs.map((n) => ({ x: n.x + nw / 2, y: n.y, d: `M${n.x + nw / 2},${n.y} ${curve(n.x + nw / 2, n.y, hubL, hub.y)}` }));
    const mid = `M${hubR},${hub.y} ${curve(hubR, hub.y, gateL, gate.y)}`;
    const outs = outputs.map((n) => ({ d: `M${gateR},${gate.y} ${curve(gateR, gate.y, n.x - nw / 2, n.y)}` }));
    return { ins, mid, outs, hubL, hubR, gateL, gateR };
});

// Packet routes: input i → hub → gate → output j, joined by straight runs through each box.
const routes = computed(() => {
    const { inputs, outputs, hub, gate } = layout.value;
    const e = edges.value;
    const nw = NW.value;
    const pairs = inputs.map((_, i) => [i, (i * 2 + 1) % outputs.length]);
    if (outputs.length > inputs.length) pairs.push([0, outputs.length - 1]);
    return pairs.map(([i, j]) => {
        const a = inputs[i], b = outputs[j];
        return [
            `M${a.x + nw / 2},${a.y}`,
            curve(a.x + nw / 2, a.y, e.hubL, hub.y),
            `L${e.hubR},${hub.y}`,
            curve(e.hubR, hub.y, e.gateL, gate.y),
            `L${e.gateR},${gate.y}`,
            curve(e.gateR, gate.y, b.x - nw / 2, b.y),
        ].join(' ');
    });
});

// Brand logo as an SVG path on the 24px grid; very dark brands follow the text colour.
function mark(key) {
    const l = logo(key);
    if (l?.kind !== 'brand') return null;
    return { path: l.path, fill: useInk(l.hex) ? 'rgb(var(--ink))' : l.hex };
}

// Motion runs for at most 5 seconds (WCAG 2.2.2), and only while the cover is
// on screen and the tab is visible.
const MOTION_MS = 5000;
const motion = ref(false); // allowed by the user's motion preference
const expired = ref(false);
const inView = ref(true);
const pageVisible = ref(true);
const playing = computed(() => motion.value && !expired.value && inView.value && pageVisible.value);

let stopTimer;
let io;
const onVisibility = () => (pageVisible.value = document.visibilityState === 'visible');

onMounted(() => {
    ro = new ResizeObserver(([e]) => {
        const { width, height } = e.contentRect;
        if (width && height) vbW.value = Math.max(480, Math.round((H_VB * width) / height));
    });
    ro.observe(root.value);
    io = new IntersectionObserver(([e]) => (inView.value = e.isIntersecting));
    io.observe(root.value);
    document.addEventListener('visibilitychange', onVisibility);
    onVisibility();
    motion.value = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    stopTimer = setTimeout(() => (expired.value = true), MOTION_MS);
});
onBeforeUnmount(() => {
    clearTimeout(stopTimer);
    ro?.disconnect();
    io?.disconnect();
    document.removeEventListener('visibilitychange', onVisibility);
});
</script>

<template>
    <div ref="root" class="relative h-full w-full overflow-hidden" aria-hidden="true">
        <div class="dot-grid absolute inset-0 opacity-70"></div>
        <div class="absolute -left-20 -top-24 h-64 w-64 rounded-full bg-ai/25 blur-3xl"></div>
        <div class="absolute -bottom-28 right-0 h-64 w-72 rounded-full bg-accent/20 blur-3xl"></div>

        <svg class="absolute inset-0 h-full w-full" :viewBox="`0 0 ${vbW} ${H_VB}`" preserveAspectRatio="xMidYMid meet" font-family="Geist, sans-serif">
            <defs>
                <radialGradient id="cover-hub-glow">
                    <stop offset="0%" stop-color="rgb(var(--ai))" stop-opacity=".35" />
                    <stop offset="100%" stop-color="rgb(var(--ai))" stop-opacity="0" />
                </radialGradient>
            </defs>

            <!-- wires -->
            <g fill="none" stroke-width="1.5">
                <path v-for="(e, i) in edges.ins" :key="'i' + i" :d="e.d" stroke="rgb(var(--line))" />
                <path :d="edges.mid" stroke="rgb(var(--line))" />
                <path v-for="(e, i) in edges.outs" :key="'o' + i" :d="e.d" stroke="rgb(var(--line))" />
                <path v-for="(e, i) in edges.ins" :key="'id' + i" :d="e.d" stroke="rgb(var(--ink) / .35)" stroke-dasharray="3 6" :class="playing && 'animate-flow'" />
                <path :d="edges.mid" stroke="rgb(var(--ai) / .8)" stroke-dasharray="3 6" :class="playing && 'animate-flow'" />
                <path v-for="(e, i) in edges.outs" :key="'od' + i" :d="e.d" stroke="rgb(var(--accent) / .8)" stroke-dasharray="3 6" :class="playing && 'animate-flow'" />
            </g>

            <!-- packets: drawn under the boxes, so they pass "through" each step -->
            <template v-if="playing">
                <g v-for="(r, i) in routes" :key="'p' + i + r.length">
                    <circle r="9" fill="rgb(var(--accent) / .22)">
                        <animateMotion :path="r" dur="4.2s" :begin="`-${i * 1.05}s`" repeatCount="indefinite" />
                    </circle>
                    <circle r="4" fill="rgb(var(--accent))">
                        <animateMotion :path="r" dur="4.2s" :begin="`-${i * 1.05}s`" repeatCount="indefinite" />
                    </circle>
                </g>
            </template>

            <!-- inputs (left) and outputs (right) -->
            <g v-for="n in [...layout.inputs, ...layout.outputs]" :key="n.label" :transform="`translate(${n.x - NW / 2}, ${n.y - NH / 2})`">
                <rect :width="NW" :height="NH" rx="10" fill="rgb(var(--surface) / .94)" stroke="rgb(var(--line))" stroke-width="1.2" />
                <g v-if="mark(n.logo)" :transform="`translate(10, ${(NH - 16) / 2}) scale(${16 / 24})`">
                    <path :d="mark(n.logo).path" :fill="mark(n.logo).fill" />
                </g>
                <text x="34" y="16" font-size="11.5" font-weight="600" fill="rgb(var(--ink))">{{ n.label }}</text>
                <text x="34" y="28" font-size="9" fill="rgb(var(--faint))" font-family="'Geist Mono', monospace">{{ n.sub }}</text>
            </g>

            <!-- AI agent hub -->
            <g :transform="`translate(${layout.hub.x}, ${layout.hub.y})`">
                <circle r="78" fill="url(#cover-hub-glow)" :class="playing && 'animate-pulse'" />
                <rect :x="-HUB_W / 2" :y="-HUB_H / 2" :width="HUB_W" :height="HUB_H" rx="16" fill="rgb(var(--surface))" stroke="rgb(var(--ai) / .7)" stroke-width="1.6" />
                <path :transform="`translate(${-HUB_W / 2 + 12}, -24) scale(.62)`" d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3Z" fill="rgb(var(--ai))" />
                <text :x="-HUB_W / 2 + 30" y="-11" font-size="13" font-weight="700" fill="rgb(var(--ink))">AI agent</text>
                <text :x="-HUB_W / 2 + 13" y="5" font-size="9" fill="rgb(var(--faint))" font-family="'Geist Mono', monospace">reads · decides · drafts</text>
                <g v-for="(t, i) in HUB_TOOLS" :key="t" :transform="`translate(${-HUB_W / 2 + 13 + i * 22}, 13)`">
                    <rect width="18" height="18" rx="5" fill="rgb(var(--raised))" stroke="rgb(var(--line))" stroke-width=".8" />
                    <g v-if="mark(t)" transform="translate(3, 3) scale(.5)"><path :d="mark(t).path" :fill="mark(t).fill" /></g>
                </g>
            </g>

            <!-- the human gate -->
            <g :transform="`translate(${layout.gate.x - GATE_W / 2}, ${layout.gate.y - GATE_H / 2})`">
                <rect :width="GATE_W" :height="GATE_H" rx="16" fill="rgb(var(--surface))" stroke="rgb(var(--human) / .7)" stroke-width="1.5" />
                <circle :cx="wide ? 16 : GATE_W / 2" :cy="GATE_H / 2" r="8" fill="rgb(var(--ok))" />
                <path :d="`M${(wide ? 16 : GATE_W / 2) - 4} ${GATE_H / 2}l2.6 2.5 4.6-5`" stroke="rgb(var(--surface))" stroke-width="1.8" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                <text v-if="wide" x="30" :y="GATE_H / 2 + 4" font-size="11.5" font-weight="600" fill="rgb(var(--ink))">You approve</text>
                <text v-else :x="GATE_W / 2" :y="GATE_H + 12" text-anchor="middle" font-size="9" fill="rgb(var(--faint))" font-family="'Geist Mono', monospace">you approve</text>
            </g>

        </svg>
    </div>
</template>

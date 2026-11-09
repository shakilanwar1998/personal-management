<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import {
    BarController, BarElement, CategoryScale, Chart, Filler, Legend, LinearScale, LineController, LineElement,
    PointElement, Tooltip,
} from 'chart.js';
import { cssVar } from '../lib/colors';
import { money, moneyCompact } from '../lib/format';
import { onThemeChange } from '../composables/useTheme';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, LineController, LineElement, PointElement, Tooltip, Legend, Filler);

/**
 * series: [{ label, data: number[], kind: 'bar' | 'line', color: '#hex' | 'var(--name)', stack?: string }]
 * One y-axis only (all series are money).
 */
const props = defineProps({
    labels: { type: Array, required: true },
    series: { type: Array, required: true },
    height: { type: Number, default: 280 },
    horizontal: { type: Boolean, default: false },
    stacked: { type: Boolean, default: false },
    legend: { type: Boolean, default: null }, // null = auto (shown for 2+ series)
});

const canvas = ref(null);
let chart = null;

const resolve = (c) => (c?.startsWith('var(') ? cssVar(c.slice(4, -1)) : c);

function build() {
    chart?.destroy();
    if (!canvas.value) return;
    const grid = cssVar('--chart-grid');
    const text = cssVar('--chart-text');
    const surface = document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff';
    const showLegend = props.legend ?? props.series.length > 1;

    const datasets = props.series.map((s, i) => {
        const color = resolve(s.color);
        const base = { label: s.label, data: s.data, order: s.kind === 'line' ? 0 : 1 };
        if (s.kind === 'line') {
            return {
                ...base,
                type: 'line',
                borderColor: color,
                backgroundColor: color,
                borderWidth: 2,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: color,
                pointBorderColor: surface,
                pointBorderWidth: 2,
                tension: 0.3,
            };
        }
        return {
            ...base,
            type: 'bar',
            backgroundColor: color,
            hoverBackgroundColor: color,
            borderRadius: 4,
            borderSkipped: props.stacked ? false : 'start',
            borderColor: surface,
            borderWidth: props.stacked ? { top: 2 } : 0,
            maxBarThickness: props.horizontal ? 18 : 22,
            categoryPercentage: 0.7,
            barPercentage: 0.85,
            stack: s.stack ?? (props.stacked ? 'all' : `s${i}`),
        };
    });

    const valueAxis = {
        stacked: props.stacked,
        beginAtZero: true,
        border: { display: false },
        grid: { color: grid, drawTicks: false },
        ticks: { color: text, padding: 8, font: { size: 11 }, maxTicksLimit: 6, callback: (v) => moneyCompact(v) },
    };
    const catAxis = {
        stacked: props.stacked,
        border: { display: false },
        grid: { display: false },
        ticks: { color: text, font: { size: 11 }, autoSkip: true, maxRotation: 0 },
    };

    chart = new Chart(canvas.value, {
        type: 'bar',
        data: { labels: props.labels, datasets },
        options: {
            indexAxis: props.horizontal ? 'y' : 'x',
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 350 },
            interaction: { mode: 'index', intersect: false, axis: props.horizontal ? 'y' : 'x' },
            layout: { padding: { top: 4 } },
            scales: props.horizontal ? { x: valueAxis, y: catAxis } : { x: catAxis, y: valueAxis },
            plugins: {
                legend: {
                    display: showLegend,
                    position: 'top',
                    align: 'end',
                    labels: { sort: (a, b) => a.datasetIndex - b.datasetIndex, color: text, usePointStyle: true, pointStyle: 'rectRounded', boxWidth: 8, boxHeight: 8, padding: 14, font: { size: 12 } },
                },
                tooltip: {
                    backgroundColor: document.documentElement.classList.contains('dark') ? '#27272a' : '#18181b',
                    titleColor: '#fafafa',
                    bodyColor: '#e4e4e7',
                    padding: 10,
                    cornerRadius: 8,
                    boxPadding: 4,
                    usePointStyle: true,
                    callbacks: { label: (ctx) => ` ${ctx.dataset.label}: ${money(ctx.parsed[props.horizontal ? 'x' : 'y'])}` },
                },
            },
        },
    });
}

let off;
onMounted(() => {
    build();
    off = onThemeChange(() => build());
});
onBeforeUnmount(() => {
    off?.();
    chart?.destroy();
});
watch(() => [props.labels, props.series], build, { deep: true });
</script>

<template>
    <div :style="{ height: height + 'px' }" class="relative">
        <canvas ref="canvas" role="img" :aria-label="series.map((s) => s.label).join(', ') + ' chart'" />
    </div>
</template>

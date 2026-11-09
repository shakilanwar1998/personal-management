<script setup>
// Backend Fix-ups: the diff that fixes a live PHP backend, the tests that prove it, and the
// deadline it beat.
import Frame from '../Frame.vue';

// t: ' ' context, '-' removed, '+' added; parts: [colour, text]
const K = 'text-[#c792ea]'; // keyword
const V = 'text-[#f78c6c]'; // variable
const S = 'text-[#c3e88d]'; // string
const F = 'text-[#82aaff]'; // function
const P = 'text-[#e6e8ec]'; // plain
const C = 'text-white/35'; // comment
const LINES = [
    { n: 41, t: ' ', parts: [[C, '// station forecast for a point']] },
    { n: 42, t: ' ', parts: [[V, '$url'], [P, ' = '], [S, '"…/points/{$lat},{$lon}"'], [P, ';']] },
    { n: 43, t: '-', parts: [[V, '$data'], [P, ' = '], [F, 'json_decode'], [P, '('], [F, 'file_get_contents'], [P, '('], [V, '$url'], [P, '));']] },
    { n: 43, t: '+', parts: [[V, '$ctx'], [P, ' = '], [F, 'stream_context_create'], [P, '(['], [S, "'http'"], [P, ' => ['], [S, "'header'"], [P, ' => '], [V, '$ua'], [P, ']]);']] },
    { n: 44, t: '+', parts: [[V, '$data'], [P, ' = '], [F, 'json_decode'], [P, '('], [F, 'file_get_contents'], [P, '('], [V, '$url'], [P, ', '], [K, 'false'], [P, ', '], [V, '$ctx'], [P, '), '], [K, 'true'], [P, ');']] },
    { n: 45, t: ' ', parts: [] },
    { n: 46, t: '-', parts: [[V, '$rows'], [P, ' = '], [V, '$db'], [P, '->'], [F, 'query'], [P, '('], [S, '"SELECT * FROM stations WHERE id = $id"'], [P, ');']] },
    { n: 47, t: '+', parts: [[V, '$stmt'], [P, ' = '], [V, '$db'], [P, '->'], [F, 'prepare'], [P, '('], [S, "'SELECT * FROM stations WHERE id = ?'"], [P, ');']] },
    { n: 48, t: '+', parts: [[V, '$stmt'], [P, '->'], [F, 'execute'], [P, '(['], [V, '$id'], [P, ']);']] },
    { n: 49, t: ' ', parts: [[K, 'return'], [P, ' '], [V, '$data'], [P, '['], [S, "'properties'"], [P, '] ?? [];']] },
];
const ROW = { ' ': '', '-': 'bg-[#f87171]/[.13]', '+': 'bg-[#4ade80]/[.12]' };
const SIGN = { ' ': 'text-white/20', '-': 'text-[#f87171]', '+': 'text-[#4ade80]' };
</script>

<template>
    <!-- the diff -->
    <div class="absolute right-[-4cqw] top-[5cqw] w-[56cqw] rotate-[1.5deg]">
        <Frame kind="window" url="forecast.php — fix/timeout-and-injection" :dark="true">
            <div class="flex border-b border-white/10 text-[1.15cqw] text-white/45">
                <span class="border-b-[0.2cqw] border-[#8892bf] bg-white/[.04] px-[1.6cqw] py-[0.7cqw] text-white/85">forecast.php</span>
                <span class="px-[1.6cqw] py-[0.7cqw]">StationRepo.php</span>
                <span class="ml-auto px-[1.6cqw] py-[0.7cqw]"><span class="text-[#4ade80]">+4</span> <span class="text-[#f87171]">−2</span></span>
            </div>
            <div class="py-[1cqw] pb-[4cqw] font-mono text-[1.15cqw] leading-[2]">
                <div v-for="(l, i) in LINES" :key="i" class="flex whitespace-nowrap pr-[1cqw]" :class="ROW[l.t]">
                    <span class="w-[4cqw] shrink-0 pr-[1cqw] text-right text-white/25">{{ l.n }}</span>
                    <span class="w-[1.8cqw] shrink-0" :class="SIGN[l.t]">{{ l.t === ' ' ? '' : l.t === '-' ? '−' : '+' }}</span>
                    <span :class="l.t === '-' ? 'opacity-70' : ''"><span v-for="(p, j) in l.parts" :key="j" :class="p[0]">{{ p[1] }}</span>&nbsp;</span>
                </div>
            </div>
        </Frame>
    </div>

    <!-- the tests -->
    <div class="absolute bottom-[8.5cqw] left-[26cqw] w-[28cqw] -rotate-[2deg] overflow-hidden rounded-[1.3cqw] bg-[#0f1216] font-mono text-[1.2cqw] leading-[1.65] text-[#d6dbe3] shadow-[0_1.8cqw_4.5cqw_rgb(0_0_0/.6)] ring-1 ring-white/15">
        <div class="flex items-center gap-[0.6cqw] border-b border-white/10 bg-[#171b21] px-[1.2cqw] py-[0.7cqw]">
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#ff5f57]"></span>
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#febc2e]"></span>
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#28c840]"></span>
        </div>
        <div class="px-[1.4cqw] py-[1cqw]">
            <p><span class="text-[#8892bf]">$</span> vendor/bin/phpunit</p>
            <p class="tracking-[0.1em] text-[#4ade80]">.........</p>
            <p class="text-white/45">9 / 9 (100%) · 0.41 s</p>
            <p class="mt-[0.3cqw] inline-block rounded-[0.4cqw] bg-[#16a34a] px-[0.7cqw] font-semibold text-white">OK (9 tests, 21 assertions)</p>
        </div>
    </div>

    <!-- the deadline -->
    <div class="absolute bottom-[4.5cqw] right-[5cqw] flex rotate-[2deg] items-center gap-[1.1cqw] rounded-[1.5cqw] bg-white px-[1.5cqw] py-[1.1cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.5)]">
        <span class="grid h-[3.4cqw] w-[3.4cqw] place-items-center rounded-full bg-[#16a34a] text-white">
            <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-[1.55cqw] font-bold">Fixed before deadline</span>
            <span class="block text-[1.2cqw] text-black/50">deployed Thu 16:40 · due Fri</span>
        </span>
    </div>
</template>

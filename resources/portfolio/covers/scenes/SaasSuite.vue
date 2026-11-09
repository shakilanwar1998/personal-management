<script setup>
// SaaS Business Suite: the suite's dashboard (CRM, invoices, inbox, courses, API) and the Slack
// alert that fires on an error burst — then reports the recovery.
import Frame from '../Frame.vue';
import { logo } from '../../logos.js';

const slack = logo('slack');
const MODULES = [
    { n: 'CRM', d: 'M16 19c0-2.2-1.8-4-4-4s-4 1.8-4 4M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6z' },
    { n: 'Invoices', d: 'M7 3h10v18l-2.5-1.5L12 21l-2.5-1.5L7 21zM10 8h4M10 12h4', on: true },
    { n: 'Inbox', badge: 3, d: 'M4 13l2.5-8h11l2.5 8v6H4zM4 13h5l1 2h4l1-2h5' },
    { n: 'Courses', d: 'M4 5h16v11H4zM10 8.5v4l3.5-2zM8 20h8' },
    { n: 'API', d: 'M9 7l-5 5 5 5M15 7l5 5-5 5' },
];
const BARS = [38, 46, 41, 55, 52, 63, 58, 70, 66, 78, 74, 88];
const INVOICES = [
    { no: 'INV-2041', who: 'Acme Studio', amt: '€1,280.00', st: 'Paid' },
    { no: 'INV-2040', who: 'Northwind Ltd', amt: '€640.00', st: 'Paid' },
    { no: 'INV-2039', who: 'Contoso GmbH', amt: '€2,150.00', st: 'Due' },
];
</script>

<template>
    <div class="absolute left-[45.5cqw] top-[5.5cqw] w-[52cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="app.suite · invoices">
            <div class="flex">
                <!-- modules -->
                <div class="w-[11.5cqw] shrink-0 border-r border-black/[.06] bg-[#f6f7f9] px-[1cqw] py-[1.4cqw]">
                    <p class="mb-[1.2cqw] flex items-center gap-[0.6cqw] px-[0.6cqw] text-[1.3cqw] font-bold">
                        <span class="h-[1.8cqw] w-[1.8cqw] rounded-[0.5cqw] bg-gradient-to-br from-[#5aa9ff] to-[#3730a3]"></span>
                        Suite
                    </p>
                    <p
                        v-for="m in MODULES"
                        :key="m.n"
                        class="flex items-center gap-[0.7cqw] rounded-[0.6cqw] px-[0.6cqw] py-[0.55cqw] text-[1.2cqw] font-medium"
                        :class="m.on ? 'bg-white font-semibold text-[#3730a3] shadow-[0_0.1cqw_0.4cqw_rgb(0_0_0/.08)]' : 'text-black/55'"
                    >
                        <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw] shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path :d="m.d" /></svg>
                        {{ m.n }}
                        <span v-if="m.badge" class="ml-auto grid h-[1.6cqw] min-w-[1.6cqw] place-items-center rounded-full bg-[#e5484d] px-[0.3cqw] text-[0.95cqw] font-bold text-white">{{ m.badge }}</span>
                    </p>
                </div>

                <!-- invoices dashboard -->
                <div class="min-w-0 flex-1 p-[1.6cqw]">
                    <div class="flex items-end justify-between leading-tight">
                        <span>
                            <span class="block text-[1.1cqw] font-medium text-black/45">Revenue · last 12 months</span>
                            <span class="block text-[2.4cqw] font-bold tracking-[-0.03em]">€48,920</span>
                        </span>
                        <span class="rounded-full bg-[#30a46c]/[.12] px-[0.9cqw] py-[0.2cqw] text-[1.1cqw] font-semibold text-[#2f8a5b]">+18%</span>
                    </div>
                    <div class="mt-[1cqw] flex h-[8cqw] items-end gap-[0.6cqw] border-b border-black/[.08]">
                        <span v-for="(b, i) in BARS" :key="i" class="flex-1 rounded-t-[0.3cqw]" :class="i === BARS.length - 1 ? 'bg-[#3730a3]' : 'bg-[#5aa9ff]/55'" :style="{ height: `${b}%` }"></span>
                    </div>
                    <div class="mt-[1.4cqw] divide-y divide-black/[.06]">
                        <div v-for="v in INVOICES" :key="v.no" class="flex items-center gap-[1cqw] py-[0.65cqw] text-[1.2cqw]">
                            <span class="font-mono text-[1.05cqw] text-black/45">{{ v.no }}</span>
                            <span class="min-w-0 flex-1 truncate font-semibold">{{ v.who }}</span>
                            <span class="font-semibold tabular-nums">{{ v.amt }}</span>
                            <span class="w-[4.2cqw] rounded-full py-[0.15cqw] text-center text-[1cqw] font-semibold" :class="v.st === 'Paid' ? 'bg-[#30a46c]/[.12] text-[#2f8a5b]' : 'bg-[#f59e0b]/15 text-[#a16207]'">{{ v.st }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-[2cqw]"></div>
        </Frame>
    </div>

    <!-- Slack alert -->
    <div class="absolute bottom-[7.5cqw] left-[26cqw] w-[25cqw] -rotate-[2deg] rounded-[1.6cqw] bg-white px-[1.5cqw] py-[1.2cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <p class="flex items-center gap-[0.7cqw] text-[1.3cqw] font-bold">
            <svg viewBox="0 0 24 24" class="h-[1.8cqw] w-[1.8cqw]" :fill="slack.hex"><path :d="slack.path" /></svg>
            #alerts
            <span class="ml-auto text-[1.05cqw] font-medium text-black/40">Suite bot</span>
        </p>
        <div class="mt-[0.9cqw] space-y-[0.6cqw] text-[1.2cqw] leading-tight">
            <p class="flex items-start gap-[0.7cqw]">
                <span class="mt-[0.25cqw] h-[1cqw] w-[1cqw] shrink-0 rounded-full bg-[#f59e0b]"></span>
                <span class="min-w-0 flex-1"><span class="font-semibold">Error burst</span> on queue “default”</span>
                <span class="font-mono text-[1cqw] text-black/40">09:14</span>
            </p>
            <p class="flex items-start gap-[0.7cqw]">
                <span class="mt-[0.25cqw] h-[1cqw] w-[1cqw] shrink-0 rounded-full bg-[#30a46c]"></span>
                <span class="min-w-0 flex-1"><span class="font-semibold">Recovered</span> · queue drained</span>
                <span class="font-mono text-[1cqw] text-black/40">09:31</span>
            </p>
        </div>
    </div>
</template>

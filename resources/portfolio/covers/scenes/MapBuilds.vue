<script setup>
// Map Builds: a client site's map with a nearby-places search: the radius around you, the
// pins inside it, and the list sorted by distance.
import Frame from '../Frame.vue';

const YOU = [250, 196];
const PINS = [
    { x: 196, y: 146, c: '#ea4335', l: 'A' },
    { x: 318, y: 168, c: '#ea4335', l: 'B' },
    { x: 250, y: 282, c: '#ea4335', l: 'C' },
    { x: 330, y: 252, c: '#ea4335', l: 'D' },
    { x: 86, y: 92, c: '#9aa0a6', l: '' },
    { x: 452, y: 300, c: '#9aa0a6', l: '' },
];
const NEARBY = [
    { l: 'A', name: 'Corner Café', sub: 'Café · open now', d: '0.2 km' },
    { l: 'B', name: 'Riverside Pharmacy', sub: 'Pharmacy', d: '0.4 km' },
    { l: 'C', name: 'Green Park', sub: 'Park', d: '0.6 km' },
    { l: 'D', name: 'Central Bus Stop', sub: 'Transit', d: '0.8 km' },
];
</script>

<template>
    <!-- the map -->
    <div class="absolute right-[-3cqw] top-[5cqw] w-[54cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="example-site.com/near-me">
            <div class="relative h-[44cqw] overflow-hidden bg-[#e6e8e1]">
                <svg viewBox="0 0 500 400" preserveAspectRatio="xMidYMid slice" class="absolute inset-0 h-full w-full" aria-hidden="true">
                    <!-- water -->
                    <path d="M410 0 C 380 70, 450 120, 420 200 S 360 320, 400 400 L 500 400 L 500 0 Z" fill="#b6d7f0" />
                    <!-- park -->
                    <rect x="210" y="228" width="80" height="80" rx="8" fill="#c9e5bd" />
                    <circle cx="224" cy="294" r="7" fill="#a9d69a" /><circle cx="272" cy="244" r="9" fill="#a9d69a" /><circle cx="276" cy="292" r="7" fill="#a9d69a" />
                    <!-- buildings -->
                    <g fill="#d9dcd3">
                        <rect x="16" y="16" width="56" height="40" rx="3" /><rect x="108" y="18" width="76" height="36" rx="3" /><rect x="216" y="14" width="68" height="42" rx="3" /><rect x="312" y="18" width="70" height="38" rx="3" />
                        <rect x="16" y="168" width="58" height="40" rx="3" /><rect x="104" y="244" width="80" height="58" rx="3" /><rect x="16" y="252" width="56" height="50" rx="3" /><rect x="216" y="104" width="68" height="34" rx="3" /><rect x="312" y="100" width="68" height="40" rx="3" />
                        <rect x="16" y="330" width="60" height="50" rx="3" /><rect x="216" y="324" width="66" height="56" rx="3" /><rect x="312" y="326" width="58" height="50" rx="3" />
                    </g>
                    <!-- streets -->
                    <g stroke="#ffffff" stroke-width="12" fill="none" stroke-linecap="round">
                        <path d="M0 80 H 500" /><path d="M0 160 H 400" /><path d="M0 316 H 500" />
                        <path d="M88 0 V 400" /><path d="M200 0 V 400" /><path d="M298 0 V 400" />
                    </g>
                    <!-- main road + bridge -->
                    <path d="M-10 236 C 120 226, 180 210, 260 206 S 420 190, 520 168" stroke="#ffffff" stroke-width="22" fill="none" />
                    <path d="M-10 236 C 120 226, 180 210, 260 206 S 420 190, 520 168" stroke="#f8d78a" stroke-width="15" fill="none" />

                    <!-- nearby radius -->
                    <circle :cx="YOU[0]" :cy="YOU[1]" r="112" fill="#1a73e8" fill-opacity=".1" stroke="#1a73e8" stroke-opacity=".7" stroke-width="2.5" stroke-dasharray="8 6" />
                    <g :transform="`translate(${YOU[0] + 50}, ${YOU[1] - 112})`">
                        <rect x="0" y="-12" width="46" height="24" rx="12" fill="#1a73e8" />
                        <text x="23" y="5" text-anchor="middle" font-size="14" font-weight="700" fill="#fff" font-family="ui-sans-serif, system-ui">1 km</text>
                    </g>

                    <!-- pins -->
                    <g v-for="(p, i) in PINS" :key="i" :transform="`translate(${p.x - 14}, ${p.y - 36})`">
                        <path d="M14 0A14 14 0 0 0 0 14c0 10 11 19 12.8 21.2a1.6 1.6 0 0 0 2.4 0C17 33 28 24 28 14A14 14 0 0 0 14 0z" :fill="p.c" stroke="#fff" stroke-width="2.4" />
                        <text v-if="p.l" x="14" y="19" text-anchor="middle" font-size="14" font-weight="700" fill="#fff" font-family="ui-sans-serif, system-ui">{{ p.l }}</text>
                        <circle v-else cx="14" cy="14" r="5" fill="#fff" />
                    </g>

                    <!-- you are here -->
                    <circle :cx="YOU[0]" :cy="YOU[1]" r="20" fill="#1a73e8" fill-opacity=".2" />
                    <circle :cx="YOU[0]" :cy="YOU[1]" r="10" fill="#1a73e8" stroke="#fff" stroke-width="4" />
                </svg>

                <!-- search on the map -->
                <div class="absolute left-[2cqw] top-[1.8cqw] flex items-center gap-[0.9cqw] rounded-full bg-white px-[1.5cqw] py-[0.8cqw] text-[1.35cqw] font-medium text-black/70 shadow-[0_0.6cqw_1.6cqw_rgb(0_0_0/.18)]">
                    <svg viewBox="0 0 24 24" class="h-[1.5cqw] w-[1.5cqw] text-[#1a73e8]" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><circle cx="10.5" cy="10.5" r="6.5" /><path d="m20 20-4.8-4.8" /></svg>
                    Places within 1 km
                </div>
            </div>
        </Frame>
    </div>

    <!-- nearby results -->
    <div class="absolute bottom-[8cqw] left-[22cqw] w-[26cqw] -rotate-[2deg] rounded-[1.5cqw] bg-white p-[1.5cqw] text-[#14161a] shadow-[0_1.8cqw_4.5cqw_rgb(0_0_0/.5)]">
        <p class="flex items-baseline justify-between text-[1.6cqw] font-bold">Nearby <span class="text-[1.15cqw] font-medium text-black/45">4 places · by distance</span></p>
        <div v-for="(r, i) in NEARBY" :key="r.l" class="flex items-center gap-[1cqw] py-[0.75cqw]" :class="i ? 'border-t border-black/[.06]' : 'mt-[0.6cqw]'">
            <span class="grid h-[2.4cqw] w-[2.4cqw] shrink-0 place-items-center rounded-full bg-[#ea4335] text-[1.15cqw] font-bold text-white">{{ r.l }}</span>
            <span class="min-w-0 leading-tight">
                <span class="block truncate text-[1.35cqw] font-semibold">{{ r.name }}</span>
                <span class="block text-[1.1cqw] text-black/45">{{ r.sub }}</span>
            </span>
            <span class="ml-auto shrink-0 text-[1.25cqw] font-semibold text-[#1a73e8]">{{ r.d }}</span>
        </div>
    </div>
</template>

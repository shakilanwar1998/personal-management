<script setup>
// Fragrance Store Kit: the storefront, built around how people buy perfume:
// by house, by scent note, and by which bottle size is worth it.
import Frame from '../Frame.vue';

const HOUSES = [
    { n: 'Ambre', c: '#b7773a' },
    { n: 'Oud Atelier', c: '#5b3a29' },
    { n: 'Vétiver & Co', c: '#5f7a4a' },
    { n: 'Lumière', c: '#c98a9a' },
];

// bottle shapes: tall | round | square
const PRODUCTS = [
    { name: 'Santal Nuit', house: 'Oud Atelier', price: '€48', shape: 'tall', glass: '#f3c98b', liquid: '#c8822f', cap: '#2b1d14', bg: '#f4ebe0', notes: ['bergamot', 'oud', 'vanilla'] },
    { name: 'Rose Poudrée', house: 'Lumière', price: '€56', shape: 'round', glass: '#f6cfd6', liquid: '#d9899a', cap: '#c9a36a', bg: '#f7e9ec', notes: ['rose', 'iris', 'musk'] },
    { name: 'Figue Verte', house: 'Vétiver & Co', price: '€44', shape: 'square', glass: '#d7e3c4', liquid: '#86a15e', cap: '#1f2a1a', bg: '#eaefe2', notes: ['fig', 'cedar', 'vetiver'] },
];

const SIZES = [
    { ml: 30, price: '€48', per: '€1.60 / ml' },
    { ml: 50, price: '€72', per: '€1.44 / ml' },
    { ml: 100, price: '€110', per: '€1.10 / ml', best: true },
];
</script>

<template>
    <div class="absolute right-[-3cqw] top-[5cqw] w-[54cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="maison-note.shop">
            <div class="bg-[#fbf7f1] px-[1.8cqw] pb-[3cqw] pt-[1.4cqw] text-[#2a2118]">
                <!-- header -->
                <div class="flex items-center gap-[1.6cqw] text-[1.1cqw]">
                    <span class="font-serif text-[1.8cqw] font-semibold tracking-[0.18em]">MAISON NOTE</span>
                    <span class="ml-auto text-black/55">Houses</span>
                    <span class="text-black/55">Notes</span>
                    <span class="text-black/55">Sizes</span>
                    <span class="grid h-[2.2cqw] w-[2.2cqw] place-items-center rounded-full bg-[#2a2118] text-[1cqw] font-bold text-[#fbf7f1]">2</span>
                </div>

                <!-- shop by house -->
                <p class="mt-[1.4cqw] font-serif text-[1.25cqw] italic text-black/55">Shop by house</p>
                <div class="mt-[0.6cqw] flex gap-[0.8cqw]">
                    <span v-for="(h, i) in HOUSES" :key="h.n" class="flex items-center gap-[0.6cqw] rounded-full py-[0.35cqw] pl-[0.35cqw] pr-[1cqw] text-[1.1cqw] font-medium" :class="i === 1 ? 'bg-[#2a2118] text-[#fbf7f1]' : 'bg-white ring-1 ring-black/[.08]'">
                        <span class="grid h-[1.9cqw] w-[1.9cqw] place-items-center rounded-full font-serif text-[1cqw] font-semibold text-white" :style="{ background: h.c }">{{ h.n.charAt(0) }}</span>
                        {{ h.n }}
                    </span>
                </div>

                <!-- products -->
                <div class="mt-[1.4cqw] grid grid-cols-3 gap-[1.2cqw]">
                    <div v-for="p in PRODUCTS" :key="p.name" class="overflow-hidden rounded-[1cqw] bg-white ring-1 ring-black/[.06]">
                        <div class="grid h-[11cqw] place-items-center" :style="{ background: p.bg }">
                            <svg viewBox="0 0 40 60" class="h-[9cqw] w-auto drop-shadow-[0_0.6cqw_0.8cqw_rgb(80_50_20/.25)]" aria-hidden="true">
                                <template v-if="p.shape === 'tall'">
                                    <rect x="15" y="2" width="10" height="11" rx="1.5" :fill="p.cap" />
                                    <rect x="17" y="13" width="6" height="4" :fill="p.glass" />
                                    <rect x="9" y="17" width="22" height="40" rx="3" :fill="p.glass" />
                                    <rect x="11" y="27" width="18" height="28" rx="2" :fill="p.liquid" opacity=".85" />
                                    <rect x="12" y="20" width="2.5" height="30" rx="1.2" fill="#fff" opacity=".55" />
                                </template>
                                <template v-else-if="p.shape === 'round'">
                                    <circle cx="20" cy="9" r="6" :fill="p.cap" />
                                    <rect x="17" y="14" width="6" height="6" :fill="p.glass" />
                                    <circle cx="20" cy="38" r="18" :fill="p.glass" />
                                    <path d="M3.6 42a16.5 16.5 0 0 0 32.8 0z" :fill="p.liquid" opacity=".85" />
                                    <ellipse cx="12" cy="31" rx="2.4" ry="6" fill="#fff" opacity=".55" />
                                </template>
                                <template v-else>
                                    <rect x="13" y="4" width="14" height="10" rx="1" :fill="p.cap" />
                                    <rect x="16.5" y="14" width="7" height="5" :fill="p.glass" />
                                    <rect x="5" y="19" width="30" height="38" rx="2" :fill="p.glass" />
                                    <rect x="7" y="30" width="26" height="25" rx="1.5" :fill="p.liquid" opacity=".85" />
                                    <rect x="8" y="22" width="3" height="31" rx="1.5" fill="#fff" opacity=".55" />
                                    <rect x="12" y="35" width="16" height="9" rx="1" fill="#fff" opacity=".8" />
                                </template>
                            </svg>
                        </div>
                        <div class="p-[1cqw]">
                            <p class="text-[0.9cqw] font-semibold uppercase tracking-[0.12em] text-black/45">{{ p.house }}</p>
                            <p class="flex items-baseline justify-between font-serif text-[1.45cqw] font-semibold leading-tight">{{ p.name }}</p>
                            <p class="text-[1.05cqw] text-black/55">from <span class="font-semibold text-[#2a2118]">{{ p.price }}</span></p>
                            <div class="mt-[0.6cqw] flex flex-wrap gap-[0.4cqw]">
                                <span v-for="n in p.notes" :key="n" class="rounded-full bg-[#c9a36a]/15 px-[0.6cqw] py-[0.15cqw] text-[0.9cqw] font-medium text-[#7a5a2c]">{{ n }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Frame>
    </div>

    <!-- bottle-size picker -->
    <div class="absolute left-[26.5cqw] top-[27.5cqw] w-[25cqw] -rotate-[2deg] rounded-[1.6cqw] bg-white p-[1.4cqw] text-[#2a2118] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.45)]">
        <p class="flex items-baseline justify-between">
            <span class="font-serif text-[1.5cqw] font-semibold">Santal Nuit</span>
            <span class="text-[1.05cqw] text-black/45">Choose a size</span>
        </p>
        <div class="mt-[1.4cqw] grid grid-cols-3 gap-[0.7cqw]">
            <div v-for="s in SIZES" :key="s.ml" class="relative rounded-[0.9cqw] px-[0.6cqw] pb-[0.7cqw] pt-[0.9cqw] text-center" :class="s.best ? 'bg-[#2a2118] text-white' : 'ring-1 ring-black/10'">
                <span v-if="s.best" class="absolute -top-[1cqw] left-1/2 -translate-x-1/2 whitespace-nowrap rounded-full bg-[#c9a36a] px-[0.7cqw] py-[0.15cqw] text-[0.9cqw] font-bold text-[#2a2118]">best value</span>
                <span class="block whitespace-nowrap text-[1.35cqw] font-bold">{{ s.ml }} ml</span>
                <span class="block text-[1.15cqw] font-medium">{{ s.price }}</span>
                <span class="block whitespace-nowrap text-[0.85cqw]" :class="s.best ? 'text-white/60' : 'text-black/40'">{{ s.per }}</span>
            </div>
        </div>
        <span class="mt-[1cqw] block rounded-full bg-[#c9a36a] py-[0.6cqw] text-center text-[1.15cqw] font-semibold text-[#2a2118]">Add to bag · €110</span>
    </div>
</template>

<script setup>
// Okkhor: the okkhor.com storefront, a Bangla-first book shop.
import Frame from '../Frame.vue';

const BOOKS = [
    { title: 'পদার্থবিজ্ঞান', sub: 'একাডেমিক', price: '৳ ৩২০', off: '২০%', from: '#1f6feb', to: '#0b3d91' },
    { title: 'সীরাতুন নবী', sub: 'ইসলামিক', price: '৳ ৪৫০', off: '১৫%', from: '#0f9d76', to: '#065f46' },
    { title: 'নদীর গল্প', sub: 'উপন্যাস', price: '৳ ২৮০', off: null, from: '#f66600', to: '#9a3412' },
    { title: 'গণিত অলিম্পিয়াড', sub: 'একাডেমিক', price: '৳ ৩৬০', off: '১০%', from: '#7c3aed', to: '#3b0764' },
];
const CATS = ['সব বই', 'একাডেমিক', 'ইসলামিক', 'উপন্যাস', 'শিশু-কিশোর'];
</script>

<template>
    <!-- storefront -->
    <div class="absolute right-[-3cqw] top-[5cqw] w-[58cqw] rotate-[-1.5deg]">
        <Frame kind="browser" url="okkhor.com">
            <div class="flex items-center gap-[1.4cqw] border-b border-black/[.06] px-[1.8cqw] py-[1.2cqw]">
                <img src="/images/brands/okkhor-logo.png" alt="" class="h-[3.2cqw] w-auto" />
                <span class="flex flex-1 items-center gap-[0.8cqw] rounded-full bg-[#f4f2ef] px-[1.4cqw] py-[0.7cqw] text-[1.25cqw] text-black/45" lang="bn">
                    <svg viewBox="0 0 24 24" class="h-[1.4cqw] w-[1.4cqw]" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="7" /><path d="m20 20-4-4" /></svg>
                    বই, লেখক বা প্রকাশক খুঁজুন
                </span>
                <span class="relative grid h-[3cqw] w-[3cqw] place-items-center rounded-full bg-[#f66600]/10 text-[#f66600]">
                    <svg viewBox="0 0 24 24" class="h-[1.6cqw] w-[1.6cqw]" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 4h2l2.4 11h11.2L21 7H6.2M9 20h.01M18 20h.01" /></svg>
                    <span class="absolute -right-[0.3cqw] -top-[0.3cqw] grid h-[1.6cqw] w-[1.6cqw] place-items-center rounded-full bg-[#f66600] text-[0.95cqw] font-bold text-white">৩</span>
                </span>
            </div>

            <div class="flex gap-[0.7cqw] px-[1.8cqw] pt-[1.2cqw]" lang="bn">
                <span
                    v-for="(c, i) in CATS"
                    :key="c"
                    class="rounded-full px-[1.2cqw] py-[0.4cqw] text-[1.15cqw] font-medium"
                    :class="i === 1 ? 'bg-[#f66600] text-white' : 'bg-[#f4f2ef] text-black/60'"
                >{{ c }}</span>
            </div>

            <div class="mx-[1.8cqw] mt-[1.2cqw] flex items-center justify-between rounded-[1.2cqw] bg-gradient-to-r from-[#fff1e6] to-[#ffe0c7] px-[1.8cqw] py-[1.3cqw]" lang="bn">
                <span>
                    <span class="block text-[1.7cqw] font-bold text-[#9a3412]">ভর্তি প্রস্তুতির বই</span>
                    <span class="block text-[1.15cqw] text-[#9a3412]/70">সব একাডেমিক বইয়ে ২০% পর্যন্ত ছাড়</span>
                </span>
                <span class="rounded-full bg-[#f66600] px-[1.3cqw] py-[0.5cqw] text-[1.15cqw] font-semibold text-white">দেখুন</span>
            </div>

            <div class="grid grid-cols-4 gap-[1.2cqw] px-[1.8cqw] py-[1.4cqw]" lang="bn">
                <div v-for="b in BOOKS" :key="b.title" class="rounded-[1cqw] border border-black/[.06] p-[0.8cqw]">
                    <div class="relative grid aspect-[3/4] place-items-center overflow-hidden rounded-[0.6cqw] shadow-[inset_-0.5cqw_0_0_rgb(0_0_0/.18)]" :style="{ background: `linear-gradient(150deg, ${b.from}, ${b.to})` }">
                        <span class="px-[0.6cqw] text-center text-[1.2cqw] font-bold leading-tight text-white">{{ b.title }}</span>
                        <span v-if="b.off" class="absolute left-[0.4cqw] top-[0.4cqw] rounded-[0.4cqw] bg-white px-[0.5cqw] text-[0.95cqw] font-bold text-[#f66600]">-{{ b.off }}</span>
                    </div>
                    <p class="mt-[0.7cqw] truncate text-[1.15cqw] font-semibold">{{ b.title }}</p>
                    <p class="text-[1cqw] text-black/45">{{ b.sub }}</p>
                    <p class="mt-[0.4cqw] flex items-center justify-between text-[1.2cqw] font-bold text-[#f66600]">
                        {{ b.price }}
                        <span class="grid h-[2cqw] w-[2cqw] place-items-center rounded-full bg-[#f66600] text-[1.3cqw] leading-none text-white">+</span>
                    </p>
                </div>
            </div>
        </Frame>
    </div>

</template>

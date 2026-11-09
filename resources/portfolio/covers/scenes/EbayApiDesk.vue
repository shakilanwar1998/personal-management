<script setup>
// eBay API on Call: the OAuth consent screen a seller finally gets to, and the token refresh
// that now comes back 200.
import Frame from '../Frame.vue';

const SCOPES = [
    'View and manage your inventory and offers',
    'View and manage your order fulfilments',
    'View your account settings and policies',
    'View your selling analytics',
];
</script>

<template>
    <!-- the consent screen -->
    <div class="absolute right-[-2cqw] top-[5cqw] w-[48cqw] rotate-[2deg]">
        <Frame kind="browser" url="auth.ebay.com/oauth2/authorize?…">
            <div class="px-[3cqw] pb-[3.6cqw] pt-[2.2cqw]">
                <span class="text-[2.6cqw] font-extrabold leading-none tracking-[-0.06em]"><span class="text-[#e53238]">e</span><span class="text-[#0064d2]">b</span><span class="text-[#f5af02]">a</span><span class="text-[#86b817]">y</span></span>
                <p class="mt-[1.6cqw] text-[2.1cqw] font-bold leading-tight">Grant application access</p>
                <p class="mt-[0.6cqw] text-[1.3cqw] leading-snug text-black/55"><b class="font-semibold text-black/80">Seller Tools (Production)</b> would like to access your eBay account to:</p>
                <ul class="mt-[1.4cqw] space-y-[0.8cqw]">
                    <li v-for="s in SCOPES" :key="s" class="flex items-center gap-[1cqw] text-[1.3cqw] text-black/75">
                        <span class="grid h-[1.9cqw] w-[1.9cqw] shrink-0 place-items-center rounded-full bg-[#e8f0fe] text-[#3665f3]">
                            <svg viewBox="0 0 24 24" class="h-[65%] w-[65%]" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                        </span>
                        {{ s }}
                    </li>
                </ul>
                <div class="mt-[2cqw] flex items-center gap-[1.2cqw]">
                    <span class="rounded-full bg-[#3665f3] px-[3.4cqw] py-[0.9cqw] text-[1.4cqw] font-semibold text-white shadow-[0_0.6cqw_1.4cqw_rgb(54_101_243/.35)]">Agree</span>
                    <span class="rounded-full border border-black/20 px-[2.4cqw] py-[0.9cqw] text-[1.4cqw] font-semibold text-black/60">Not now</span>
                </div>
            </div>
        </Frame>
    </div>

    <!-- the token refresh that now works -->
    <div class="absolute bottom-[8.5cqw] left-[22cqw] w-[31cqw] -rotate-[2deg] overflow-hidden rounded-[1.3cqw] bg-[#0f1216] font-mono text-[1.15cqw] leading-[1.5] text-[#d6dbe3] shadow-[0_1.8cqw_4.5cqw_rgb(0_0_0/.55)] ring-1 ring-white/10">
        <div class="flex items-center gap-[0.6cqw] border-b border-white/10 bg-[#171b21] px-[1.2cqw] py-[0.7cqw]">
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#ff5f57]"></span>
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#febc2e]"></span>
            <span class="h-[0.9cqw] w-[0.9cqw] rounded-full bg-[#28c840]"></span>
            <span class="ml-[0.8cqw] text-[1.05cqw] text-white/45">refresh-token.sh</span>
        </div>
        <div class="px-[1.4cqw] py-[1cqw]">
            <p><span class="text-[#5aa9ff]">$</span> POST /identity/v1/oauth2/token</p>
            <p class="text-white/40">&nbsp;&nbsp;grant_type=refresh_token</p>
            <p class="mt-[0.4cqw] font-semibold text-[#4ade80]">HTTP/1.1 200 OK</p>
            <p><span class="text-white/40">{</span> <span class="text-[#93c5fd]">"access_token"</span>: <span class="text-[#fcd34d]">"v^1.1#i^1#p^3#r^1…"</span>,</p>
            <p>&nbsp;&nbsp;<span class="text-[#93c5fd]">"expires_in"</span>: <span class="text-[#f0abfc]">7200</span>,</p>
            <p>&nbsp;&nbsp;<span class="text-[#93c5fd]">"token_type"</span>: <span class="text-[#fcd34d]">"User Access Token"</span> <span class="text-white/40">}</span></p>
        </div>
    </div>
</template>

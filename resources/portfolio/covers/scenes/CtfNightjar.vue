<script setup>
// Operation Nightjar: an incident-response session in the lab's terminal (hidden files first, then
// the auth log), and the five objectives from the player brief. Flag values and IPs are placeholders
// (documentation ranges), never the lab's real answers.
import Frame from '../Frame.vue';

const LS = [
    { perm: 'drwxr-xr-x', size: '4096', name: '.' },
    { perm: '-rw-r--r--', size: ' 220', name: '.bashrc' },
    { perm: '-rw-------', size: ' 512', name: '.notes_old', hit: true },
    { perm: '-rw-r--r--', size: '1.2K', name: 'portal.conf' },
    { perm: 'drwxr-xr-x', size: '4096', name: 'backups/' },
];
const IPS = [
    { n: '412', ip: '203.0.113.47', hit: true },
    { n: ' 38', ip: '198.51.100.12' },
    { n: '  9', ip: '198.51.100.83' },
    { n: '  2', ip: '203.0.113.9' },
];
const FLAGS = [
    { k: 'Recon', d: 'hidden files', ok: true },
    { k: 'Bonus', d: 'not what it says', ok: true },
    { k: 'Loot', d: 'secret in plain sight', ok: true },
    { k: 'Root', d: 'a root cron job', ok: false },
    { k: 'Forensics', d: 'the attacker’s IP', ok: false, now: true },
];
</script>

<template>
    <!-- the responder's terminal -->
    <div class="absolute right-[-4cqw] top-[5cqw] w-[57cqw] rotate-[-1.2deg]">
        <Frame kind="window" url="analyst@lab: ~/target" dark>
            <div class="px-[1.8cqw] pb-[2.4cqw] pt-[1.4cqw] font-mono text-[1.2cqw] leading-[1.6]">
                <p><span class="text-[#3ddc97]">analyst@lab</span><span class="text-white/40">:~/target$</span> ls -la home/dev</p>
                <p v-for="f in LS" :key="f.name" class="whitespace-pre" :class="f.hit ? '-mx-[0.6cqw] rounded-[0.4cqw] bg-[#3ddc97]/15 px-[0.6cqw] text-[#a7f3d0]' : 'text-white/55'">{{ f.perm }}  dev dev  {{ f.size }}  {{ f.name }}<span v-if="f.hit" class="text-[#3ddc97]">   ← hidden</span></p>

                <p class="mt-[1cqw]"><span class="text-[#3ddc97]">analyst@lab</span><span class="text-white/40">:~/target$</span> grep "Failed password" var/log/auth.log \</p>
                <p class="pl-[2.4cqw]">| awk '{print $11}' | sort | uniq -c | sort -rn</p>
                <p v-for="r in IPS" :key="r.ip" class="whitespace-pre" :class="r.hit ? '-mx-[0.6cqw] rounded-[0.4cqw] bg-[#ff5f57]/15 px-[0.6cqw] font-semibold text-[#ffb4ae]' : 'text-white/55'">    {{ r.n }} {{ r.ip }}<span v-if="r.hit" class="font-normal text-[#ff8a80]">   ← brute force</span></p>

                <p class="mt-[1cqw]"><span class="text-[#3ddc97]">analyst@lab</span><span class="text-white/40">:~/target$</span> <span class="inline-block h-[1.3cqw] w-[0.7cqw] translate-y-[0.2cqw] bg-white/80"></span></p>
            </div>
        </Frame>
    </div>

    <!-- the five objectives -->
    <div class="absolute bottom-[8cqw] left-[24cqw] w-[22cqw] rotate-[-2.5deg] rounded-[1.4cqw] bg-white p-[1.4cqw] text-[#14161a] shadow-[0_1.6cqw_4cqw_rgb(0_0_0/.5)]">
        <p class="flex items-center justify-between text-[1.3cqw] font-bold">
            Flags
            <span class="rounded-full bg-[#30a46c]/12 px-[0.8cqw] py-[0.15cqw] text-[1cqw] font-semibold text-[#1f7a4d]">3 / 5 captured</span>
        </p>
        <div class="mt-[0.8cqw] space-y-[0.45cqw]">
            <div
                v-for="(f, i) in FLAGS"
                :key="f.k"
                class="flex items-center gap-[0.8cqw] rounded-[0.7cqw] px-[0.7cqw] py-[0.4cqw]"
                :class="f.ok ? 'bg-[#30a46c]/[.07]' : f.now ? 'ring-[0.2cqw] ring-[#3ddc97]/60' : 'bg-black/[.03]'"
            >
                <span
                    class="grid h-[1.9cqw] w-[1.9cqw] shrink-0 place-items-center rounded-full text-[0.95cqw] font-bold"
                    :class="f.ok ? 'bg-[#30a46c] text-white' : 'border border-black/15 text-black/40'"
                >
                    <svg v-if="f.ok" viewBox="0 0 24 24" class="h-[62%] w-[62%]" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5 10 17 19 7" /></svg>
                    <template v-else>{{ i + 1 }}</template>
                </span>
                <span class="min-w-0 flex-1 leading-tight">
                    <span class="block text-[1.15cqw] font-semibold">{{ f.k }}</span>
                    <span class="block truncate text-[0.9cqw] text-black/45">{{ f.d }}</span>
                </span>
                <span class="font-mono text-[0.95cqw]" :class="f.ok ? 'text-[#1f7a4d]' : 'text-black/25'">PCSI{••••}</span>
            </div>
        </div>
    </div>
</template>

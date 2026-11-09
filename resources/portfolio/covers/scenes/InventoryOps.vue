<script setup>
// Inventory Ops Hub: the back-office stock table with a low-stock alert, and a messy vendor
// Excel sheet with the detected header row, UPC and price columns picked out.
import Frame from '../Frame.vue';

const ROWS = [
    { sku: 'SKU-2081', item: 'USB-C hub, 7-port', stock: 84, ch: 'eBay' },
    { sku: 'SKU-3317', item: 'Desk lamp, LED', stock: 52, ch: 'Web' },
    { sku: 'SKU-4471', item: 'Wireless mouse', stock: 3, ch: 'eBay', low: true },
    { sku: 'SKU-5120', item: 'Laptop stand', stock: 37, ch: 'Amazon' },
    { sku: 'SKU-6094', item: 'HDMI cable 2 m', stock: 66, ch: 'eBay' },
];
const CH = { eBay: 'bg-[#e8f0fe] text-[#1d4ed8]', Web: 'bg-[#ecfdf5] text-[#047857]', Amazon: 'bg-[#fff7ed] text-[#c2410c]' };

const COLS = ['A', 'B', 'C', 'D', 'E'];
const SHEET = [
    ['Acme Supply – price list', '', '', '', ''],
    ['valid until 30/09', '', '', '', ''],
    ['Item', 'UPC', 'Description', 'Cost', 'Qty'],
    ['MS-220', '0 84512 77301', 'Wireless mouse', '4.80', '200'],
    ['HB-7C', '0 84512 77318', 'USB-C hub 7p', '11.25', '80'],
    ['LS-AL', '0 84512 77325', 'Laptop stand', '9.10', '120'],
];
const HEADER = 2; // detected header row (0-based)
const PICK = { 1: 'upc', 3: 'price' };
</script>

<template>
    <!-- the back office -->
    <div class="absolute right-[-4cqw] top-[5cqw] w-[54cqw] rotate-[1.5deg]">
        <Frame kind="browser" url="ops.internal / inventory">
            <div class="flex items-center gap-[1.6cqw] border-b border-black/[.07] px-[2cqw] py-[1.2cqw] text-[1.3cqw] font-medium text-black/45">
                <span class="border-b-[0.25cqw] border-[#334155] pb-[0.4cqw] font-semibold text-[#14161a]">Inventory</span>
                <span class="pb-[0.4cqw]">Orders</span>
                <span class="pb-[0.4cqw]">Vendors</span>
                <span class="ml-auto mr-[3cqw] flex items-center gap-[0.5cqw] text-[1.1cqw] text-black/50"><span class="h-[0.8cqw] w-[0.8cqw] rounded-full bg-[#22c55e]"></span>orders synced 1 min ago</span>
            </div>
            <div class="px-[2cqw] pb-[3cqw] pt-[0.8cqw]">
                <div class="grid grid-cols-[9cqw_1fr_13cqw_7cqw] items-center gap-x-[1.2cqw] py-[0.8cqw] text-[1.05cqw] font-semibold uppercase tracking-[0.06em] text-black/40">
                    <span>SKU</span><span>Item</span><span>Stock</span><span>Channel</span>
                </div>
                <div
                    v-for="r in ROWS"
                    :key="r.sku"
                    class="grid grid-cols-[9cqw_1fr_13cqw_7cqw] items-center gap-x-[1.2cqw] border-t border-black/[.06] py-[0.95cqw] text-[1.3cqw]"
                    :class="r.low ? '-mx-[1cqw] rounded-[0.8cqw] border-transparent bg-[#fef2f2] px-[1cqw] ring-1 ring-[#fca5a5]' : ''"
                >
                    <span class="font-mono text-[1.15cqw] text-black/55">{{ r.sku }}</span>
                    <span class="truncate font-medium">{{ r.item }}</span>
                    <span class="flex items-center gap-[0.8cqw]">
                        <span class="block h-[0.8cqw] flex-1 overflow-hidden rounded-full bg-black/[.07]">
                            <span class="block h-full rounded-full" :class="r.low ? 'bg-[#ef4444]' : 'bg-[#64748b]'" :style="{ width: `${Math.max(r.stock, 4)}%` }"></span>
                        </span>
                        <span class="w-[2.6cqw] text-right text-[1.15cqw] font-semibold" :class="r.low ? 'text-[#dc2626]' : 'text-black/60'">{{ r.stock }}</span>
                    </span>
                    <span><span class="rounded-full px-[0.8cqw] py-[0.2cqw] text-[1.05cqw] font-semibold" :class="CH[r.ch]">{{ r.ch }}</span></span>
                </div>
            </div>
        </Frame>
    </div>

    <!-- low-stock alert -->
    <div class="absolute right-[4cqw] top-[1.8cqw] flex rotate-[1.5deg] items-center gap-[1cqw] rounded-[1.2cqw] bg-white px-[1.3cqw] py-[0.9cqw] text-[#14161a] shadow-[0_1.4cqw_3.5cqw_rgb(0_0_0/.45)]">
        <span class="grid h-[2.8cqw] w-[2.8cqw] place-items-center rounded-full bg-[#ef4444] text-white">
            <svg viewBox="0 0 24 24" class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 16V11a6 6 0 0 1 12 0v5l1.5 2h-15z" /><path d="M10 21h4" /></svg>
        </span>
        <span class="leading-tight">
            <span class="block text-[1.35cqw] font-bold">Low stock · Wireless mouse</span>
            <span class="block text-[1.1cqw] text-black/50">3 left · alert emailed to purchasing</span>
        </span>
    </div>

    <!-- the vendor sheet, parsed -->
    <div class="absolute bottom-[8.5cqw] left-[12cqw] w-[38cqw] -rotate-[2deg] overflow-hidden rounded-[1.2cqw] bg-white text-[#14161a] shadow-[0_1.8cqw_4.5cqw_rgb(0_0_0/.5)]">
        <div class="flex items-center gap-[0.8cqw] bg-[#1d6f42] px-[1.3cqw] py-[0.7cqw] text-[1.15cqw] font-semibold text-white">
            <span class="grid h-[1.7cqw] w-[1.7cqw] place-items-center rounded-[0.3cqw] bg-white text-[1.05cqw] font-bold text-[#1d6f42]">X</span>
            vendor_prices.xlsx
            <span class="ml-auto rounded-full bg-white/20 px-[0.8cqw] py-[0.15cqw] text-[1cqw] font-medium">header row 3 · UPC B · price D</span>
        </div>
        <table class="w-full table-fixed border-collapse text-[1.05cqw]">
            <colgroup><col class="w-[2.4cqw]" /><col class="w-[5.4cqw]" /><col class="w-[9.6cqw]" /><col /><col class="w-[4.6cqw]" /><col class="w-[3.6cqw]" /></colgroup>
            <tr class="bg-[#f3f4f2] text-center text-black/40">
                <td class="border border-black/[.08]"></td>
                <td v-for="(c, j) in COLS" :key="c" class="border border-black/[.08] py-[0.15cqw]" :class="PICK[j] ? 'font-bold text-[#1d6f42]' : ''">{{ c }}</td>
            </tr>
            <tr v-for="(row, i) in SHEET" :key="i" :class="i === HEADER ? 'outline outline-[0.25cqw] -outline-offset-[0.12cqw] outline-[#16a34a]' : ''">
                <td class="border border-black/[.08] bg-[#f3f4f2] text-center text-black/40">{{ i + 1 }}</td>
                <td v-if="i < HEADER" colspan="5" class="border border-black/[.08] px-[0.5cqw] py-[0.3cqw] italic text-black/35">{{ row[0] }}</td>
                <template v-else>
                    <td
                        v-for="(cell, j) in row"
                        :key="j"
                        class="truncate whitespace-nowrap border border-black/[.08] px-[0.5cqw] py-[0.3cqw]"
                        :class="[
                            i === HEADER ? 'bg-[#dcfce7] font-bold' : 'text-black/70',
                            i > HEADER && PICK[j] === 'upc' ? 'bg-[#e0f2fe] font-mono text-[#075985]' : '',
                            i > HEADER && PICK[j] === 'price' ? 'bg-[#fef9c3] font-semibold text-[#854d0e]' : '',
                        ]"
                    >{{ cell }}</td>
                </template>
            </tr>
        </table>
    </div>
</template>

<script setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Check, ChevronDown, Search } from 'lucide-vue-next';
import CategoryIcon from './CategoryIcon.vue';

const model = defineModel({ type: [Number, String, null], default: null });
const props = defineProps({
    options: { type: Array, default: null }, // defaults to shared expense categories
    placeholder: { type: String, default: 'Select a category' },
    emptyLabel: { type: String, default: '' }, // when set, an "empty" option is offered (e.g. "All categories")
    error: { type: Boolean, default: false },
    parentsOnly: { type: Boolean, default: false },
    exclude: { type: [Number, null], default: null },
});

const page = usePage();
const all = computed(() => {
    let list = props.options ?? page.props.lookups?.expenseCategories ?? [];
    if (props.parentsOnly) list = list.filter((o) => !o.parent_id);
    if (props.exclude) list = list.filter((o) => o.id !== props.exclude);
    return list;
});
const open = ref(false);
const q = ref('');
const active = ref(0);
const root = ref(null);
const searchEl = ref(null);
const listEl = ref(null);

const selected = computed(() => all.value.find((o) => String(o.id) === String(model.value)));
const filtered = computed(() => {
    const s = q.value.trim().toLowerCase();
    const list = s ? all.value.filter((o) => o.label.toLowerCase().includes(s)) : all.value;
    return props.emptyLabel && !s ? [{ id: '', label: props.emptyLabel, name: props.emptyLabel, empty: true }, ...list] : list;
});

async function toggle() {
    open.value = !open.value;
    if (open.value) {
        q.value = '';
        active.value = Math.max(0, filtered.value.findIndex((o) => String(o.id) === String(model.value ?? '')));
        await nextTick();
        searchEl.value?.focus();
        scrollActive();
    }
}
function pick(o) {
    model.value = o.empty ? '' : o.id;
    open.value = false;
}
function scrollActive() {
    nextTick(() => listEl.value?.querySelector(`[data-i="${active.value}"]`)?.scrollIntoView({ block: 'nearest' }));
}
function onKey(e) {
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        active.value = Math.min(filtered.value.length - 1, active.value + 1);
        scrollActive();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        active.value = Math.max(0, active.value - 1);
        scrollActive();
    } else if (e.key === 'Enter') {
        e.preventDefault();
        filtered.value[active.value] && pick(filtered.value[active.value]);
    } else if (e.key === 'Escape') {
        e.stopPropagation();
        open.value = false;
    }
}
const onDoc = (e) => root.value && !root.value.contains(e.target) && (open.value = false);
onMounted(() => document.addEventListener('mousedown', onDoc));
onBeforeUnmount(() => document.removeEventListener('mousedown', onDoc));
</script>

<template>
    <div ref="root" class="relative">
        <button type="button" class="input flex items-center gap-2 text-left" :class="{ 'input-error': error }" @click="toggle" @keydown.down.prevent="!open && toggle()">
            <template v-if="selected">
                <CategoryIcon :icon="selected.icon" :color="selected.color" size="sm" class="-ml-1" />
                <span class="truncate">{{ selected.label }}</span>
            </template>
            <span v-else class="truncate" :class="emptyLabel ? 'text-zinc-700 dark:text-zinc-200' : 'text-zinc-400'">{{ emptyLabel || placeholder }}</span>
            <ChevronDown class="ml-auto h-4 w-4 shrink-0 text-zinc-400" />
        </button>
        <div v-if="open" class="menu absolute left-0 right-0 mt-1.5 min-w-[16rem] p-0">
            <div class="relative border-b border-zinc-100 dark:border-zinc-800">
                <Search class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                <input
                    ref="searchEl"
                    v-model="q"
                    type="text"
                    placeholder="Search categories…"
                    class="h-10 w-full bg-transparent pl-9 pr-3 text-sm outline-none placeholder:text-zinc-400"
                    @input="active = 0"
                    @keydown="onKey"
                />
            </div>
            <ul ref="listEl" class="max-h-64 overflow-y-auto p-1">
                <li v-if="!filtered.length" class="px-3 py-6 text-center text-sm text-zinc-500">No categories match “{{ q }}”.</li>
                <li v-for="(o, i) in filtered" :key="o.id + '-' + i" :data-i="i">
                    <button
                        type="button"
                        class="flex w-full items-center gap-2.5 rounded-lg px-2.5 py-1.5 text-left text-sm"
                        :class="[i === active ? 'bg-zinc-100 dark:bg-zinc-800' : '', o.parent_id && !q ? 'pl-7' : '']"
                        @mouseenter="active = i"
                        @click="pick(o)"
                    >
                        <CategoryIcon v-if="!o.empty" :icon="o.icon" :color="o.color" size="sm" />
                        <span class="flex-1 truncate" :class="!o.parent_id && !o.empty ? 'font-medium text-zinc-900 dark:text-zinc-100' : 'text-zinc-700 dark:text-zinc-300'">
                            {{ q ? o.label : o.name }}
                        </span>
                        <Check v-if="String(o.id) === String(model ?? '')" class="h-4 w-4 text-brand-600 dark:text-brand-400" />
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ChevronDown, Minus, MoreHorizontal, Pencil, PiggyBank, Plus, Trash2, Trophy } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import IconColorPicker from '../../Components/IconColorPicker.vue';
import Dropdown from '../../Components/Dropdown.vue';
import EmptyState from '../../Components/EmptyState.vue';
import { confirm } from '../../composables/useConfirm';
import { swatch } from '../../lib/colors';
import { date, money, today } from '../../lib/format';

const props = defineProps({
    goals: Array,
    summary: Object,
});

const active = computed(() => props.goals.filter((g) => !g.achieved_at));
const achieved = computed(() => props.goals.filter((g) => g.achieved_at));
const expanded = ref({});

// Progress ring geometry
const R = 30;
const C = 2 * Math.PI * R;
const dash = (pct) => `${(Math.min(100, pct) / 100) * C} ${C}`;

// Create / edit
const showForm = ref(false);
const editing = ref(null);
const form = useForm({ name: '', target_amount: '', target_date: '', icon: 'target', color: 'indigo', notes: '' });

function openCreate() {
    editing.value = null;
    form.reset();
    form.clearErrors();
    showForm.value = true;
}
function openEdit(g) {
    editing.value = g;
    form.clearErrors();
    Object.assign(form, { name: g.name, target_amount: g.target, target_date: g.target_date ?? '', icon: g.icon || 'target', color: g.color || 'indigo', notes: g.notes ?? '' });
    showForm.value = true;
}
function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editing.value) form.put(`/admin/goals/${editing.value.id}`, opts);
    else form.post('/admin/goals', opts);
}
async function remove(g) {
    if (await confirm({ title: `Delete “${g.name}”?`, message: 'The goal and its history are removed. Your balance is not affected.' })) {
        router.delete(`/admin/goals/${g.id}`, { preserveScroll: true });
    }
}

// Add / withdraw
const moving = ref(null);
const direction = ref('add');
const moveForm = useForm({ date: today(), amount: '', note: '' });
function openMove(g, dir = 'add') {
    moving.value = g;
    direction.value = dir;
    moveForm.clearErrors();
    moveForm.date = today();
    moveForm.amount = dir === 'add' && g.per_month ? Math.ceil(g.per_month) : '';
    moveForm.note = '';
}
function submitMove() {
    moveForm
        .transform((d) => ({ ...d, amount: direction.value === 'withdraw' ? -Math.abs(Number(d.amount)) : Math.abs(Number(d.amount)) }))
        .post(`/admin/goals/${moving.value.id}/contributions`, { preserveScroll: true, onSuccess: () => (moving.value = null) });
}
async function removeEntry(c) {
    if (await confirm({ title: 'Remove this entry?', message: `${money(c.amount, { signed: true })} on ${date(c.date)}.`, confirmText: 'Remove' })) {
        router.delete(`/admin/goals/contributions/${c.id}`, { preserveScroll: true });
    }
}
</script>

<template>
    <Head title="Savings Goals" />
    <PageHeader title="Savings Goals" description="Set money aside for the things that matter. Goals earmark part of your balance — they don't change it.">
        <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> New goal</button>
    </PageHeader>

    <template v-if="goals.length">
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Saved across goals</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(summary.saved, { decimals: 0 }) }}</p>
                <p class="mt-1.5 text-xs text-zinc-500">{{ summary.active }} active · {{ summary.achieved }} achieved</p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Still to save</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(Math.max(0, summary.target - active.reduce((s, g) => s + g.saved, 0)), { decimals: 0 }) }}</p>
                <p class="num mt-1.5 text-xs text-zinc-500">of {{ money(summary.target, { decimals: 0 }) }} in active goals</p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Current balance</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(summary.balance, { decimals: 0 }) }}</p>
                <div class="mt-3 flex h-2 overflow-hidden rounded-full bg-zinc-100 dark:bg-zinc-800" title="Allocated to goals vs free">
                    <div class="h-full bg-brand-500" :style="{ width: (summary.balance > 0 ? Math.min(100, (summary.saved / summary.balance) * 100) : 0) + '%' }" />
                </div>
                <p class="mt-1.5 text-xs text-zinc-500">
                    <span class="mr-1 inline-block h-2 w-2 rounded-sm bg-brand-500" />{{ summary.balance > 0 ? Math.round((summary.saved / summary.balance) * 100) : 0 }}% set aside for goals
                </p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Free to spend</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight" :class="summary.free < 0 ? 'text-rose-600 dark:text-rose-400' : ''">{{ money(summary.free, { decimals: 0 }) }}</p>
                <p class="mt-1.5 text-xs" :class="summary.free < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-500'">
                    {{ summary.free < 0 ? 'Goals hold more than your balance' : 'Balance not set aside for goals' }}
                </p>
            </div>
        </div>

        <div v-if="active.length" class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            <div v-for="g in active" :key="g.id" class="card flex flex-col">
                <div class="flex items-start gap-4 p-5">
                    <div class="relative h-[76px] w-[76px] shrink-0">
                        <svg viewBox="0 0 76 76" class="h-full w-full -rotate-90">
                            <circle cx="38" cy="38" :r="R" fill="none" stroke-width="7" class="stroke-zinc-100 dark:stroke-zinc-800" />
                            <circle cx="38" cy="38" :r="R" fill="none" stroke-width="7" stroke-linecap="round" :stroke="swatch(g.color || 'indigo')" :stroke-dasharray="dash(g.percent)" class="transition-all duration-700" />
                        </svg>
                        <span class="num absolute inset-0 flex items-center justify-center text-sm font-semibold">{{ Math.floor(g.percent) }}%</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex min-w-0 items-center gap-2">
                                <CategoryIcon :icon="g.icon || 'target'" :color="g.color || 'indigo'" size="sm" />
                                <h3 class="truncate font-semibold text-zinc-900 dark:text-white">{{ g.name }}</h3>
                            </div>
                            <Dropdown>
                                <template #trigger="{ toggle }">
                                    <button class="btn btn-ghost btn-icon btn-xs -mr-1.5 -mt-1" aria-label="More actions" @click="toggle"><MoreHorizontal class="h-4 w-4" /></button>
                                </template>
                                <button class="menu-item" @click="openEdit(g)"><Pencil class="h-4 w-4" /> Edit</button>
                                <button class="menu-item" :disabled="g.saved <= 0" @click="openMove(g, 'withdraw')"><Minus class="h-4 w-4" /> Withdraw</button>
                                <button class="menu-item text-rose-600 dark:text-rose-400" @click="remove(g)"><Trash2 class="h-4 w-4" /> Delete</button>
                            </Dropdown>
                        </div>
                        <p class="num mt-2 text-lg font-semibold tracking-tight">
                            {{ money(g.saved, { decimals: 0 }) }} <span class="text-sm font-normal text-zinc-400">of {{ money(g.target, { decimals: 0 }) }}</span>
                        </p>
                        <p class="num text-xs text-zinc-500">{{ money(g.remaining, { decimals: 0 }) }} to go</p>
                    </div>
                </div>

                <div class="mx-5 mb-4 rounded-lg bg-zinc-50 px-3 py-2.5 text-xs dark:bg-zinc-800/50">
                    <template v-if="g.target_date">
                        <p class="flex justify-between gap-2">
                            <span class="text-zinc-500">Target date</span>
                            <span class="font-medium" :class="g.overdue ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-800 dark:text-zinc-200'">{{ date(g.target_date) }}{{ g.overdue ? ' · passed' : '' }}</span>
                        </p>
                        <p v-if="g.per_month" class="mt-1 flex justify-between gap-2">
                            <span class="text-zinc-500">Needed per month</span>
                            <span class="num font-medium text-zinc-800 dark:text-zinc-200">{{ money(g.per_month, { decimals: 0 }) }} × {{ g.months_left }}</span>
                        </p>
                    </template>
                    <p v-else class="text-zinc-500">No target date — save at your own pace.</p>
                </div>

                <div class="mt-auto border-t border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2 px-5 py-3">
                        <button class="btn btn-primary btn-sm" @click="openMove(g, 'add')"><Plus class="h-3.5 w-3.5" /> Add money</button>
                        <button v-if="g.contributions.length" class="btn btn-ghost btn-sm ml-auto" @click="expanded[g.id] = !expanded[g.id]">
                            History ({{ g.contributions.length }}) <ChevronDown class="h-3.5 w-3.5 transition" :class="{ 'rotate-180': expanded[g.id] }" />
                        </button>
                    </div>
                    <ul v-if="expanded[g.id]" class="max-h-60 divide-y divide-zinc-100 overflow-y-auto border-t border-zinc-100 dark:divide-zinc-800 dark:border-zinc-800">
                        <li v-for="c in g.contributions" :key="c.id" class="group flex items-center gap-3 px-5 py-2 text-sm">
                            <span class="num w-24 shrink-0 text-xs text-zinc-500">{{ date(c.date) }}</span>
                            <span class="min-w-0 flex-1 truncate text-zinc-600 dark:text-zinc-400">{{ c.note || (c.amount > 0 ? 'Added' : 'Withdrawn') }}</span>
                            <span class="num font-medium" :class="c.amount > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">{{ money(c.amount, { signed: true }) }}</span>
                            <button class="btn btn-ghost btn-icon btn-xs opacity-0 group-hover:opacity-100 hover:!text-rose-600" aria-label="Remove entry" @click="removeEntry(c)"><Trash2 class="h-3.5 w-3.5" /></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div v-else class="card">
            <EmptyState :icon="Trophy" title="Every goal achieved" description="Start a new one whenever you're ready.">
                <button class="btn btn-primary btn-sm" @click="openCreate"><Plus class="h-4 w-4" /> New goal</button>
            </EmptyState>
        </div>

        <div v-if="achieved.length" class="card mt-6">
            <div class="card-header">
                <div class="flex items-center gap-2">
                    <Trophy class="h-4 w-4 text-amber-500" />
                    <h2 class="card-title">Achieved</h2>
                </div>
                <span class="badge badge-green num">{{ achieved.length }}</span>
            </div>
            <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <li v-for="g in achieved" :key="g.id" class="flex items-center gap-3 px-5 py-3">
                    <CategoryIcon :icon="g.icon || 'target'" :color="g.color || 'indigo'" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ g.name }}</p>
                        <p class="text-xs text-zinc-500">Reached {{ date(g.achieved_at) }}</p>
                    </div>
                    <span class="num text-sm font-semibold">{{ money(g.saved, { decimals: 0 }) }}</span>
                    <Dropdown>
                        <template #trigger="{ toggle }">
                            <button class="btn btn-ghost btn-icon btn-xs" aria-label="More actions" @click="toggle"><MoreHorizontal class="h-4 w-4" /></button>
                        </template>
                        <button class="menu-item" @click="openEdit(g)"><Pencil class="h-4 w-4" /> Edit</button>
                        <button class="menu-item" @click="openMove(g, 'withdraw')"><Minus class="h-4 w-4" /> Withdraw (spend it)</button>
                        <button class="menu-item text-rose-600 dark:text-rose-400" @click="remove(g)"><Trash2 class="h-4 w-4" /> Delete</button>
                    </Dropdown>
                </li>
            </ul>
        </div>
    </template>

    <div v-else class="card">
        <EmptyState
            :icon="PiggyBank"
            title="Start saving towards something"
            description="Create a goal — an emergency fund, Hajj, a laptop, a wedding — and set money aside as you go. You'll see progress, how much to save each month, and how much of your balance is free to spend."
        >
            <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> Create your first goal</button>
        </EmptyState>
    </div>

    <!-- Create / edit -->
    <Modal :show="showForm" :title="editing ? 'Edit goal' : 'New savings goal'" size="lg" @close="showForm = false">
        <form id="goal-form" class="grid gap-5 sm:grid-cols-2" @submit.prevent="submit">
            <div class="space-y-4">
                <Field label="Name" :error="form.errors.name" required for="goal-name">
                    <input id="goal-name" v-model="form.name" type="text" maxlength="255" class="input" :class="{ 'input-error': form.errors.name }" placeholder="e.g. Emergency fund" />
                </Field>
                <Field label="Target amount" :error="form.errors.target_amount" required for="goal-target">
                    <MoneyInput id="goal-target" v-model="form.target_amount" :error="!!form.errors.target_amount" />
                </Field>
                <Field label="Target date" :error="form.errors.target_date" hint="Optional. Used to work out how much to save each month." for="goal-date">
                    <input id="goal-date" v-model="form.target_date" type="date" class="input" />
                </Field>
                <Field label="Notes" :error="form.errors.notes" for="goal-notes">
                    <textarea id="goal-notes" v-model="form.notes" rows="2" class="input" />
                </Field>
            </div>
            <IconColorPicker v-model:icon="form.icon" v-model:color="form.color" />
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="goal-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Create goal' }}</button>
        </template>
    </Modal>

    <!-- Add / withdraw -->
    <Modal :show="!!moving" :title="moving ? moving.name : ''" size="sm" @close="moving = null">
        <form v-if="moving" id="move-form" class="space-y-4" @submit.prevent="submitMove">
            <div class="seg w-full">
                <button type="button" class="flex-1" :class="{ on: direction === 'add' }" @click="direction = 'add'">Add money</button>
                <button type="button" class="flex-1" :class="{ on: direction === 'withdraw' }" :disabled="moving.saved <= 0" @click="direction = 'withdraw'">Withdraw</button>
            </div>
            <Field label="Amount" :error="moveForm.errors.amount" :hint="direction === 'withdraw' ? `Up to ${money(moving.saved)} available` : moving.per_month ? `Suggested: ${money(moving.per_month, { decimals: 0 })} a month` : ''" required for="move-amount">
                <MoneyInput id="move-amount" v-model="moveForm.amount" :error="!!moveForm.errors.amount" />
            </Field>
            <Field label="Date" :error="moveForm.errors.date" required for="move-date">
                <input id="move-date" v-model="moveForm.date" type="date" class="input" />
            </Field>
            <Field label="Note" :error="moveForm.errors.note" for="move-note">
                <input id="move-note" v-model="moveForm.note" type="text" maxlength="255" class="input" placeholder="Optional" />
            </Field>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="moving = null">Cancel</button>
            <button type="submit" form="move-form" class="btn" :class="direction === 'withdraw' ? 'btn-danger' : 'btn-primary'" :disabled="moveForm.processing">
                {{ direction === 'withdraw' ? 'Withdraw' : 'Add to goal' }}
            </button>
        </template>
    </Modal>
</template>

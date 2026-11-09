<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { CalendarClock, CheckCircle2, MoreHorizontal, Pause, Pencil, Play, Plus, SkipForward, Trash2 } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import CategorySelect from '../../Components/CategorySelect.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import Toggle from '../../Components/Toggle.vue';
import Dropdown from '../../Components/Dropdown.vue';
import EmptyState from '../../Components/EmptyState.vue';
import { confirm } from '../../composables/useConfirm';
import { date, money, relativeDue, today } from '../../lib/format';

const props = defineProps({
    bills: Array,
    summary: Object,
    frequencies: Array,
});

const groups = computed(() =>
    [
        { key: 'overdue', title: 'Overdue', sub: 'Past their due date', tone: 'text-rose-600 dark:text-rose-400' },
        { key: 'week', title: 'Due in the next 7 days', sub: 'Coming up soon', tone: 'text-amber-700 dark:text-amber-400' },
        { key: 'upcoming', title: 'Upcoming', sub: 'Later', tone: 'text-zinc-500' },
        { key: 'paused', title: 'Paused', sub: 'Not counted in commitments', tone: 'text-zinc-400' },
    ]
        .map((g) => ({ ...g, rows: props.bills.filter((b) => b.group === g.key) }))
        .filter((g) => g.rows.length),
);

const freqLabel = { weekly: 'Weekly', monthly: 'Monthly', quarterly: 'Quarterly', yearly: 'Yearly' };
const freqBadge = { weekly: 'badge-violet', monthly: 'badge-blue', quarterly: 'badge-amber', yearly: 'badge-green' };

// Create / edit
const showForm = ref(false);
const editing = ref(null);
const form = useForm({ name: '', amount: '', expense_category_id: null, frequency: 'monthly', next_due_date: today(), is_active: true, notes: '' });

function openCreate() {
    editing.value = null;
    form.reset();
    form.clearErrors();
    form.next_due_date = today();
    showForm.value = true;
}
function openEdit(b) {
    editing.value = b;
    form.clearErrors();
    Object.assign(form, {
        name: b.name,
        amount: b.amount,
        expense_category_id: b.expense_category_id,
        frequency: b.frequency,
        next_due_date: b.next_due_date,
        is_active: b.is_active,
        notes: b.notes ?? '',
    });
    showForm.value = true;
}
function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editing.value) form.put(`/admin/bills/${editing.value.id}`, opts);
    else form.post('/admin/bills', opts);
}

// Pay
const paying = ref(null);
const payForm = useForm({ date: today(), amount: '', note: '' });
function openPay(b) {
    paying.value = b;
    payForm.clearErrors();
    payForm.date = today();
    payForm.amount = b.amount;
    payForm.note = '';
}
function submitPay() {
    payForm.post(`/admin/bills/${paying.value.id}/pay`, { preserveScroll: true, onSuccess: () => (paying.value = null) });
}

async function skip(b) {
    if (await confirm({ title: `Skip this ${b.frequency} payment?`, message: `${b.name} moves to its next due date without recording an expense.`, confirmText: 'Skip', tone: 'brand' })) {
        router.post(`/admin/bills/${b.id}/skip`, {}, { preserveScroll: true });
    }
}
function toggleActive(b) {
    router.put(
        `/admin/bills/${b.id}`,
        { name: b.name, amount: b.amount, expense_category_id: b.expense_category_id, frequency: b.frequency, next_due_date: b.next_due_date, notes: b.notes, is_active: !b.is_active },
        { preserveScroll: true },
    );
}
async function remove(b) {
    if (await confirm({ title: `Delete ${b.name}?`, message: b.payments_count ? `Its ${b.payments_count} past payment(s) stay in your expenses.` : 'This cannot be undone.' })) {
        router.delete(`/admin/bills/${b.id}`, { preserveScroll: true });
    }
}

const nextAfter = computed(() => {
    if (!paying.value) return '';
    const [y, m, d] = paying.value.next_due_date.split('-').map(Number);
    const add = { weekly: [0, 0, 7], monthly: [0, 1, 0], quarterly: [0, 3, 0], yearly: [1, 0, 0] }[paying.value.frequency];
    if (add[2]) return date(new Date(y, m - 1, d + add[2]));
    const target = new Date(y + add[0], m - 1 + add[1], 1);
    const last = new Date(target.getFullYear(), target.getMonth() + 1, 0).getDate();
    return date(new Date(target.getFullYear(), target.getMonth(), Math.min(d, last)));
});
</script>

<template>
    <Head title="Bills & Subscriptions" />
    <PageHeader title="Bills & Subscriptions" description="Rent, utilities, internet, subscriptions — anything you pay on a schedule.">
        <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> Add bill</button>
    </PageHeader>

    <template v-if="bills.length">
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Monthly commitment</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(summary.monthly, { decimals: 0 }) }}</p>
                <p class="mt-1.5 text-xs text-zinc-500">{{ summary.active }} active bill{{ summary.active === 1 ? '' : 's' }}, averaged per month</p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Yearly commitment</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(summary.yearly, { decimals: 0 }) }}</p>
                <p class="mt-1.5 text-xs text-zinc-500">What your bills cost over 12 months</p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Due this week</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ summary.due_week }}</p>
                <p class="num mt-1.5 text-xs text-zinc-500">{{ money(summary.due_week_amount, { decimals: 0 }) }} including overdue</p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Overdue</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight" :class="summary.overdue ? 'text-rose-600 dark:text-rose-400' : ''">{{ summary.overdue }}</p>
                <p class="mt-1.5 text-xs text-zinc-500">{{ summary.overdue ? 'Pay or skip to clear them' : 'Nothing overdue' }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div v-for="g in groups" :key="g.key" class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title" :class="g.key === 'overdue' ? 'text-rose-700 dark:text-rose-400' : ''">{{ g.title }}</h2>
                        <p class="card-sub">{{ g.sub }}</p>
                    </div>
                    <span class="badge badge-gray num">{{ g.rows.length }}</span>
                </div>
                <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <li v-for="b in g.rows" :key="b.id" class="flex flex-col gap-3 px-5 py-3.5 sm:flex-row sm:items-center" :class="{ 'opacity-70': !b.is_active }">
                        <div class="flex min-w-0 flex-1 items-center gap-3">
                            <CategoryIcon :icon="b.category?.icon || 'receipt'" :color="b.category?.color || 'blue'" />
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ b.name }}</p>
                                    <span class="badge" :class="freqBadge[b.frequency]">{{ freqLabel[b.frequency] }}</span>
                                </div>
                                <p class="truncate text-xs text-zinc-500">
                                    {{ b.category ? (b.category.parent ? `${b.category.parent} › ${b.category.name}` : b.category.name) : 'No category' }}
                                    · <Link v-if="b.payments_count" :href="`/admin/expenses?year=all&bill=${b.id}`" class="hover:underline">{{ b.payments_count }} payment{{ b.payments_count === 1 ? '' : 's' }}</Link><span v-else>No payments yet</span>
                                    <template v-if="b.last_paid_at"> · last paid {{ date(b.last_paid_at) }}</template>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 sm:justify-end">
                            <div class="text-left sm:text-right">
                                <p class="num text-sm font-semibold text-zinc-900 dark:text-white">{{ money(b.amount) }}</p>
                                <p class="text-xs" :class="g.tone">{{ b.is_active ? relativeDue(b.next_due_date) : 'Paused' }} · {{ date(b.next_due_date) }}</p>
                            </div>
                            <div class="ml-auto flex items-center gap-1 sm:ml-0">
                                <button v-if="b.is_active" class="btn btn-secondary btn-sm" @click="openPay(b)"><CheckCircle2 class="h-3.5 w-3.5 text-emerald-600" /> Pay</button>
                                <Dropdown>
                                    <template #trigger="{ toggle }">
                                        <button class="btn btn-ghost btn-icon btn-sm" aria-label="More actions" @click="toggle"><MoreHorizontal class="h-4 w-4" /></button>
                                    </template>
                                    <button class="menu-item" @click="openEdit(b)"><Pencil class="h-4 w-4" /> Edit</button>
                                    <button v-if="b.is_active" class="menu-item" @click="skip(b)"><SkipForward class="h-4 w-4" /> Skip this period</button>
                                    <button class="menu-item" @click="toggleActive(b)">
                                        <component :is="b.is_active ? Pause : Play" class="h-4 w-4" /> {{ b.is_active ? 'Pause' : 'Resume' }}
                                    </button>
                                    <button class="menu-item text-rose-600 dark:text-rose-400" @click="remove(b)"><Trash2 class="h-4 w-4" /> Delete</button>
                                </Dropdown>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </template>

    <div v-else class="card">
        <EmptyState
            :icon="CalendarClock"
            title="Never miss a bill again"
            description="Add rent, electricity, internet, phone plans or subscriptions. You'll see what's due, how much your bills cost each month, and paying one records the expense for you."
        >
            <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> Add your first bill</button>
        </EmptyState>
    </div>

    <!-- Create / edit -->
    <Modal :show="showForm" :title="editing ? 'Edit bill' : 'Add bill'" @close="showForm = false">
        <form id="bill-form" class="space-y-4" @submit.prevent="submit">
            <Field label="Name" :error="form.errors.name" required for="bill-name">
                <input id="bill-name" v-model="form.name" type="text" maxlength="255" class="input" :class="{ 'input-error': form.errors.name }" placeholder="e.g. House rent, Internet" />
            </Field>
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Amount" :error="form.errors.amount" required for="bill-amount">
                    <MoneyInput id="bill-amount" v-model="form.amount" :error="!!form.errors.amount" />
                </Field>
                <Field label="Repeats" :error="form.errors.frequency" required for="bill-frequency">
                    <select id="bill-frequency" v-model="form.frequency" class="input">
                        <option v-for="f in frequencies" :key="f" :value="f">{{ freqLabel[f] }}</option>
                    </select>
                </Field>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Category" :error="form.errors.expense_category_id" hint="Payments are recorded in this category." required>
                    <CategorySelect v-model="form.expense_category_id" :error="!!form.errors.expense_category_id" />
                </Field>
                <Field label="Next due date" :error="form.errors.next_due_date" required for="bill-due">
                    <input id="bill-due" v-model="form.next_due_date" type="date" class="input" :class="{ 'input-error': form.errors.next_due_date }" />
                </Field>
            </div>
            <Field label="Notes" :error="form.errors.notes" for="bill-notes">
                <textarea id="bill-notes" v-model="form.notes" rows="2" class="input" placeholder="Account number, provider, etc." />
            </Field>
            <Toggle v-model="form.is_active" label="Active" description="Paused bills don't show as due and aren't counted." />
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="bill-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Add bill' }}</button>
        </template>
    </Modal>

    <!-- Pay -->
    <Modal :show="!!paying" :title="paying ? `Pay ${paying.name}` : ''" description="Records an expense and moves the bill to its next due date." size="sm" @close="paying = null">
        <form v-if="paying" id="pay-form" class="space-y-4" @submit.prevent="submitPay">
            <Field label="Amount paid" :error="payForm.errors.amount" required for="pay-amount">
                <MoneyInput id="pay-amount" v-model="payForm.amount" :error="!!payForm.errors.amount" />
            </Field>
            <Field label="Paid on" :error="payForm.errors.date" required for="pay-date">
                <input id="pay-date" v-model="payForm.date" type="date" class="input" />
            </Field>
            <Field label="Note" :error="payForm.errors.note" for="pay-note">
                <input id="pay-note" v-model="payForm.note" type="text" maxlength="255" class="input" :placeholder="paying.name" />
            </Field>
            <p class="rounded-lg bg-zinc-50 px-3 py-2 text-xs text-zinc-600 dark:bg-zinc-800/60 dark:text-zinc-300">
                Due {{ date(paying.next_due_date) }} → next due <span class="font-medium">{{ nextAfter }}</span>
            </p>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="paying = null">Cancel</button>
            <button type="submit" form="pay-form" class="btn btn-success" :disabled="payForm.processing">Record payment</button>
        </template>
    </Modal>
</template>

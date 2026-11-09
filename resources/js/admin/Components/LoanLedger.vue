<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { AlarmClock, CheckCircle2, HandCoins, Landmark, Pencil, Plus, Trash2, Undo2, Wallet } from 'lucide-vue-next';
import PageHeader from './PageHeader.vue';
import StatCard from './StatCard.vue';
import SearchInput from './SearchInput.vue';
import LedgerPeriodFilter from './LedgerPeriodFilter.vue';
import SortTh from './SortTh.vue';
import Pagination from './Pagination.vue';
import EmptyState from './EmptyState.vue';
import BulkBar from './BulkBar.vue';
import Modal from './Modal.vue';
import Field from './Field.vue';
import MoneyInput from './MoneyInput.vue';
import Toggle from './Toggle.vue';
import ProgressBar from './ProgressBar.vue';
import { useFilters } from '../composables/useFilters';
import { useSelection } from '../composables/useSelection';
import { confirm } from '../composables/useConfirm';
import { date, initials, money, number, relativeDue, today } from '../lib/format';

/**
 * Shared ledger for "Money Lent" (kind = lending) and "Money Borrowed" (kind = borrowing).
 */
const props = defineProps({
    kind: { type: String, required: true }, // lending | borrowing
    loans: Object,
    filters: Object,
    summary: Object,
    people: Array,
});

const lending = computed(() => props.kind === 'lending');
const t = computed(() =>
    lending.value
        ? {
              title: 'Money Lent',
              description: 'Money you have lent to others and what is still owed to you.',
              base: '/admin/lending',
              nameField: 'borrower_name',
              person: 'Borrower',
              add: 'Add loan',
              noun: 'loan',
              action: 'Record return',
              actionShort: 'Return',
              actionUrl: 'return',
              doneStatus: 'Returned',
              partialStatus: 'Partially Returned',
              returnedLabel: 'Returned',
              peopleTitle: 'Who owes you',
              total: 'Total lent',
              totalValue: props.summary.lent,
              doneToggle: 'Fully returned',
              icon: HandCoins,
          }
        : {
              title: 'Money Borrowed',
              description: 'Money you owe to others, with repayments tracked.',
              base: '/admin/borrowing',
              nameField: 'lender_name',
              person: 'Lender',
              add: 'Add borrowing',
              noun: 'debt',
              action: 'Record repayment',
              actionShort: 'Repay',
              actionUrl: 'repay',
              doneStatus: 'Repaid',
              partialStatus: 'Partially Repaid',
              returnedLabel: 'Repaid',
              peopleTitle: 'Who you owe',
              total: 'Total borrowed',
              totalValue: props.summary.borrowed,
              doneToggle: 'Fully repaid',
              icon: Landmark,
          },
);

const { filters, sortBy, reset } = useFilters({
    search: props.filters.search ?? '',
    status: props.filters.status ?? '',
    year: props.filters.year ?? '',
    from: props.filters.from ?? '',
    to: props.filters.to ?? '',
    sort: props.filters.sort ?? 'date',
    dir: props.filters.dir ?? 'desc',
    per_page: props.filters.per_page ?? '',
});
const hasFilters = computed(() => filters.search || filters.status || filters.year);

const rows = computed(() => props.loans.data);
const { selected, allSelected, someSelected, toggleAll, clear } = useSelection(rows);

const names = computed(() => [...new Set([...props.people.map((p) => p.name), ...rows.value.map((r) => r[t.value.nameField])])].sort());

const statusBadge = (r) => {
    if (r.is_returned) return ['badge-green', t.value.doneStatus];
    if (r.is_overdue) return ['badge-red', 'Overdue'];
    if (r.returned_amount > 0) return ['badge-amber', t.value.partialStatus];
    return ['badge-gray', 'Active'];
};

// Create / edit
const editing = ref(null);
const showForm = ref(false);
const form = useForm({ date: today(), name: '', amount: '', due_date: '', notes: '', is_returned: false });

function openForm(r = null) {
    editing.value = r;
    form.clearErrors();
    form.defaults(
        r
            ? { date: r.date, name: r[t.value.nameField], amount: r.amount, due_date: r.due_date ?? '', notes: r.notes ?? '', is_returned: r.is_returned }
            : { date: today(), name: '', amount: '', due_date: '', notes: '', is_returned: false },
    );
    form.reset();
    showForm.value = true;
}
function submitForm() {
    const nameField = t.value.nameField;
    const editingRow = editing.value;
    form.transform((d) => {
        const out = { date: d.date, [nameField]: d.name, amount: d.amount, due_date: d.due_date || null, notes: d.notes || null };
        if (editingRow) out.is_returned = d.is_returned;
        return out;
    });
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editingRow) form.put(`${t.value.base}/${editingRow.id}`, opts);
    else form.post(t.value.base, opts);
}
const formError = (k) => form.errors[k === 'name' ? t.value.nameField : k] || form.errors[k];

// Return / repay
const paying = ref(null);
const payForm = useForm({ amount: '' });
function openPay(r) {
    paying.value = r;
    payForm.clearErrors();
    payForm.amount = r.outstanding;
}
function submitPay() {
    payForm.post(`${t.value.base}/${paying.value.id}/${t.value.actionUrl}`, {
        preserveScroll: true,
        onSuccess: () => (paying.value = null),
    });
}

async function remove(r) {
    if (await confirm({ title: `Delete this ${t.value.noun}?`, message: `${money(r.amount)} — ${r[t.value.nameField]} (${date(r.date)}) will be removed permanently.` })) {
        router.delete(`${t.value.base}/${r.id}`, { preserveScroll: true });
    }
}
async function removeSelected() {
    const n = selected.value.length;
    if (await confirm({ title: `Delete ${n} record${n > 1 ? 's' : ''}?`, message: 'This cannot be undone.' })) {
        router.post(`${t.value.base}/bulk-delete`, { ids: selected.value }, { preserveScroll: true, onSuccess: clear });
    }
}
</script>

<template>
    <Head :title="t.title" />
    <PageHeader :title="t.title" :description="t.description">
        <button class="btn btn-primary" @click="openForm()"><Plus class="h-4 w-4" /> {{ t.add }}</button>
    </PageHeader>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard label="Outstanding" :value="money(summary.outstanding, { decimals: 0 })" :sub="`${number(summary.open_count)} open`" :icon="Wallet" tone="brand" />
        <StatCard label="Overdue" :value="money(summary.overdue_amount, { decimals: 0 })" :sub="summary.overdue_count ? `${summary.overdue_count} past due date` : 'Nothing overdue'" :icon="AlarmClock" :tone="summary.overdue_count ? 'red' : 'zinc'" />
        <StatCard :label="t.total" :value="money(t.totalValue, { decimals: 0 })" sub="All time" :icon="t.icon" tone="violet" />
        <StatCard :label="`${t.returnedLabel} so far`" :value="money(summary.returned, { decimals: 0 })" sub="All time" :icon="CheckCircle2" tone="green" />
    </div>

    <div class="grid gap-6 xl:grid-cols-4">
        <div class="card min-w-0 xl:col-span-3">
            <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 lg:flex-row lg:items-center">
                <SearchInput v-model="filters.search" :placeholder="`Search ${t.person.toLowerCase()} or note…`" class="lg:w-60" />
                <select v-model="filters.status" class="input w-auto" aria-label="Status">
                    <option value="">All statuses</option>
                    <option value="outstanding">Outstanding</option>
                    <option value="overdue">Overdue</option>
                    <option value="returned">{{ t.doneStatus }}</option>
                </select>
                <LedgerPeriodFilter v-model:year="filters.year" v-model:from="filters.from" v-model:to="filters.to" />
                <button v-if="hasFilters" class="btn btn-ghost btn-sm" @click="reset({ sort: 'date', dir: 'desc' })">Clear</button>
                <span class="text-sm text-zinc-500 lg:ml-auto">{{ number(loans.total) }} records</span>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th class="w-10"><input type="checkbox" class="h-4 w-4 rounded" :checked="allSelected" :indeterminate="someSelected" aria-label="Select all" @change="toggleAll" /></th>
                            <SortTh :column="t.nameField" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">{{ t.person }}</SortTh>
                            <SortTh column="date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Date</SortTh>
                            <SortTh column="due_date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Due</SortTh>
                            <SortTh column="amount" :sort="filters.sort" :dir="filters.dir" align="right" @sort="sortBy">Amount</SortTh>
                            <th class="min-w-[9rem]">{{ t.returnedLabel }}</th>
                            <th class="text-right">Outstanding</th>
                            <th>Status</th>
                            <th class="w-36"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in rows" :key="r.id" :class="{ 'bg-brand-50/40 dark:bg-brand-500/5': selected.includes(r.id) }">
                            <td><input v-model="selected" type="checkbox" class="h-4 w-4 rounded" :value="r.id" :aria-label="`Select record ${r.id}`" /></td>
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-[11px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ initials(r[t.nameField]) }}</span>
                                    <div class="min-w-0 leading-tight">
                                        <p class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ r[t.nameField] }}</p>
                                        <p v-if="r.notes" class="max-w-[14rem] truncate text-xs text-zinc-500" :title="r.notes">{{ r.notes }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ date(r.date) }}</td>
                            <td class="whitespace-nowrap">
                                <template v-if="r.due_date">
                                    <p class="num text-zinc-600 dark:text-zinc-400">{{ date(r.due_date) }}</p>
                                    <p v-if="!r.is_returned" class="text-xs" :class="r.is_overdue ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-500'">{{ relativeDue(r.due_date) }}</p>
                                </template>
                                <span v-else class="text-zinc-400">—</span>
                            </td>
                            <td class="num whitespace-nowrap text-right font-medium text-zinc-900 dark:text-white">{{ money(r.amount) }}</td>
                            <td>
                                <ProgressBar :value="r.is_returned ? r.amount : r.returned_amount" :max="r.amount" tone="brand" height="h-1.5" />
                                <p class="num mt-1 text-xs text-zinc-500">{{ money(r.returned_amount) }}</p>
                            </td>
                            <td class="num whitespace-nowrap text-right font-medium" :class="r.outstanding > 0 ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'">{{ money(r.outstanding) }}</td>
                            <td><span class="badge" :class="statusBadge(r)[0]">{{ statusBadge(r)[1] }}</span></td>
                            <td>
                                <div class="flex items-center justify-end gap-0.5">
                                    <button v-if="!r.is_returned" class="btn btn-secondary btn-xs mr-1" @click="openPay(r)"><Undo2 class="h-3.5 w-3.5" /> {{ t.actionShort }}</button>
                                    <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openForm(r)"><Pencil class="h-3.5 w-3.5" /></button>
                                    <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(r)"><Trash2 class="h-3.5 w-3.5" /></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <EmptyState v-if="!rows.length" :title="`No ${t.noun}s found`" :description="hasFilters ? 'Try a different filter or period.' : 'Nothing recorded yet.'">
                    <button class="btn btn-primary btn-sm" @click="openForm()"><Plus class="h-4 w-4" /> {{ t.add }}</button>
                </EmptyState>
            </div>
            <Pagination :meta="loans" />
        </div>

        <div class="card h-fit">
            <div class="card-header">
                <div>
                    <h2 class="card-title">{{ t.peopleTitle }}</h2>
                    <p class="card-sub">Outstanding by person</p>
                </div>
            </div>
            <ul v-if="people.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <li v-for="p in people" :key="p.name">
                    <button class="flex w-full items-center gap-3 px-5 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-800/40" @click="filters.search = p.name">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-[11px] font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">{{ initials(p.name) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ p.name }}</span>
                            <span class="block text-xs text-zinc-500">{{ p.count }} open · since {{ date(p.since) }}</span>
                        </span>
                        <span class="num text-sm font-semibold">{{ money(p.outstanding, { decimals: 0 }) }}</span>
                    </button>
                </li>
            </ul>
            <p v-else class="px-5 py-8 text-center text-sm text-zinc-500">Nothing outstanding. 🎉</p>
        </div>
    </div>

    <BulkBar :count="selected.length" @delete="removeSelected" @clear="clear" />

    <!-- Create / edit -->
    <Modal :show="showForm" :title="editing ? `Edit ${t.noun}` : t.add" @close="showForm = false">
        <form id="loan-form" class="space-y-4" @submit.prevent="submitForm">
            <Field :label="t.person" :error="formError('name')" required for="loan-name">
                <input id="loan-name" v-model="form.name" type="text" list="loan-names" maxlength="255" class="input" :class="{ 'input-error': formError('name') }" autofocus />
                <datalist id="loan-names"><option v-for="n in names" :key="n" :value="n" /></datalist>
            </Field>
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Amount" :error="form.errors.amount" required for="loan-amount">
                    <MoneyInput id="loan-amount" v-model="form.amount" :error="!!form.errors.amount" />
                </Field>
                <Field label="Date" :error="form.errors.date" required for="loan-date">
                    <input id="loan-date" v-model="form.date" type="date" class="input" :class="{ 'input-error': form.errors.date }" />
                </Field>
            </div>
            <Field label="Due date" :error="form.errors.due_date" hint="Optional — you'll get a reminder on the dashboard." for="loan-due">
                <input id="loan-due" v-model="form.due_date" type="date" class="input" />
            </Field>
            <Field label="Notes" :error="form.errors.notes" for="loan-notes">
                <textarea id="loan-notes" v-model="form.notes" rows="2" class="input" placeholder="Optional" />
            </Field>
            <div v-if="editing" class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <Toggle v-model="form.is_returned" :label="t.doneToggle" :description="`Mark the whole ${t.noun} as settled.`" />
                <p v-if="editing.returned_amount > 0" class="hint">{{ t.returnedLabel }} so far: {{ money(editing.returned_amount) }}</p>
            </div>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="loan-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : t.add }}</button>
        </template>
    </Modal>

    <!-- Return / repay -->
    <Modal :show="!!paying" :title="t.action" :description="paying ? `${paying[t.nameField]} · ${money(paying.amount)} on ${date(paying.date)}` : ''" size="sm" @close="paying = null">
        <form v-if="paying" id="pay-form" class="space-y-4" @submit.prevent="submitPay">
            <Field label="Amount" :error="payForm.errors.amount" required :hint="`Outstanding: ${money(paying.outstanding)}`" for="pay-amount">
                <MoneyInput id="pay-amount" v-model="payForm.amount" :error="!!payForm.errors.amount" autofocus />
            </Field>
            <div class="flex gap-2">
                <button type="button" class="btn btn-secondary btn-xs" @click="payForm.amount = paying.outstanding">Full amount</button>
                <button type="button" class="btn btn-secondary btn-xs" @click="payForm.amount = Math.round((paying.outstanding / 2) * 100) / 100">Half</button>
            </div>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="paying = null">Cancel</button>
            <button type="submit" form="pay-form" class="btn btn-success" :disabled="payForm.processing">{{ t.action }}</button>
        </template>
    </Modal>
</template>

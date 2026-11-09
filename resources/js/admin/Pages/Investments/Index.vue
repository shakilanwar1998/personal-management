<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { CheckCircle2, Infinity as InfinityIcon, Pencil, PiggyBank, Plus, Sparkles, Trash2, TrendingUp, Undo2 } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import StatCard from '../../Components/StatCard.vue';
import SearchInput from '../../Components/SearchInput.vue';
import LedgerPeriodFilter from '../../Components/LedgerPeriodFilter.vue';
import SortTh from '../../Components/SortTh.vue';
import Pagination from '../../Components/Pagination.vue';
import EmptyState from '../../Components/EmptyState.vue';
import BulkBar from '../../Components/BulkBar.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import Toggle from '../../Components/Toggle.vue';
import ProgressBar from '../../Components/ProgressBar.vue';
import { useFilters } from '../../composables/useFilters';
import { useSelection } from '../../composables/useSelection';
import { confirm } from '../../composables/useConfirm';
import { date, money, number, today } from '../../lib/format';

const props = defineProps({
    investments: Object,
    filters: Object,
    summary: Object,
});

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

const rows = computed(() => props.investments.data);
const { selected, allSelected, someSelected, toggleAll, clear } = useSelection(rows);

const badges = {
    Returned: 'badge-green',
    'Partially Returned': 'badge-amber',
    Lifetime: 'badge-blue',
    Active: 'badge-gray',
};

// Create / edit
const editing = ref(null);
const showForm = ref(false);
const blank = { company_name: '', date: today(), purpose: '', amount: '', is_lifetime: false, return_date: '', notes: '', is_returned: false };
const form = useForm({ ...blank });

function openForm(r = null) {
    editing.value = r;
    form.clearErrors();
    form.defaults(
        r
            ? {
                  company_name: r.company_name ?? '',
                  date: r.date,
                  purpose: r.purpose ?? '',
                  amount: r.amount,
                  is_lifetime: r.is_lifetime,
                  return_date: r.return_date ?? '',
                  notes: r.notes ?? '',
                  is_returned: r.is_returned,
              }
            : { ...blank, date: today() },
    );
    form.reset();
    showForm.value = true;
}
function submitForm() {
    const editingRow = editing.value;
    form.transform((d) => {
        const out = { ...d, return_date: d.return_date || null, purpose: d.purpose || null, notes: d.notes || null };
        if (!editingRow) delete out.is_returned;
        return out;
    });
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editingRow) form.put(`/admin/investments/${editingRow.id}`, opts);
    else form.post('/admin/investments', opts);
}

// Record a return
const returning = ref(null);
const returnForm = useForm({ amount: '', date: today(), final: false });
function openReturn(r) {
    returning.value = r;
    returnForm.clearErrors();
    returnForm.amount = r.outstanding;
    returnForm.date = today();
    returnForm.final = false;
}
const preview = computed(() => {
    const r = returning.value;
    if (!r) return null;
    const amt = Number(returnForm.amount) || 0;
    const newReturned = r.returned_amount + amt;
    const profit = Math.max(0, newReturned - r.amount) - Math.max(0, r.returned_amount - r.amount);
    if (profit > 0) return { tone: 'green', text: `${money(profit)} will be recorded as “Investment Profit” income and the investment will be closed.` };
    if (newReturned >= r.amount) return { tone: 'green', text: 'The principal is fully recovered — the investment will be closed.' };
    if (returnForm.final) return { tone: 'red', text: `${money(r.amount - newReturned)} will be written off as an “Investment Loss” expense and the investment will be closed.` };
    return { tone: 'gray', text: `${money(r.amount - newReturned)} will still be outstanding.` };
});
function submitReturn() {
    returnForm.post(`/admin/investments/${returning.value.id}/return`, {
        preserveScroll: true,
        onSuccess: () => (returning.value = null),
    });
}

async function remove(r) {
    if (await confirm({ title: 'Delete this investment?', message: `${money(r.amount)} in ${r.company_name || 'this investment'} will be removed. Incomes or expenses already booked from its returns stay.` })) {
        router.delete(`/admin/investments/${r.id}`, { preserveScroll: true });
    }
}
async function removeSelected() {
    const n = selected.value.length;
    if (await confirm({ title: `Delete ${n} investment${n > 1 ? 's' : ''}?`, message: 'This cannot be undone.' })) {
        router.post('/admin/investments/bulk-delete', { ids: selected.value }, { preserveScroll: true, onSuccess: clear });
    }
}
</script>

<template>
    <Head title="Investments" />
    <PageHeader title="Investments" description="Money put into businesses and ventures, and what has come back.">
        <button class="btn btn-primary" @click="openForm()"><Plus class="h-4 w-4" /> Add investment</button>
    </PageHeader>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard label="Outstanding" :value="money(summary.outstanding, { decimals: 0 })" :sub="`${number(summary.active_count)} not yet returned`" :icon="TrendingUp" tone="brand" />
        <StatCard label="Total invested" :value="money(summary.invested, { decimals: 0 })" :sub="`incl. ${money(summary.lifetime, { decimals: 0 })} lifetime`" :icon="PiggyBank" tone="violet" />
        <StatCard label="Returned so far" :value="money(summary.returned, { decimals: 0 })" sub="All time" :icon="CheckCircle2" tone="green" />
        <StatCard label="Realised profit" :value="money(summary.profit, { decimals: 0 })" sub="Returns above principal" :icon="Sparkles" tone="amber" />
    </div>

    <div class="card">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 lg:flex-row lg:items-center">
            <SearchInput v-model="filters.search" placeholder="Search company, purpose or note…" class="lg:w-64" />
            <select v-model="filters.status" class="input w-auto" aria-label="Status">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="partial">Partially returned</option>
                <option value="lifetime">Lifetime</option>
                <option value="returned">Returned</option>
            </select>
            <LedgerPeriodFilter v-model:year="filters.year" v-model:from="filters.from" v-model:to="filters.to" />
            <button v-if="hasFilters" class="btn btn-ghost btn-sm" @click="reset({ sort: 'date', dir: 'desc' })">Clear</button>
            <span class="text-sm text-zinc-500 lg:ml-auto">{{ number(investments.total) }} investments</span>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="h-4 w-4 rounded" :checked="allSelected" :indeterminate="someSelected" aria-label="Select all" @change="toggleAll" /></th>
                        <SortTh column="company_name" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Company / purpose</SortTh>
                        <SortTh column="date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Date</SortTh>
                        <SortTh column="amount" :sort="filters.sort" :dir="filters.dir" align="right" @sort="sortBy">Invested</SortTh>
                        <th class="min-w-[9rem]">Returned</th>
                        <th class="text-right">Outstanding</th>
                        <th>Return date</th>
                        <th>Status</th>
                        <th class="w-36"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in rows" :key="r.id" :class="{ 'bg-brand-50/40 dark:bg-brand-500/5': selected.includes(r.id) }">
                        <td><input v-model="selected" type="checkbox" class="h-4 w-4 rounded" :value="r.id" :aria-label="`Select investment ${r.id}`" /></td>
                        <td>
                            <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ r.company_name || '—' }}</p>
                            <p v-if="r.purpose" class="max-w-[16rem] truncate text-xs text-zinc-500" :title="r.purpose">{{ r.purpose }}</p>
                        </td>
                        <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ date(r.date) }}</td>
                        <td class="num whitespace-nowrap text-right font-medium text-zinc-900 dark:text-white">{{ money(r.amount) }}</td>
                        <td>
                            <template v-if="r.is_lifetime && !r.returned_amount">
                                <span class="inline-flex items-center gap-1 text-xs text-zinc-500"><InfinityIcon class="h-3.5 w-3.5" /> Not expected back</span>
                            </template>
                            <template v-else>
                                <ProgressBar :value="r.is_returned ? Math.max(r.returned_amount, r.amount) : r.returned_amount" :max="r.amount" tone="brand" height="h-1.5" />
                                <p class="num mt-1 text-xs text-zinc-500">
                                    {{ money(r.returned_amount) }}
                                    <span v-if="r.profit > 0" class="text-emerald-600 dark:text-emerald-400">· +{{ money(r.profit) }} profit</span>
                                </p>
                            </template>
                        </td>
                        <td class="num whitespace-nowrap text-right font-medium" :class="r.outstanding > 0 ? 'text-zinc-900 dark:text-white' : 'text-zinc-400'">{{ money(r.outstanding) }}</td>
                        <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ r.return_date ? date(r.return_date) : '—' }}</td>
                        <td><span class="badge" :class="badges[r.status] || 'badge-gray'">{{ r.status }}</span></td>
                        <td>
                            <div class="flex items-center justify-end gap-0.5">
                                <button v-if="!r.is_returned && !r.is_lifetime" class="btn btn-secondary btn-xs mr-1" @click="openReturn(r)"><Undo2 class="h-3.5 w-3.5" /> Return</button>
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openForm(r)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(r)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <EmptyState v-if="!rows.length" title="No investments found" :description="hasFilters ? 'Try a different filter or period.' : 'Record money you put into a business or venture.'">
                <button class="btn btn-primary btn-sm" @click="openForm()"><Plus class="h-4 w-4" /> Add investment</button>
            </EmptyState>
        </div>
        <Pagination :meta="investments" />
    </div>

    <BulkBar :count="selected.length" @delete="removeSelected" @clear="clear" />

    <!-- Create / edit -->
    <Modal :show="showForm" :title="editing ? 'Edit investment' : 'Add investment'" @close="showForm = false">
        <form id="inv-form" class="space-y-4" @submit.prevent="submitForm">
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Company / person" :error="form.errors.company_name" required for="inv-company">
                    <input id="inv-company" v-model="form.company_name" type="text" maxlength="255" class="input" :class="{ 'input-error': form.errors.company_name }" autofocus />
                </Field>
                <Field label="Purpose" :error="form.errors.purpose" for="inv-purpose">
                    <input id="inv-purpose" v-model="form.purpose" type="text" maxlength="255" class="input" placeholder="e.g. 2nd payment" />
                </Field>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Amount" :error="form.errors.amount" required for="inv-amount">
                    <MoneyInput id="inv-amount" v-model="form.amount" :error="!!form.errors.amount" />
                </Field>
                <Field label="Date" :error="form.errors.date" required for="inv-date">
                    <input id="inv-date" v-model="form.date" type="date" class="input" :class="{ 'input-error': form.errors.date }" />
                </Field>
            </div>
            <Field label="Expected return date" :error="form.errors.return_date" for="inv-return-date">
                <input id="inv-return-date" v-model="form.return_date" type="date" class="input" />
            </Field>
            <Field label="Notes" :error="form.errors.notes" for="inv-notes">
                <textarea id="inv-notes" v-model="form.notes" rows="2" class="input" placeholder="Optional" />
            </Field>
            <div class="space-y-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <Toggle v-model="form.is_lifetime" label="Lifetime investment" description="Money not expected back (e.g. a stake). Counts against yearly savings." />
                <Toggle v-if="editing" v-model="form.is_returned" label="Returned / closed" description="Mark the investment as settled." />
            </div>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="inv-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Add investment' }}</button>
        </template>
    </Modal>

    <!-- Record return -->
    <Modal :show="!!returning" title="Record a return" :description="returning ? `${returning.company_name}${returning.purpose ? ' · ' + returning.purpose : ''}` : ''" @close="returning = null">
        <form v-if="returning" id="ret-form" class="space-y-4" @submit.prevent="submitReturn">
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Return amount" :error="returnForm.errors.amount" required for="ret-amount">
                    <MoneyInput id="ret-amount" v-model="returnForm.amount" :error="!!returnForm.errors.amount" autofocus />
                </Field>
                <Field label="Date received" :error="returnForm.errors.date" for="ret-date">
                    <input id="ret-date" v-model="returnForm.date" type="date" class="input" />
                </Field>
            </div>
            <p class="hint -mt-2">
                Invested: {{ money(returning.amount) }} · Outstanding: {{ money(returning.outstanding) }}. Enter more than the outstanding principal to book a profit.
            </p>
            <div class="rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                <Toggle
                    v-model="returnForm.final"
                    label="Final settlement"
                    description="Close this investment now. If less than the invested principal has been returned, the remainder is written off as a loss."
                />
            </div>
            <div
                v-if="preview"
                class="rounded-lg px-3 py-2.5 text-sm"
                :class="{
                    'bg-emerald-50 text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300': preview.tone === 'green',
                    'bg-rose-50 text-rose-800 dark:bg-rose-500/10 dark:text-rose-300': preview.tone === 'red',
                    'bg-zinc-50 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300': preview.tone === 'gray',
                }"
            >
                {{ preview.text }}
            </div>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="returning = null">Cancel</button>
            <button type="submit" form="ret-form" class="btn btn-success" :disabled="returnForm.processing">Record return</button>
        </template>
    </Modal>
</template>

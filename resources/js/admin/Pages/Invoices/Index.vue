<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { BadgeCheck, Copy, Download, Eye, FileText, MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import SearchInput from '../../Components/SearchInput.vue';
import PeriodFilter from '../../Components/PeriodFilter.vue';
import SortTh from '../../Components/SortTh.vue';
import Pagination from '../../Components/Pagination.vue';
import EmptyState from '../../Components/EmptyState.vue';
import BulkBar from '../../Components/BulkBar.vue';
import Dropdown from '../../Components/Dropdown.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import InvoiceStatusBadge from '../../Components/InvoiceStatusBadge.vue';
import { useFilters } from '../../composables/useFilters';
import { useSelection } from '../../composables/useSelection';
import { confirm } from '../../composables/useConfirm';
import { date, today, relativeDue } from '../../lib/format';

const props = defineProps({
    invoices: Object,
    filters: Object,
    summary: Object,
    statuses: Array,
    countries: Object,
    clients: { type: Array, default: () => [] },
});

const { filters, sortBy, reset } = useFilters({
    search: props.filters.search ?? '',
    status: props.filters.status ?? '',
    client_country: props.filters.client_country ?? '',
    client: props.filters.client ?? '',
    year: props.filters.year ?? '',
    from: props.filters.from ?? '',
    to: props.filters.to ?? '',
    sort: props.filters.sort ?? 'invoice_date',
    dir: props.filters.dir ?? 'desc',
    per_page: props.filters.per_page ?? '',
});
// Invoices list all time by default (server treats an empty year as all time). The shared
// PeriodFilter uses '' for "selected year", so translate between the two.
const page = usePage();
const selectedYear = computed(() => String(page.props.calendar?.selected ?? ''));
const period = computed({
    get: () => (filters.year === '' ? 'all' : filters.year === selectedYear.value ? '' : filters.year),
    set: (v) => (filters.year = v === 'all' ? '' : v === '' ? selectedYear.value : v),
});
const hasFilters = computed(() => filters.search || filters.status || filters.client_country || filters.client || filters.year);

const rows = computed(() => props.invoices.data);
const { selected, allSelected, someSelected, toggleAll, clear } = useSelection(rows);

const ucfirst = (s) => s.charAt(0).toUpperCase() + s.slice(1);

function fmt(amount, currency) {
    try {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: currency || 'USD' }).format(Number(amount) || 0);
    } catch {
        return `${currency} ${Number(amount || 0).toFixed(2)}`;
    }
}

const countryBadge = { US: 'badge-green', DE: 'badge-amber', GB: 'badge-blue' };

async function remove(inv) {
    if (await confirm({ title: `Delete invoice ${inv.invoice_number}?`, message: 'The invoice and its items will be removed permanently.' })) {
        router.delete(`/admin/invoices/${inv.id}`, { preserveScroll: true });
    }
}
async function removeSelected() {
    const n = selected.value.length;
    if (await confirm({ title: `Delete ${n} invoice${n > 1 ? 's' : ''}?`, message: 'This cannot be undone.' })) {
        router.post('/admin/invoices/bulk-delete', { ids: selected.value }, { preserveScroll: true, onSuccess: clear });
    }
}
function duplicate(inv) {
    router.post(`/admin/invoices/${inv.id}/duplicate`);
}

// Record payment
const paying = ref(null);
const payForm = useForm({ paid_at: today(), record_income: true, amount_bdt: '' });
function openPayment(inv) {
    paying.value = inv;
    payForm.reset();
    payForm.clearErrors();
    payForm.paid_at = today();
    payForm.record_income = !inv.income_id;
}
function submitPayment() {
    payForm.post(`/admin/invoices/${paying.value.id}/payment`, {
        preserveScroll: true,
        onSuccess: () => (paying.value = null),
    });
}
</script>

<template>
    <Head title="Invoices" />
    <PageHeader title="Invoices" description="Bill clients, download PDFs and track what has been paid.">
        <Link href="/admin/invoices/create" class="btn btn-primary"><Plus class="h-4 w-4" /> New invoice</Link>
    </PageHeader>

    <!-- Summary -->
    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card p-5">
            <p class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">Outstanding</p>
            <template v-if="summary.currencies.some((c) => c.outstanding > 0)">
                <p v-for="c in summary.currencies.filter((c) => c.outstanding > 0)" :key="c.currency" class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">
                    {{ fmt(c.outstanding, c.currency) }}
                </p>
            </template>
            <p v-else class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">—</p>
            <p class="mt-1.5 text-xs text-zinc-500">{{ summary.open }} sent / due / overdue</p>
        </div>
        <div class="card p-5">
            <p class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">Overdue</p>
            <template v-if="summary.currencies.some((c) => c.overdue > 0)">
                <p v-for="c in summary.currencies.filter((c) => c.overdue > 0)" :key="c.currency" class="num mt-1.5 text-2xl font-semibold tracking-tight text-rose-600 dark:text-rose-400">
                    {{ fmt(c.overdue, c.currency) }}
                </p>
            </template>
            <p v-else class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">—</p>
            <p class="mt-1.5 text-xs text-zinc-500">Past the due date, not yet paid</p>
        </div>
        <div class="card p-5">
            <p class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">Paid in {{ summary.year }}</p>
            <template v-if="summary.currencies.some((c) => c.paid_year > 0)">
                <p v-for="c in summary.currencies.filter((c) => c.paid_year > 0)" :key="c.currency" class="num mt-1.5 text-2xl font-semibold tracking-tight text-emerald-600 dark:text-emerald-400">
                    {{ fmt(c.paid_year, c.currency) }}
                </p>
            </template>
            <p v-else class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">—</p>
            <p class="mt-1.5 text-xs text-zinc-500">By payment date</p>
        </div>
        <div class="card p-5">
            <p class="text-[13px] font-medium text-zinc-500 dark:text-zinc-400">Drafts</p>
            <p class="num mt-1.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">{{ summary.drafts }}</p>
            <p class="mt-1.5 text-xs text-zinc-500">{{ summary.count }} invoices in total</p>
        </div>
    </div>

    <div class="card">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 lg:flex-row lg:flex-wrap lg:items-center">
            <SearchInput v-model="filters.search" placeholder="Search number or client…" class="lg:w-64" />
            <select v-model="filters.status" class="input w-auto" aria-label="Status">
                <option value="">All statuses</option>
                <option v-for="s in statuses" :key="s" :value="s">{{ ucfirst(s) }}</option>
            </select>
            <select v-if="clients.length" v-model="filters.client" class="input w-auto max-w-[12rem]" aria-label="Client">
                <option value="">All clients</option>
                <option v-for="c in clients" :key="c.id" :value="String(c.id)">{{ c.name }}</option>
            </select>
            <select v-model="filters.client_country" class="input w-auto" aria-label="Country">
                <option value="">All countries</option>
                <option v-for="(name, code) in countries" :key="code" :value="code">{{ name }}</option>
            </select>
            <PeriodFilter v-model:year="period" v-model:from="filters.from" v-model:to="filters.to" />
            <button v-if="hasFilters" class="btn btn-ghost btn-sm" @click="reset({ sort: 'invoice_date', dir: 'desc' })">Clear</button>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="h-4 w-4 rounded" :checked="allSelected" :indeterminate="someSelected" aria-label="Select all" @change="toggleAll" /></th>
                        <SortTh column="invoice_number" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Invoice</SortTh>
                        <th>Client</th>
                        <SortTh column="invoice_date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Issued</SortTh>
                        <SortTh column="due_date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Due</SortTh>
                        <th>Status</th>
                        <SortTh column="total" :sort="filters.sort" :dir="filters.dir" align="right" @sort="sortBy">Total</SortTh>
                        <th class="w-32"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="inv in rows" :key="inv.id" :class="{ 'bg-brand-50/40 dark:bg-brand-500/5': selected.includes(inv.id) }">
                        <td><input v-model="selected" type="checkbox" class="h-4 w-4 rounded" :value="inv.id" :aria-label="`Select invoice ${inv.invoice_number}`" /></td>
                        <td class="whitespace-nowrap">
                            <Link :href="`/admin/invoices/${inv.id}/edit`" class="num font-medium text-zinc-900 hover:text-brand-600 dark:text-zinc-100 dark:hover:text-brand-300">{{ inv.invoice_number }}</Link>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <span class="truncate font-medium text-zinc-800 dark:text-zinc-200">{{ inv.client_name }}</span>
                                <span class="badge" :class="countryBadge[inv.client_country] || 'badge-gray'" :title="countries[inv.client_country]">{{ inv.client_country }}</span>
                            </div>
                            <p v-if="inv.client_email" class="truncate text-xs text-zinc-500">{{ inv.client_email }}</p>
                        </td>
                        <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ date(inv.invoice_date) }}</td>
                        <td class="whitespace-nowrap">
                            <span class="num text-zinc-600 dark:text-zinc-400">{{ inv.due_date ? date(inv.due_date) : '—' }}</span>
                            <p v-if="inv.is_overdue" class="text-xs text-rose-600 dark:text-rose-400">{{ relativeDue(inv.due_date) }}</p>
                        </td>
                        <td>
                            <InvoiceStatusBadge :status="inv.status" :overdue="inv.is_overdue" />
                            <p v-if="inv.status === 'paid' && inv.paid_at" class="mt-0.5 text-xs text-zinc-500">on {{ date(inv.paid_at) }}</p>
                        </td>
                        <td class="num whitespace-nowrap text-right font-medium text-zinc-900 dark:text-white">{{ fmt(inv.total, inv.currency) }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-0.5">
                                <a :href="`/invoices/${inv.id}/view`" target="_blank" rel="noopener" class="btn btn-ghost btn-icon btn-xs" aria-label="Preview" title="Preview"><Eye class="h-3.5 w-3.5" /></a>
                                <a :href="`/invoices/${inv.id}/download`" target="_blank" rel="noopener" class="btn btn-ghost btn-icon btn-xs" aria-label="Download PDF" title="Download PDF"><Download class="h-3.5 w-3.5" /></a>
                                <Link :href="`/admin/invoices/${inv.id}/edit`" class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" title="Edit"><Pencil class="h-3.5 w-3.5" /></Link>
                                <Dropdown width="w-48">
                                    <template #trigger="{ toggle }">
                                        <button class="btn btn-ghost btn-icon btn-xs" aria-label="More actions" @click="toggle"><MoreHorizontal class="h-3.5 w-3.5" /></button>
                                    </template>
                                    <button v-if="inv.status !== 'paid'" class="menu-item" @click="openPayment(inv)"><BadgeCheck class="h-4 w-4" /> Record payment</button>
                                    <button class="menu-item" @click="duplicate(inv)"><Copy class="h-4 w-4" /> Duplicate</button>
                                    <button class="menu-item text-rose-600 dark:text-rose-400" @click="remove(inv)"><Trash2 class="h-4 w-4" /> Delete</button>
                                </Dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <EmptyState v-if="!rows.length" :icon="FileText" title="No invoices found" :description="hasFilters ? 'Try a different filter or period.' : 'Create your first invoice to bill a client.'">
                <Link href="/admin/invoices/create" class="btn btn-primary btn-sm"><Plus class="h-4 w-4" /> New invoice</Link>
            </EmptyState>
        </div>
        <Pagination :meta="invoices" />
    </div>

    <BulkBar :count="selected.length" @delete="removeSelected" @clear="clear" />

    <Modal :show="!!paying" :title="paying ? `Record payment · ${paying.invoice_number}` : ''" :description="paying ? `${paying.client_name} · ${fmt(paying.total, paying.currency)}` : ''" @close="paying = null">
        <form id="pay-form" class="space-y-4" @submit.prevent="submitPayment">
            <Field label="Payment date" :error="payForm.errors.paid_at" required for="pay-date">
                <input id="pay-date" v-model="payForm.paid_at" type="date" class="input" :class="{ 'input-error': payForm.errors.paid_at }" />
            </Field>
            <template v-if="paying && !paying.income_id">
                <label class="flex items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
                    <input v-model="payForm.record_income" type="checkbox" class="mt-0.5 h-4 w-4 rounded" />
                    <span class="text-sm">
                        <span class="block font-medium text-zinc-800 dark:text-zinc-200">Also record it as income</span>
                        <span class="block text-xs text-zinc-500">Adds an income entry under “Invoice Payment” so it counts toward your balance.</span>
                    </span>
                </label>
                <Field v-if="payForm.record_income" label="Amount received (BDT)" :error="payForm.errors.amount_bdt" hint="What actually landed in your account after conversion and fees." required for="pay-amount">
                    <MoneyInput id="pay-amount" v-model="payForm.amount_bdt" :error="!!payForm.errors.amount_bdt" />
                </Field>
            </template>
            <p v-else-if="paying" class="text-sm text-zinc-500">An income entry is already linked to this invoice.</p>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="paying = null">Cancel</button>
            <button type="submit" form="pay-form" class="btn btn-success" :disabled="payForm.processing"><BadgeCheck class="h-4 w-4" /> Mark as paid</button>
        </template>
    </Modal>
</template>

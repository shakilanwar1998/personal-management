<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Download, Pencil, Plus, Repeat, Trash2 } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import SearchInput from '../../Components/SearchInput.vue';
import CategorySelect from '../../Components/CategorySelect.vue';
import PeriodFilter from '../../Components/PeriodFilter.vue';
import SortTh from '../../Components/SortTh.vue';
import Pagination from '../../Components/Pagination.vue';
import EmptyState from '../../Components/EmptyState.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import BulkBar from '../../Components/BulkBar.vue';
import Chart from '../../Components/Chart.vue';
import BarList from '../../Components/BarList.vue';
import { useFilters } from '../../composables/useFilters';
import { useSelection } from '../../composables/useSelection';
import { confirm } from '../../composables/useConfirm';
import { openQuickAdd } from '../../composables/useQuickAdd';
import { date, money, number } from '../../lib/format';

const props = defineProps({
    expenses: Object,
    filters: Object,
    summary: Object,
    chartYear: Number,
    monthly: Object,
    pinned: Array,
    bill: Object,
});

const page = usePage();
const { filters, sortBy, reset } = useFilters({
    search: props.filters.search ?? '',
    category: props.filters.category ?? '',
    year: props.filters.year ?? '',
    from: props.filters.from ?? '',
    to: props.filters.to ?? '',
    estimated: props.filters.estimated ?? '',
    sort: props.filters.sort ?? 'date',
    dir: props.filters.dir ?? 'desc',
    per_page: props.filters.per_page ?? '',
    bill: props.filters.bill ?? '',
});
const hasFilters = computed(() => filters.search || filters.category || filters.year || filters.estimated || filters.bill);

const rows = computed(() => props.expenses.data);
const { selected, allSelected, someSelected, toggleAll, clear } = useSelection(rows);

const showCharts = true;
const yearTotal = computed(() => props.monthly.expense.reduce((s, v) => s + v, 0));
const hasLifetime = computed(() => props.monthly.lifetime.some((v) => v > 0));

const exportUrl = computed(() => {
    const q = new URLSearchParams(Object.entries(filters).filter(([, v]) => v !== '' && v !== null));
    return `/admin/expenses/export?${q}`;
});

async function remove(e) {
    if (await confirm({ title: 'Delete this expense?', message: `${money(e.amount)} on ${date(e.date)} will be removed permanently.` })) {
        router.delete(`/admin/expenses/${e.id}`, { preserveScroll: true });
    }
}
async function removeSelected() {
    const n = selected.value.length;
    if (await confirm({ title: `Delete ${n} expense${n > 1 ? 's' : ''}?`, message: 'This cannot be undone.' })) {
        router.post('/admin/expenses/bulk-delete', { ids: selected.value }, { preserveScroll: true, onSuccess: clear });
    }
}
</script>

<template>
    <Head title="Expenses" />
    <PageHeader title="Expenses" description="Everything you spend, organised by category.">
        <a :href="exportUrl" class="btn btn-secondary"><Download class="h-4 w-4" /> Export CSV</a>
        <button class="btn btn-primary" @click="openQuickAdd('expense')"><Plus class="h-4 w-4" /> Add expense</button>
    </PageHeader>

    <!-- Charts -->
    <div v-if="showCharts" class="mb-6 grid gap-6 xl:grid-cols-3">
        <div class="card xl:col-span-2">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Monthly spending · {{ chartYear }}</h2>
                    <p class="card-sub">January – December · total <span class="num">{{ money(yearTotal, { decimals: 0 }) }}</span></p>
                </div>
            </div>
            <div class="p-5">
                <Chart
                    :labels="monthly.labels"
                    :stacked="hasLifetime"
                    :series="[
                        { label: 'Expenses', data: monthly.expense, kind: 'bar', color: 'var(--series-expense)' },
                        ...(hasLifetime ? [{ label: 'Lifetime investments', data: monthly.lifetime, kind: 'bar', color: 'var(--series-savings)' }] : []),
                    ]"
                    :height="250"
                />
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Pinned categories · {{ chartYear }}</h2>
                    <p class="card-sub">Categories marked “show in stats”</p>
                </div>
            </div>
            <div class="p-5">
                <BarList v-if="pinned.length" :rows="pinned" :limit="7" @select="(r) => (filters.category = String(r.id))" />
                <p v-else class="py-10 text-center text-sm text-zinc-500">Pin categories from the Categories page.</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 lg:flex-row lg:items-center">
            <SearchInput v-model="filters.search" placeholder="Search notes or amount…" class="lg:w-64" />
            <CategorySelect v-model="filters.category" empty-label="All categories" class="lg:w-60" />
            <PeriodFilter v-model:year="filters.year" v-model:from="filters.from" v-model:to="filters.to" />
            <select v-model="filters.estimated" class="input w-auto" aria-label="Estimated entries">
                <option value="">All entries</option>
                <option value="hide">Hide estimated</option>
                <option value="only">Only estimated</option>
            </select>
            <span v-if="filters.bill && bill" class="badge badge-blue gap-1.5 py-1">
                <Repeat class="h-3 w-3" /> Payments of {{ bill.name }}
                <button type="button" aria-label="Remove bill filter" @click="filters.bill = ''">✕</button>
            </span>
            <button v-if="hasFilters" class="btn btn-ghost btn-sm" @click="reset({ sort: 'date', dir: 'desc' })">Clear</button>
            <div class="text-right text-sm lg:ml-auto">
                <span class="text-zinc-500">{{ number(summary.count) }} entries ·</span>
                <span class="num font-semibold text-zinc-900 dark:text-white"> {{ money(summary.total) }}</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="h-4 w-4 rounded" :checked="allSelected" :indeterminate="someSelected" aria-label="Select all" @change="toggleAll" /></th>
                        <SortTh column="date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Date</SortTh>
                        <th>Category</th>
                        <th>Note</th>
                        <SortTh column="amount" :sort="filters.sort" :dir="filters.dir" align="right" @sort="sortBy">Amount</SortTh>
                        <th class="w-24"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="e in rows" :key="e.id" :class="{ 'bg-brand-50/40 dark:bg-brand-500/5': selected.includes(e.id) }">
                        <td><input v-model="selected" type="checkbox" class="h-4 w-4 rounded" :value="e.id" :aria-label="`Select expense ${e.id}`" /></td>
                        <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ date(e.date) }}</td>
                        <td>
                            <div v-if="e.category" class="flex items-center gap-2.5">
                                <CategoryIcon :icon="e.category.icon" :color="e.category.color" size="sm" />
                                <div class="min-w-0 leading-tight">
                                    <p class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ e.category.name }}</p>
                                    <p v-if="e.category.parent" class="truncate text-xs text-zinc-500">{{ e.category.parent }}</p>
                                </div>
                            </div>
                            <span v-else class="badge badge-gray">No category</span>
                        </td>
                        <td class="max-w-xs">
                            <div class="flex items-center gap-2">
                                <span v-if="e.is_estimated" class="badge badge-amber" title="Added to fill a recording gap">Estimated</span>
                                <span v-if="e.recurring_bill_id" class="badge badge-blue"><Repeat class="h-3 w-3" /> Bill</span>
                                <span class="truncate" :title="e.remarks">{{ e.is_estimated ? e.remarks?.replace('[ESTIMATED] ', '') : e.remarks || '—' }}</span>
                            </div>
                        </td>
                        <td class="num whitespace-nowrap text-right font-medium text-zinc-900 dark:text-white">{{ money(e.amount) }}</td>
                        <td>
                            <div class="flex justify-end gap-0.5">
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openQuickAdd('expense', e)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(e)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <EmptyState v-if="!rows.length" title="No expenses found" :description="hasFilters ? 'Try a different filter or period.' : 'Add your first expense to get started.'">
                <button class="btn btn-primary btn-sm" @click="openQuickAdd('expense')"><Plus class="h-4 w-4" /> Add expense</button>
            </EmptyState>
        </div>
        <div class="flex items-center justify-between">
            <Pagination :meta="expenses" class="flex-1" />
        </div>
    </div>

    <BulkBar :count="selected.length" @delete="removeSelected" @clear="clear" />
</template>

<script setup>
import { computed } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Download, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import SearchInput from '../../Components/SearchInput.vue';
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
    incomes: Object,
    filters: Object,
    summary: Object,
    chartYear: Number,
    monthly: Object,
    sources: Array,
});

const page = usePage();
const sourceOptions = computed(() => page.props.lookups?.incomeCategories ?? []);

const { filters, sortBy, reset } = useFilters({
    search: props.filters.search ?? '',
    source: props.filters.source ?? '',
    year: props.filters.year ?? '',
    from: props.filters.from ?? '',
    to: props.filters.to ?? '',
    sort: props.filters.sort ?? 'date',
    dir: props.filters.dir ?? 'desc',
    per_page: props.filters.per_page ?? '',
});
const hasFilters = computed(() => filters.search || filters.source || filters.year);

const rows = computed(() => props.incomes.data);
const { selected, allSelected, someSelected, toggleAll, clear } = useSelection(rows);

const yearTotal = computed(() => props.monthly.income.reduce((s, v) => s + v, 0));
const monthsWithIncome = computed(() => props.monthly.income.filter((v) => v > 0).length);

const exportUrl = computed(() => {
    const q = new URLSearchParams(Object.entries(filters).filter(([, v]) => v !== '' && v !== null));
    return `/admin/incomes/export?${q}`;
});

async function remove(i) {
    if (await confirm({ title: 'Delete this income?', message: `${money(i.amount)} on ${date(i.date)} will be removed permanently.` })) {
        router.delete(`/admin/incomes/${i.id}`, { preserveScroll: true });
    }
}
async function removeSelected() {
    const n = selected.value.length;
    if (await confirm({ title: `Delete ${n} income record${n > 1 ? 's' : ''}?`, message: 'This cannot be undone.' })) {
        router.post('/admin/incomes/bulk-delete', { ids: selected.value }, { preserveScroll: true, onSuccess: clear });
    }
}
</script>

<template>
    <Head title="Income" />
    <PageHeader title="Income" description="Salary, remittance, business and every other money coming in.">
        <a :href="exportUrl" class="btn btn-secondary"><Download class="h-4 w-4" /> Export CSV</a>
        <button class="btn btn-primary" @click="openQuickAdd('income')"><Plus class="h-4 w-4" /> Add income</button>
    </PageHeader>

    <!-- Charts -->
    <div class="mb-6 grid gap-6 xl:grid-cols-3">
        <div class="card xl:col-span-2">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Monthly income · {{ chartYear }}</h2>
                    <p class="card-sub">
                        January – December · total <span class="num">{{ money(yearTotal, { decimals: 0 }) }}</span>
                        <template v-if="monthsWithIncome"> · avg <span class="num">{{ money(yearTotal / monthsWithIncome, { decimals: 0 }) }}</span>/month</template>
                    </p>
                </div>
            </div>
            <div class="p-5">
                <Chart
                    :labels="monthly.labels"
                    :series="[{ label: 'Income', data: monthly.income, kind: 'bar', color: 'var(--series-income)' }]"
                    :legend="false"
                    :height="250"
                />
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">By source · {{ chartYear }}</h2>
                    <p class="card-sub">Click a source to filter the list</p>
                </div>
            </div>
            <div class="p-5">
                <BarList v-if="sources.length" :rows="sources" :limit="7" bar-class="bg-[var(--series-income)]" @select="(r) => (filters.source = r.name)" />
                <p v-else class="py-10 text-center text-sm text-zinc-500">No income recorded in {{ chartYear }}.</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 lg:flex-row lg:items-center">
            <SearchInput v-model="filters.search" placeholder="Search notes or amount…" class="lg:w-64" />
            <select v-model="filters.source" class="input w-auto min-w-[10rem]" aria-label="Source">
                <option value="">All sources</option>
                <option v-for="s in sourceOptions" :key="s.id" :value="s.name">{{ s.name }}</option>
            </select>
            <PeriodFilter v-model:year="filters.year" v-model:from="filters.from" v-model:to="filters.to" />
            <button v-if="hasFilters" class="btn btn-ghost btn-sm" @click="reset({ sort: 'date', dir: 'desc' })">Clear</button>
            <div class="text-right text-sm lg:ml-auto">
                <span class="text-zinc-500">{{ number(summary.count) }} entries ·</span>
                <span class="num font-semibold text-emerald-600 dark:text-emerald-400"> {{ money(summary.total) }}</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="h-4 w-4 rounded" :checked="allSelected" :indeterminate="someSelected" aria-label="Select all" @change="toggleAll" /></th>
                        <SortTh column="date" :sort="filters.sort" :dir="filters.dir" @sort="sortBy">Date</SortTh>
                        <th>Source</th>
                        <th>Note</th>
                        <SortTh column="amount" :sort="filters.sort" :dir="filters.dir" align="right" @sort="sortBy">Amount</SortTh>
                        <th class="w-24"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="i in rows" :key="i.id" :class="{ 'bg-brand-50/40 dark:bg-brand-500/5': selected.includes(i.id) }">
                        <td><input v-model="selected" type="checkbox" class="h-4 w-4 rounded" :value="i.id" :aria-label="`Select income ${i.id}`" /></td>
                        <td class="num whitespace-nowrap text-zinc-600 dark:text-zinc-400">{{ date(i.date) }}</td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <CategoryIcon :icon="i.icon" :color="i.color" size="sm" />
                                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ i.income_source || 'Unspecified' }}</span>
                            </div>
                        </td>
                        <td class="max-w-xs"><span class="block truncate" :title="i.remarks">{{ i.remarks || '—' }}</span></td>
                        <td class="num whitespace-nowrap text-right font-medium text-emerald-600 dark:text-emerald-400">{{ money(i.amount) }}</td>
                        <td>
                            <div class="flex justify-end gap-0.5">
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openQuickAdd('income', i)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(i)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <EmptyState v-if="!rows.length" title="No income found" :description="hasFilters ? 'Try a different filter or period.' : 'Record your first income to get started.'">
                <button class="btn btn-primary btn-sm" @click="openQuickAdd('income')"><Plus class="h-4 w-4" /> Add income</button>
            </EmptyState>
        </div>
        <Pagination :meta="incomes" />
    </div>

    <BulkBar :count="selected.length" @delete="removeSelected" @clear="clear" />
</template>

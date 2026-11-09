<script setup>
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { ArrowDownRight, ArrowUpRight, Download, HandCoins, Landmark, Printer, TrendingUp } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import StatCard from '../../Components/StatCard.vue';
import Chart from '../../Components/Chart.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import EmptyState from '../../Components/EmptyState.vue';
import ProgressBar from '../../Components/ProgressBar.vue';
import ReportCategoryMatrix from '../../Components/ReportCategoryMatrix.vue';
import { date, money, moneyCompact, number, percent } from '../../lib/format';

const props = defineProps({
    year: Number,
    compare: Number,
    years: Array,
    summary: Object,
    compareSummary: Object,
    months: Array,
    matrix: Object,
    yoy: Array,
    sources: Object,
    top: Array,
    activity: Object,
});

const labels = computed(() => props.months.map((m) => m.label));
const growth = (cur, prev) => (prev ? Math.round(((cur - prev) / Math.abs(prev)) * 1000) / 10 : null);
const s = computed(() => props.summary);
const c = computed(() => props.compareSummary);
const ratePts = computed(() => (s.value.savings_rate !== null && c.value.savings_rate !== null ? Math.round((s.value.savings_rate - c.value.savings_rate) * 10) / 10 : null));

const chartSeries = computed(() => [
    { label: 'Income', data: props.months.map((m) => m.income), kind: 'bar', color: 'var(--series-income)' },
    { label: 'Expenses', data: props.months.map((m) => m.expense), kind: 'bar', color: 'var(--series-expense)' },
    { label: 'Savings', data: props.months.map((m) => m.savings), kind: 'line', color: 'var(--series-savings)' },
]);
const totals = computed(() => {
    const income = props.months.reduce((a, m) => a + m.income, 0);
    const expense = props.months.reduce((a, m) => a + m.expense, 0);
    return { income, expense, savings: income - expense, rate: income > 0 ? ((income - expense) / income) * 100 : null };
});
const yoyRows = computed(() => props.yoy.filter((r) => r.current > 0 || r.previous > 0));

function go(params) {
    router.get('/admin/reports', { year: props.year, compare: props.compare, ...params }, { preserveScroll: true });
}
const printPage = () => window.print();
const exportUrl = computed(() => `/admin/reports/export?year=${props.year}`);
</script>

<template>
    <Head :title="`Report ${year}`" />
    <PageHeader :title="`Annual report · ${year}`" :description="`1 January – 31 December ${year}, compared with ${compare}.`">
        <div class="flex items-center gap-2 print:hidden">
            <label class="text-xs text-zinc-500" for="r-year">Year</label>
            <select id="r-year" class="input w-auto" :value="year" @change="go({ year: $event.target.value, compare: Number($event.target.value) - 1 })">
                <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
            </select>
            <label class="text-xs text-zinc-500" for="r-compare">vs</label>
            <select id="r-compare" class="input w-auto" :value="compare" @change="go({ compare: $event.target.value })">
                <option v-for="y in [...new Set([...years, year - 1])].sort((a, b) => b - a)" :key="y" :value="y">{{ y }}</option>
            </select>
        </div>
        <button class="btn btn-secondary print:hidden" @click="printPage"><Printer class="h-4 w-4" /> Print</button>
        <a :href="exportUrl" class="btn btn-secondary print:hidden"><Download class="h-4 w-4" /> Export CSV</a>
    </PageHeader>

    <!-- 1. Summary -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
        <StatCard label="Income" :value="money(s.income, { decimals: 0 })" :delta="growth(s.income, c.income)" :sub="`vs ${compare}`" />
        <StatCard label="Expenses" :value="money(s.expense, { decimals: 0 })" :delta="growth(s.expense, c.expense)" good-when="down" :sub="`vs ${compare}`" />
        <StatCard label="Savings" :value="money(s.savings, { decimals: 0 })" :delta="growth(s.savings, c.savings)" :sub="`vs ${compare}`" />
        <StatCard
            label="Savings rate"
            :value="percent(s.savings_rate)"
            :sub="ratePts === null ? `${compare}: ${percent(c.savings_rate)}` : `${ratePts >= 0 ? '+' : ''}${ratePts} pts vs ${compare}`"
        />
        <StatCard label="Lifetime investments" :value="money(s.lifetime_invested, { decimals: 0 })" :sub="`${compare}: ${money(c.lifetime_invested, { decimals: 0 })}`" />
        <StatCard label="Avg. monthly spend" :value="money(s.avg_monthly_expense, { decimals: 0 })" :delta="growth(s.avg_monthly_expense, c.avg_monthly_expense)" good-when="down" :sub="`vs ${compare}`" />
    </div>

    <!-- 2. Monthly -->
    <div class="card mt-6">
        <div class="card-header">
            <div>
                <h2 class="card-title">Month by month</h2>
                <p class="card-sub">
                    Savings = income − expenses for the month · cumulative runs from January
                    <span v-if="s.lifetime_invested"> · the Savings card above also subtracts {{ money(s.lifetime_invested, { decimals: 0 }) }} of lifetime investments</span>
                </p>
            </div>
        </div>
        <div class="p-5"><Chart :labels="labels" :series="chartSeries" :height="270" /></div>
        <div class="table-wrap border-t border-zinc-100 dark:border-zinc-800">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th class="text-right">Income</th>
                        <th class="text-right">Expenses</th>
                        <th class="text-right">Savings</th>
                        <th class="text-right">Savings rate</th>
                        <th class="text-right">Cumulative</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="m in months" :key="m.month">
                        <td class="font-medium text-zinc-900 dark:text-zinc-100">{{ m.label }}</td>
                        <td class="num text-right">{{ money(m.income, { decimals: 0 }) }}</td>
                        <td class="num text-right">{{ money(m.expense, { decimals: 0 }) }}</td>
                        <td class="num text-right font-medium" :class="m.savings < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-900 dark:text-zinc-100'">{{ money(m.savings, { decimals: 0 }) }}</td>
                        <td class="num text-right text-zinc-500">{{ percent(m.savings_rate) }}</td>
                        <td class="num text-right" :class="m.cumulative < 0 ? 'text-rose-600 dark:text-rose-400' : ''">{{ money(m.cumulative, { decimals: 0 }) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td class="num text-right">{{ money(totals.income, { decimals: 0 }) }}</td>
                        <td class="num text-right">{{ money(totals.expense, { decimals: 0 }) }}</td>
                        <td class="num text-right" :class="totals.savings < 0 ? 'text-rose-600 dark:text-rose-400' : ''">{{ money(totals.savings, { decimals: 0 }) }}</td>
                        <td class="num text-right">{{ percent(totals.rate) }}</td>
                        <td />
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- 3. Category × month -->
    <div class="card mt-6">
        <div class="card-header">
            <div>
                <h2 class="card-title">Spending by category and month</h2>
                <p class="card-sub">Darker cells = more spent · click a category to see its subcategories</p>
            </div>
        </div>
        <ReportCategoryMatrix v-if="matrix.rows.length" :matrix="matrix" :labels="labels" />
        <EmptyState v-else title="No spending recorded" :description="`No expenses in ${year}.`" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <!-- 4. Year over year -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Category change · {{ year }} vs {{ compare }}</h2>
                    <p class="card-sub">Sorted by size of change</p>
                </div>
            </div>
            <div class="table-wrap max-h-[28rem] overflow-y-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th class="text-right">{{ compare }}</th>
                            <th class="text-right">{{ year }}</th>
                            <th class="text-right">Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in yoyRows" :key="r.id ?? r.name">
                            <td>
                                <span class="flex items-center gap-2">
                                    <CategoryIcon :icon="r.icon" :color="r.color" size="sm" />
                                    <span class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ r.name }}</span>
                                </span>
                            </td>
                            <td class="num text-right text-zinc-500">{{ money(r.previous, { decimals: 0 }) }}</td>
                            <td class="num text-right">{{ money(r.current, { decimals: 0 }) }}</td>
                            <td class="num whitespace-nowrap text-right font-medium" :class="r.change > 0 ? 'text-rose-600 dark:text-rose-400' : r.change < 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-500'">
                                <span class="inline-flex items-center gap-0.5">
                                    <ArrowUpRight v-if="r.change > 0" class="h-3.5 w-3.5" />
                                    <ArrowDownRight v-else-if="r.change < 0" class="h-3.5 w-3.5" />
                                    {{ money(r.change, { decimals: 0, signed: true }) }}
                                </span>
                                <span class="block text-xs font-normal text-zinc-500">{{ r.change_pct === null ? (r.current > 0 ? 'new' : '—') : `${r.change_pct > 0 ? '+' : ''}${r.change_pct}%` }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <EmptyState v-if="!yoyRows.length" title="Nothing to compare" />
            </div>
        </div>

        <!-- 6. Top expenses -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Largest expenses · {{ year }}</h2>
                    <p class="card-sub">Top 15 single entries</p>
                </div>
            </div>
            <ul v-if="top.length" class="max-h-[28rem] divide-y divide-zinc-100 overflow-y-auto dark:divide-zinc-800">
                <li v-for="(e, i) in top" :key="e.id" class="flex items-center gap-3 px-5 py-2.5">
                    <span class="num w-5 text-right text-xs text-zinc-400">{{ i + 1 }}</span>
                    <CategoryIcon :icon="e.icon" :color="e.color" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            {{ e.remarks || e.category || 'Expense' }}
                            <span v-if="e.is_estimated" class="badge badge-amber ml-1">Estimated</span>
                        </p>
                        <p class="truncate text-xs text-zinc-500">{{ e.parent ? `${e.parent} › ` : '' }}{{ e.category || 'No category' }} · {{ date(e.date) }}</p>
                    </div>
                    <span class="num text-sm font-semibold text-zinc-900 dark:text-white">{{ money(e.amount, { decimals: 0 }) }}</span>
                </li>
            </ul>
            <EmptyState v-else title="No expenses" />
        </div>
    </div>

    <!-- 5. Income by source × month -->
    <div class="card mt-6">
        <div class="card-header">
            <div>
                <h2 class="card-title">Income by source and month</h2>
                <p class="card-sub">Total {{ money(sources.total, { decimals: 0 }) }}</p>
            </div>
        </div>
        <div v-if="sources.rows.length" class="table-wrap">
            <table class="data-table text-[13px]">
                <thead>
                    <tr>
                        <th class="min-w-[10rem]">Source</th>
                        <th v-for="m in labels" :key="m" class="text-right">{{ m }}</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in sources.rows" :key="r.name">
                        <td>
                            <span class="flex items-center gap-2">
                                <CategoryIcon :icon="r.icon" :color="r.color" size="sm" />
                                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ r.name }}</span>
                            </span>
                        </td>
                        <td v-for="(v, i) in r.months" :key="i" class="num whitespace-nowrap text-right" :class="v ? '' : 'text-zinc-300 dark:text-zinc-600'" :title="`${r.name} · ${labels[i]}: ${money(v)}`">
                            {{ v ? moneyCompact(v) : '—' }}
                        </td>
                        <td class="num whitespace-nowrap text-right font-semibold text-zinc-900 dark:text-white">{{ money(r.total, { decimals: 0 }) }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total</td>
                        <td v-for="(v, i) in sources.monthTotals" :key="i" class="num whitespace-nowrap text-right">{{ v ? moneyCompact(v) : '—' }}</td>
                        <td class="num whitespace-nowrap text-right">{{ money(sources.total, { decimals: 0 }) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <EmptyState v-else title="No income recorded" :description="`No income in ${year}.`" />
    </div>

    <!-- 7. Loans & investments -->
    <div class="card mt-6">
        <div class="card-header">
            <div>
                <h2 class="card-title">Loans & investments started in {{ year }}</h2>
                <p class="card-sub">“Back so far” is what has been returned on these to date</p>
            </div>
        </div>
        <div class="grid gap-px bg-zinc-100 dark:bg-zinc-800 md:grid-cols-3">
            <div v-for="item in [
                { key: 'lent', title: 'Money lent', icon: HandCoins, back: 'Returned to you', tone: 'green' },
                { key: 'borrowed', title: 'Money borrowed', icon: Landmark, back: 'Repaid by you', tone: 'brand' },
                { key: 'invested', title: 'Invested', icon: TrendingUp, back: 'Returned so far', tone: 'brand' },
            ]" :key="item.key" class="bg-white p-5 dark:bg-zinc-900">
                <div class="flex items-center gap-2 text-sm text-zinc-500">
                    <component :is="item.icon" class="h-4 w-4" /> {{ item.title }}
                    <span class="num ml-auto text-xs">{{ number(activity[item.key].count) }} {{ activity[item.key].count === 1 ? 'entry' : 'entries' }}</span>
                </div>
                <p class="num mt-1.5 text-xl font-semibold text-zinc-900 dark:text-white">{{ money(activity[item.key].amount, { decimals: 0 }) }}</p>
                <div class="mt-3">
                    <div class="mb-1 flex justify-between text-xs text-zinc-500">
                        <span>{{ item.back }}</span>
                        <span class="num">{{ money(activity[item.key].returned, { decimals: 0 }) }}</span>
                    </div>
                    <ProgressBar :value="activity[item.key].returned" :max="activity[item.key].amount" :tone="item.tone" />
                </div>
                <p v-if="item.key === 'invested' && activity.invested.lifetime" class="num mt-2 text-xs text-zinc-500">
                    incl. {{ money(activity.invested.lifetime, { decimals: 0 }) }} lifetime (not expected back)
                </p>
            </div>
        </div>
    </div>
</template>

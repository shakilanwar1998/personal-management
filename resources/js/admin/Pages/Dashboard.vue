<script setup>
import { computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    AlertCircle, ArrowDownLeft, ArrowRight, ArrowUpRight, CalendarClock, FileWarning, HandCoins, Landmark, Plus,
    Scale, TrendingUp, Wallet,
} from 'lucide-vue-next';
import StatCard from '../Components/StatCard.vue';
import Chart from '../Components/Chart.vue';
import BarList from '../Components/BarList.vue';
import CategoryIcon from '../Components/CategoryIcon.vue';
import ProgressBar from '../Components/ProgressBar.vue';
import EmptyState from '../Components/EmptyState.vue';
import { openQuickAdd } from '../composables/useQuickAdd';
import { date, money, percent, relativeDue, shortDate, daysUntil } from '../lib/format';

const props = defineProps({
    year: Number,
    month: Object,
    worth: Object,
    summary: Object,
    prevSummary: Object,
    series: Array,
    categories: Array,
    sources: Array,
    budgets: Array,
    bills: Array,
    alerts: Array,
    recent: Array,
    goals: Array,
});

const page = usePage();
const userFirst = computed(() => (page.props.auth.user?.name || '').split(' ')[0]);
const greeting = computed(() => {
    const h = new Date().getHours();
    return h < 12 ? 'Good morning' : h < 18 ? 'Good afternoon' : 'Good evening';
});

const yoy = (cur, prev) => (prev ? Math.round(((cur - prev) / Math.abs(prev)) * 1000) / 10 : null);
const labels = computed(() => props.series.map((m) => m.label));
// Months that haven't happened yet (only in the current year) get no savings point.
const isFuture = (m) => props.year > new Date().getFullYear() || (props.year === new Date().getFullYear() && m > new Date().getMonth() + 1);
const cleanNote = (t) => (t || '').replace(/^\[ESTIMATED\]\s*/, '');
const isEstimated = (t) => (t || '').startsWith('[ESTIMATED]');
const chartSeries = computed(() => [
    { label: 'Income', data: props.series.map((m) => m.income), kind: 'bar', color: 'var(--series-income)' },
    { label: 'Expenses', data: props.series.map((m) => m.expense), kind: 'bar', color: 'var(--series-expense)' },
    { label: 'Savings', data: props.series.map((m) => (isFuture(m.month) ? null : m.savings)), kind: 'line', color: 'var(--series-savings)' },
]);
const categoryRows = computed(() => props.categories.map((c) => ({ ...c })));
const attention = computed(() => [
    ...props.alerts.map((a) => ({ ...a, kind: 'alert' })),
    ...props.bills.map((b) => ({ type: 'bill', title: b.name, amount: b.amount, date: b.next_due_date, href: '/admin/bills', icon: b.icon, color: b.color })),
].sort((a, b) => (a.date || '').localeCompare(b.date || '')));

const alertIcon = { lent: HandCoins, borrowed: Landmark, invoice: FileWarning };
</script>

<template>
    <Head title="Dashboard" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ greeting }}, {{ userFirst }}</p>
            <h1 class="mt-0.5 text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Your money at a glance</h1>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-secondary" @click="openQuickAdd('income')"><ArrowDownLeft class="h-4 w-4 text-emerald-600" /> Add income</button>
            <button class="btn btn-secondary" @click="openQuickAdd('expense')"><ArrowUpRight class="h-4 w-4 text-rose-600" /> Add expense</button>
        </div>
    </div>

    <!-- Headline numbers -->
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard label="Current balance" :value="money(worth.cash, { decimals: 0 })" sub="Cash after money lent & invested" :icon="Wallet" tone="brand" />
        <StatCard label="Net worth" :value="money(worth.net_worth, { decimals: 0 })" :sub="`Assets ${money(worth.assets, { decimals: 0 })} · Owed ${money(worth.liabilities, { decimals: 0 })}`" :icon="Scale" tone="violet" />
        <StatCard :label="`Income · ${month.label}`" :value="money(month.income, { decimals: 0 })" :delta="month.income_growth" :sub="`vs ${month.last_label}`" :icon="ArrowDownLeft" tone="green" />
        <StatCard :label="`Expenses · ${month.label}`" :value="money(month.expense, { decimals: 0 })" :delta="month.expense_growth" good-when="down" :sub="`vs ${month.last_label}`" :icon="ArrowUpRight" tone="red" />
    </div>

    <!-- Cash flow + attention -->
    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="card xl:col-span-2">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Cash flow · {{ year }}</h2>
                    <p class="card-sub">Monthly income, expenses and savings · January – December</p>
                </div>
                <Link href="/admin/reports" class="btn btn-ghost btn-sm">Full report <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <div class="grid grid-cols-2 gap-px border-b border-zinc-100 bg-zinc-100 dark:border-zinc-800 dark:bg-zinc-800 sm:grid-cols-4">
                <div class="bg-white px-5 py-3.5 dark:bg-zinc-900">
                    <p class="text-xs text-zinc-500">Income</p>
                    <p class="num mt-0.5 text-lg font-semibold">{{ money(summary.income, { decimals: 0 }) }}</p>
                    <p v-if="yoy(summary.income, prevSummary.income) !== null" class="num text-xs" :class="yoy(summary.income, prevSummary.income) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                        {{ yoy(summary.income, prevSummary.income) >= 0 ? '+' : '' }}{{ yoy(summary.income, prevSummary.income) }}% vs {{ year - 1 }}
                    </p>
                </div>
                <div class="bg-white px-5 py-3.5 dark:bg-zinc-900">
                    <p class="text-xs text-zinc-500">Expenses</p>
                    <p class="num mt-0.5 text-lg font-semibold">{{ money(summary.expense, { decimals: 0 }) }}</p>
                    <p v-if="yoy(summary.expense, prevSummary.expense) !== null" class="num text-xs" :class="yoy(summary.expense, prevSummary.expense) <= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                        {{ yoy(summary.expense, prevSummary.expense) >= 0 ? '+' : '' }}{{ yoy(summary.expense, prevSummary.expense) }}% vs {{ year - 1 }}
                    </p>
                </div>
                <div class="bg-white px-5 py-3.5 dark:bg-zinc-900">
                    <p class="text-xs text-zinc-500">Savings</p>
                    <p class="num mt-0.5 text-lg font-semibold" :class="summary.savings < 0 ? 'text-rose-600 dark:text-rose-400' : ''">{{ money(summary.savings, { decimals: 0 }) }}</p>
                    <p v-if="summary.lifetime_invested" class="num text-xs text-zinc-500">after {{ money(summary.lifetime_invested, { decimals: 0 }) }} lifetime inv.</p>
                </div>
                <div class="bg-white px-5 py-3.5 dark:bg-zinc-900">
                    <p class="text-xs text-zinc-500">Savings rate</p>
                    <p class="num mt-0.5 text-lg font-semibold">{{ percent(summary.savings_rate) }}</p>
                    <p class="text-xs text-zinc-500">of income kept</p>
                </div>
            </div>
            <div class="p-5">
                <Chart :labels="labels" :series="chartSeries" :height="290" />
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <h2 class="card-title">This month</h2>
                    <span class="text-xs text-zinc-500">{{ month.label }}</span>
                </div>
                <p class="mt-3 text-xs text-zinc-500">Saved so far</p>
                <div class="flex items-baseline gap-2">
                    <p class="num text-2xl font-semibold tracking-tight" :class="month.savings < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-900 dark:text-white'">{{ money(month.savings, { decimals: 0 }) }}</p>
                    <span v-if="month.savings_growth !== null" class="num text-xs" :class="month.savings_growth >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                        {{ month.savings_growth >= 0 ? '↑' : '↓' }} {{ Math.abs(month.savings_growth) }}% vs {{ month.last_label }}
                    </span>
                </div>
                <div class="mt-4 space-y-2.5 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400"><span class="h-2 w-2 rounded-sm" style="background: var(--series-income)" /> Income</span>
                        <span class="num font-medium">{{ money(month.income, { decimals: 0 }) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-zinc-600 dark:text-zinc-400"><span class="h-2 w-2 rounded-sm" style="background: var(--series-expense)" /> Expenses</span>
                        <span class="num font-medium">{{ money(month.expense, { decimals: 0 }) }}</span>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-zinc-100 pt-4 text-sm dark:border-zinc-800">
                    <Link href="/admin/lending" class="rounded-lg p-2 -m-2 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <p class="text-xs text-zinc-500">Owed to you</p>
                        <p class="num font-semibold">{{ money(worth.lent, { decimals: 0 }) }}</p>
                    </Link>
                    <Link href="/admin/borrowing" class="rounded-lg p-2 -m-2 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                        <p class="text-xs text-zinc-500">You owe</p>
                        <p class="num font-semibold">{{ money(worth.liabilities, { decimals: 0 }) }}</p>
                    </Link>
                </div>
            </div>

            <div class="card flex-1">
                <div class="card-header">
                    <h2 class="card-title">Needs attention</h2>
                    <span v-if="attention.length" class="badge badge-amber num">{{ attention.length }}</span>
                </div>
                <ul v-if="attention.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <li v-for="(a, i) in attention.slice(0, 6)" :key="i">
                        <Link :href="a.href" class="flex items-center gap-3 px-5 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800/40">
                            <CategoryIcon v-if="a.type === 'bill'" :icon="a.icon || 'receipt'" :color="a.color || 'blue'" size="md" />
                            <span v-else class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                                <component :is="alertIcon[a.type] || AlertCircle" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ a.title }}</p>
                                <p class="text-xs" :class="daysUntil(a.date) < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-500'">{{ relativeDue(a.date) }}</p>
                            </div>
                            <span class="num text-sm font-medium">{{ a.currency ? `${a.currency} ${a.amount.toLocaleString()}` : money(a.amount, { decimals: 0 }) }}</span>
                        </Link>
                    </li>
                </ul>
                <div v-else class="flex flex-col items-center px-5 py-8 text-center">
                    <CalendarClock class="h-6 w-6 text-emerald-500" />
                    <p class="mt-2 text-sm font-medium">All clear</p>
                    <p class="text-xs text-zinc-500">No overdue loans, invoices or bills due in the next 2 weeks.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakdown + recent -->
    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Spending by category · {{ year }}</h2>
                    <p class="card-sub">Subcategories are included in their parent</p>
                </div>
                <Link href="/admin/reports" class="btn btn-ghost btn-sm">Details <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <div class="p-5">
                <BarList v-if="categoryRows.length" :rows="categoryRows" :limit="8" bar-class="bg-[var(--series-expense)]" @select="(r) => r.id && router.visit(`/admin/expenses?category=${r.id}`)" />
                <EmptyState v-else title="No spending recorded" :description="`Nothing spent in ${year} yet.`" />
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Recent transactions</h2>
                <Link href="/admin/expenses" class="btn btn-ghost btn-sm">View all <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <ul v-if="recent.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <li v-for="t in recent" :key="t.type + t.id" class="flex items-center gap-3 px-5 py-3">
                    <CategoryIcon :icon="t.icon" :color="t.color" />
                    <div class="min-w-0 flex-1">
                        <p class="flex min-w-0 items-center gap-1.5 text-sm font-medium text-zinc-900 dark:text-zinc-100">
                            <span v-if="isEstimated(t.remarks)" class="badge badge-amber">Est.</span>
                            <span class="min-w-0 truncate">{{ cleanNote(t.remarks) || t.label || (t.type === 'income' ? 'Income' : 'Expense') }}</span>
                        </p>
                        <p class="truncate text-xs text-zinc-500">{{ t.label }} · {{ shortDate(t.date) }}</p>
                    </div>
                    <span class="num text-sm font-semibold" :class="t.type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-900 dark:text-zinc-100'">
                        {{ t.type === 'income' ? '+' : '−' }}{{ money(t.amount).replace('−', '') }}
                    </span>
                </li>
            </ul>
            <EmptyState v-else title="No transactions yet" />
        </div>
    </div>

    <!-- Planning -->
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Budgets · {{ month.label }}</h2>
                    <p class="card-sub">Spent so far this month</p>
                </div>
                <Link href="/admin/budgets" class="btn btn-ghost btn-sm">Manage <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <div v-if="budgets.length" class="space-y-4 p-5">
                <div v-for="b in budgets" :key="b.id">
                    <div class="mb-1.5 flex items-center justify-between gap-2 text-sm">
                        <span class="flex min-w-0 items-center gap-2">
                            <CategoryIcon :icon="b.icon" :color="b.color" size="sm" />
                            <span class="truncate font-medium">{{ b.name }}</span>
                        </span>
                        <span class="num shrink-0 text-xs text-zinc-500">
                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ money(b.spent, { decimals: 0 }) }}</span> / {{ money(b.amount, { decimals: 0 }) }}
                        </span>
                    </div>
                    <ProgressBar :value="b.spent" :max="b.amount" />
                </div>
            </div>
            <EmptyState v-else title="No budgets yet" description="Set monthly limits per category to keep spending in check.">
                <Link href="/admin/budgets" class="btn btn-secondary btn-sm"><Plus class="h-4 w-4" /> Create a budget</Link>
            </EmptyState>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Savings goals</h2>
                <Link href="/admin/goals" class="btn btn-ghost btn-sm">Manage <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <div v-if="goals.length" class="space-y-4 p-5">
                <div v-for="g in goals" :key="g.id">
                    <div class="mb-1.5 flex items-center justify-between gap-2 text-sm">
                        <span class="flex min-w-0 items-center gap-2">
                            <CategoryIcon :icon="g.icon || 'target'" :color="g.color || 'indigo'" size="sm" />
                            <span class="truncate font-medium">{{ g.name }}</span>
                        </span>
                        <span class="num text-xs text-zinc-500">{{ g.target > 0 ? Math.min(100, Math.round((g.saved / g.target) * 100)) : 0 }}%</span>
                    </div>
                    <ProgressBar :value="g.saved" :max="g.target" tone="brand" />
                    <p class="num mt-1 text-xs text-zinc-500">{{ money(g.saved, { decimals: 0 }) }} of {{ money(g.target, { decimals: 0 }) }}<span v-if="g.target_date"> · by {{ date(g.target_date) }}</span></p>
                </div>
            </div>
            <EmptyState v-else title="No goals yet" description="Set money aside for things that matter.">
                <Link href="/admin/goals" class="btn btn-secondary btn-sm"><Plus class="h-4 w-4" /> Create a goal</Link>
            </EmptyState>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Income by source · {{ year }}</h2>
                <Link href="/admin/incomes" class="btn btn-ghost btn-sm">View <ArrowRight class="h-3.5 w-3.5" /></Link>
            </div>
            <div class="p-5">
                <BarList v-if="sources.length" :rows="sources" :limit="6" bar-class="bg-[var(--series-income)]" />
                <EmptyState v-else title="No income recorded" :description="`No income in ${year} yet.`" />
            </div>
            <div class="flex items-center gap-3 border-t border-zinc-100 px-5 py-3 text-sm dark:border-zinc-800">
                <TrendingUp class="h-4 w-4 text-zinc-400" />
                <span class="text-zinc-500">Invested (outstanding)</span>
                <Link href="/admin/investments" class="num ml-auto font-medium hover:underline">{{ money(worth.invested, { decimals: 0 }) }}</Link>
            </div>
        </div>
    </div>
</template>

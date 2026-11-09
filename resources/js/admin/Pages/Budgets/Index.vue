<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { AlertTriangle, ChevronLeft, ChevronRight, Gauge, Pencil, Plus, Trash2, Wallet } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import CategorySelect from '../../Components/CategorySelect.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import ProgressBar from '../../Components/ProgressBar.vue';
import Toggle from '../../Components/Toggle.vue';
import EmptyState from '../../Components/EmptyState.vue';
import Chart from '../../Components/Chart.vue';
import { confirm } from '../../composables/useConfirm';
import { money } from '../../lib/format';

const props = defineProps({
    month: Object,
    budgets: Array,
    totals: Object,
    unbudgeted: Array,
    history: Object,
});

const hasOverall = computed(() => props.budgets.some((b) => b.overall));
const categoryRows = computed(() => props.budgets.filter((b) => !b.overall));
const overall = computed(() => props.budgets.find((b) => b.overall));
const hasHistory = computed(() => props.history.spent.some((v) => v > 0) || props.history.budget.some((v) => v > 0));

const goMonth = (m) => router.get('/admin/budgets', { month: m }, { preserveScroll: true, preserveState: true });

// Create / edit
const showForm = ref(false);
const editing = ref(null);
const scope = ref('category'); // category | overall
const form = useForm({ expense_category_id: null, amount: '', is_active: true, notes: '' });

function openCreate(categoryId = null, suggested = '') {
    editing.value = null;
    form.reset();
    form.clearErrors();
    scope.value = 'category';
    form.expense_category_id = categoryId;
    form.amount = suggested ? Math.ceil(suggested / 100) * 100 : '';
    showForm.value = true;
}
function openEdit(b) {
    editing.value = b;
    form.clearErrors();
    scope.value = b.overall ? 'overall' : 'category';
    form.expense_category_id = b.expense_category_id;
    form.amount = b.amount;
    form.is_active = b.is_active;
    form.notes = b.notes ?? '';
    showForm.value = true;
}
function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    form.transform((d) => ({ ...d, expense_category_id: scope.value === 'overall' ? null : d.expense_category_id }));
    if (editing.value) form.put(`/admin/budgets/${editing.value.id}`, opts);
    else form.post('/admin/budgets', opts);
}
async function remove(b) {
    if (await confirm({ title: `Delete the ${b.name} budget?`, message: 'Your expenses are not affected.' })) {
        router.delete(`/admin/budgets/${b.id}`, { preserveScroll: true });
    }
}

const statusBadge = { ok: ['On track', 'badge-green'], near: ['Near limit', 'badge-amber'], over: ['Over budget', 'badge-red'] };
const pctOf = (spent, limit) => (limit > 0 ? Math.round((spent / limit) * 100) : 0);
</script>

<template>
    <Head title="Budgets" />
    <PageHeader title="Budgets" description="Monthly spending limits per category. A parent category's budget includes its subcategories.">
        <div class="flex items-center gap-1 rounded-lg border border-zinc-200 bg-white p-0.5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <button class="btn btn-ghost btn-icon btn-sm" aria-label="Previous month" @click="goMonth(month.prev)"><ChevronLeft class="h-4 w-4" /></button>
            <span class="min-w-[8.5rem] text-center text-sm font-medium">{{ month.label }}</span>
            <button class="btn btn-ghost btn-icon btn-sm" aria-label="Next month" @click="goMonth(month.next)"><ChevronRight class="h-4 w-4" /></button>
        </div>
        <button class="btn btn-primary" @click="openCreate()"><Plus class="h-4 w-4" /> New budget</button>
    </PageHeader>

    <template v-if="budgets.length">
        <!-- Summary -->
        <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Budgeted (categories)</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(totals.budgeted, { decimals: 0 }) }}</p>
                <p class="num mt-1.5 text-xs text-zinc-500">{{ money(totals.spent_budgeted, { decimals: 0 }) }} spent · {{ pctOf(totals.spent_budgeted, totals.budgeted) }}%</p>
                <ProgressBar class="mt-3" :value="totals.spent_budgeted" :max="totals.budgeted" />
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Total spent · {{ month.label }}</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight">{{ money(totals.month_spend, { decimals: 0 }) }}</p>
                <p class="mt-1.5 text-xs text-zinc-500">
                    <template v-if="totals.overall_limit">of {{ money(totals.overall_limit, { decimals: 0 }) }} overall limit</template>
                    <template v-else>All categories, budgeted or not</template>
                </p>
                <ProgressBar v-if="totals.overall_limit" class="mt-3" :value="totals.month_spend" :max="totals.overall_limit" />
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Left to spend</p>
                <p class="num mt-1.5 text-2xl font-semibold tracking-tight" :class="totals.budgeted - totals.spent_budgeted < 0 ? 'text-rose-600 dark:text-rose-400' : ''">
                    {{ money(totals.budgeted - totals.spent_budgeted, { decimals: 0 }) }}
                </p>
                <p class="mt-1.5 text-xs text-zinc-500">
                    <template v-if="month.is_current">{{ month.days_in_month - month.days_elapsed }} days left this month</template>
                    <template v-else>Month closed</template>
                </p>
            </div>
            <div class="card p-5">
                <p class="text-[13px] font-medium text-zinc-500">Status</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="badge badge-red num">{{ totals.over_count }} over</span>
                    <span class="badge badge-amber num">{{ totals.near_count }} near limit</span>
                </div>
                <p class="mt-2.5 text-xs text-zinc-500">Near limit means 85% or more used.</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <!-- Budget list -->
            <div class="card xl:col-span-2">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Budgets · {{ month.label }}</h2>
                        <p class="card-sub"><template v-if="month.is_current">Projection assumes you keep spending at this month's daily pace</template><template v-else>Final spending for the month</template></p>
                    </div>
                </div>
                <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <li v-if="overall" class="bg-brand-50/30 px-5 py-4 dark:bg-brand-500/5">
                        <div class="flex items-center gap-3">
                            <CategoryIcon icon="wallet" color="indigo" />
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">Overall spending</p>
                                    <span v-if="!overall.is_active" class="badge badge-gray">Paused</span>
                                    <span v-else class="badge" :class="statusBadge[overall.status][1]">{{ statusBadge[overall.status][0] }}</span>
                                </div>
                                <p class="text-xs text-zinc-500">Limit for everything you spend in a month</p>
                            </div>
                            <div class="text-right">
                                <p class="num text-sm font-semibold">{{ money(overall.spent, { decimals: 0 }) }} <span class="font-normal text-zinc-400">/ {{ money(overall.amount, { decimals: 0 }) }}</span></p>
                                <p class="num text-xs" :class="overall.remaining < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-500'">
                                    {{ overall.remaining < 0 ? money(-overall.remaining, { decimals: 0 }) + ' over' : money(overall.remaining, { decimals: 0 }) + ' left' }}
                                </p>
                            </div>
                            <div class="flex gap-0.5">
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openEdit(overall)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(overall)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </div>
                        <ProgressBar class="mt-3" :value="overall.spent" :max="overall.amount" :tone="overall.is_active ? 'auto' : 'brand'" />
                        <p v-if="overall.projected !== null && overall.is_active" class="num mt-1.5 text-xs" :class="overall.projected > overall.amount ? 'text-amber-700 dark:text-amber-400' : 'text-zinc-500'">
                            Projected month-end: {{ money(overall.projected, { decimals: 0 }) }}
                        </p>
                    </li>
                    <li v-for="b in categoryRows" :key="b.id" class="px-5 py-4" :class="{ 'opacity-60': !b.is_active }">
                        <div class="flex items-center gap-3">
                            <CategoryIcon :icon="b.icon" :color="b.color" />
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <Link :href="`/admin/expenses?category=${b.expense_category_id}&year=custom&from=${month.value}-01&to=${month.value}-${month.days_in_month}`" class="truncate font-medium text-zinc-900 hover:underline dark:text-zinc-100">{{ b.name }}</Link>
                                    <span v-if="!b.is_active" class="badge badge-gray">Paused</span>
                                    <span v-else class="badge" :class="statusBadge[b.status][1]">{{ statusBadge[b.status][0] }}</span>
                                </div>
                                <p class="truncate text-xs text-zinc-500">{{ b.parent ? `${b.parent} · ` : '' }}{{ b.notes || `${b.percent}% used` }}</p>
                            </div>
                            <div class="text-right">
                                <p class="num text-sm font-semibold">{{ money(b.spent, { decimals: 0 }) }} <span class="font-normal text-zinc-400">/ {{ money(b.amount, { decimals: 0 }) }}</span></p>
                                <p class="num text-xs" :class="b.remaining < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-500'">
                                    {{ b.remaining < 0 ? money(-b.remaining, { decimals: 0 }) + ' over' : money(b.remaining, { decimals: 0 }) + ' left' }}
                                </p>
                            </div>
                            <div class="flex gap-0.5">
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openEdit(b)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="remove(b)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </div>
                        <ProgressBar class="mt-3" :value="b.spent" :max="b.amount" :tone="b.is_active ? 'auto' : 'brand'" />
                        <p v-if="b.projected !== null && b.is_active && b.spent > 0" class="num mt-1.5 flex items-center gap-1 text-xs" :class="b.projected > b.amount ? 'text-amber-700 dark:text-amber-400' : 'text-zinc-500'">
                            <AlertTriangle v-if="b.projected > b.amount && b.status !== 'over'" class="h-3 w-3" />
                            Projected month-end: {{ money(b.projected, { decimals: 0 }) }}
                        </p>
                    </li>
                    <li v-if="!categoryRows.length" class="px-5 py-8 text-center text-sm text-zinc-500">No category budgets yet. Add one to track a specific kind of spending.</li>
                </ul>
            </div>

            <div class="flex flex-col gap-6">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="card-title">Budget vs actual</h2>
                            <p class="card-sub">Last 6 months, using today's budgets</p>
                        </div>
                    </div>
                    <div class="p-5">
                        <Chart
                            v-if="hasHistory"
                            :labels="history.labels"
                            :series="[
                                { label: 'Budget', data: history.budget, kind: 'bar', color: 'var(--series-savings)' },
                                { label: 'Spent', data: history.spent, kind: 'bar', color: 'var(--series-expense)' },
                            ]"
                            :height="220"
                        />
                        <p v-else class="py-8 text-center text-sm text-zinc-500">No spending in this period.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="card-title">Unbudgeted spending</h2>
                            <p class="card-sub">Categories with spending but no budget</p>
                        </div>
                    </div>
                    <ul v-if="unbudgeted.length" class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        <li v-for="u in unbudgeted.slice(0, 8)" :key="u.id" class="flex items-center gap-3 px-5 py-2.5">
                            <CategoryIcon :icon="u.icon" :color="u.color" size="sm" />
                            <span class="min-w-0 flex-1 truncate text-sm font-medium">{{ u.name }}</span>
                            <span class="num text-sm text-zinc-600 dark:text-zinc-300">{{ money(u.spent, { decimals: 0 }) }}</span>
                            <button class="btn btn-secondary btn-xs" @click="openCreate(u.id, u.spent)">Set budget</button>
                        </li>
                    </ul>
                    <p v-else class="px-5 py-8 text-center text-sm text-zinc-500">Every category you spent on has a budget.</p>
                </div>
            </div>
        </div>
    </template>

    <!-- Empty state -->
    <div v-else class="card">
        <EmptyState
            :icon="Gauge"
            title="Set your first monthly budget"
            description="Give a category a monthly limit — for example ৳15,000 for Food — and see how much is left, what you'll likely spend by month-end, and where you're overspending."
        >
            <div class="flex flex-wrap justify-center gap-2">
                <button class="btn btn-primary" @click="openCreate()"><Plus class="h-4 w-4" /> New budget</button>
            </div>
        </EmptyState>
        <div v-if="unbudgeted.length" class="border-t border-zinc-100 px-5 py-4 dark:border-zinc-800">
            <p class="mb-3 text-xs font-medium uppercase tracking-wider text-zinc-400">Where your money went in {{ month.label }}</p>
            <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                <div v-for="u in unbudgeted.slice(0, 8)" :key="u.id" class="flex items-center gap-2.5 rounded-lg border border-zinc-200 px-3 py-2 dark:border-zinc-800">
                    <CategoryIcon :icon="u.icon" :color="u.color" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">{{ u.name }}</p>
                        <p class="num text-xs text-zinc-500">{{ money(u.spent, { decimals: 0 }) }}</p>
                    </div>
                    <button class="btn btn-ghost btn-xs" @click="openCreate(u.id, u.spent)">Budget</button>
                </div>
            </div>
        </div>
    </div>

    <Modal :show="showForm" :title="editing ? 'Edit budget' : 'New budget'" description="The limit applies to every month until you change it." @close="showForm = false">
        <form id="budget-form" class="space-y-4" @submit.prevent="submit">
            <div v-if="!editing" class="seg w-full">
                <button type="button" class="flex-1" :class="{ on: scope === 'category' }" @click="scope = 'category'">One category</button>
                <button type="button" class="flex-1" :class="{ on: scope === 'overall' }" :disabled="hasOverall" @click="scope = 'overall'">
                    Overall spending<span v-if="hasOverall" class="ml-1 text-xs">(exists)</span>
                </button>
            </div>
            <Field v-if="scope === 'category'" label="Category" :error="form.errors.expense_category_id" hint="Pick a parent category to include all of its subcategories." required>
                <CategorySelect v-model="form.expense_category_id" :error="!!form.errors.expense_category_id" />
            </Field>
            <div v-else class="flex items-start gap-3 rounded-lg bg-zinc-50 p-3 text-sm text-zinc-600 dark:bg-zinc-800/50 dark:text-zinc-300">
                <Wallet class="mt-0.5 h-4 w-4 shrink-0 text-brand-500" />
                A single limit for everything you spend in a month, across all categories.
                <p v-if="form.errors.expense_category_id" class="error">{{ form.errors.expense_category_id }}</p>
            </div>
            <Field label="Monthly limit" :error="form.errors.amount" required for="budget-amount">
                <MoneyInput id="budget-amount" v-model="form.amount" :error="!!form.errors.amount" />
            </Field>
            <Field label="Note" :error="form.errors.notes" for="budget-notes">
                <input id="budget-notes" v-model="form.notes" type="text" maxlength="255" class="input" placeholder="Optional" />
            </Field>
            <Toggle v-model="form.is_active" label="Active" description="Paused budgets are kept but not counted." />
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="budget-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Create budget' }}</button>
        </template>
    </Modal>
</template>

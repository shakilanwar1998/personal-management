<script setup>
import { computed, reactive, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import {
    ChevronRight, ChevronsDownUp, ChevronsUpDown, GitMerge, MoreHorizontal, Pencil, Pin, PinOff, Plus, Trash2,
} from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import SearchInput from '../../Components/SearchInput.vue';
import EmptyState from '../../Components/EmptyState.vue';
import CategoryIcon from '../../Components/CategoryIcon.vue';
import CategorySelect from '../../Components/CategorySelect.vue';
import Dropdown from '../../Components/Dropdown.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import Toggle from '../../Components/Toggle.vue';
import IconColorPicker from '../../Components/IconColorPicker.vue';
import { confirm } from '../../composables/useConfirm';
import { money, number } from '../../lib/format';

const props = defineProps({
    tab: String,
    tree: Array,
    income: Array,
    chartYear: Number,
    stats: Object,
});

const page = usePage();

// ---- Tabs (kept in ?tab=) ----
const tab = ref(props.tab);
function setTab(t) {
    tab.value = t;
    router.get('/admin/categories', t === 'income' ? { tab: 'income' } : {}, { preserveState: true, preserveScroll: true, replace: true, only: [] });
}

// ---- Expense tree ----
const q = ref('');
const expanded = reactive(new Set());
const toggle = (id) => (expanded.has(id) ? expanded.delete(id) : expanded.add(id));
const allExpanded = computed(() => props.tree.filter((p) => p.children.length).every((p) => expanded.has(p.id)));
function toggleAll() {
    if (allExpanded.value) expanded.clear();
    else props.tree.forEach((p) => p.children.length && expanded.add(p.id));
}

const visibleTree = computed(() => {
    const s = q.value.trim().toLowerCase();
    if (!s) return props.tree.map((p) => ({ ...p, open: expanded.has(p.id), shownChildren: p.children }));
    return props.tree
        .map((p) => {
            const selfHit = p.name.toLowerCase().includes(s);
            const kids = p.children.filter((c) => c.name.toLowerCase().includes(s));
            if (!selfHit && !kids.length) return null;
            return { ...p, open: kids.length > 0 || expanded.has(p.id), shownChildren: selfHit ? p.children : kids };
        })
        .filter(Boolean);
});

const yearTotal = computed(() => props.tree.reduce((s, p) => s + p.tree_total, 0));

function togglePin(c) {
    router.post(`/admin/categories/expense/${c.id}/toggle-stats`, {}, { preserveScroll: true });
}

// ---- Expense category form ----
const showForm = ref(false);
const editing = ref(null);
const form = useForm({ name: '', parent: 0, icon: null, color: null, is_stats: false, description: '' });
const parentOptions = computed(() => props.tree.filter((p) => !editing.value || p.id !== editing.value.id));
const editingHasChildren = computed(() => !!editing.value && (props.tree.find((p) => p.id === editing.value.id)?.children.length ?? 0) > 0);

function openCreate(parent = null) {
    editing.value = null;
    form.defaults({ name: '', parent: parent?.id ?? 0, icon: parent ? null : 'circle-dot', color: parent ? null : 'indigo', is_stats: false, description: '' });
    form.reset();
    form.clearErrors();
    showForm.value = true;
}
function openEdit(c) {
    editing.value = c;
    form.defaults({
        name: c.name,
        parent: c.parent || 0,
        icon: c.icon_inherited ? null : c.icon,
        color: c.icon_inherited ? null : c.color,
        is_stats: c.is_stats,
        description: c.description || '',
    });
    form.reset();
    form.clearErrors();
    showForm.value = true;
}
function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editing.value) form.put(`/admin/categories/expense/${editing.value.id}`, opts);
    else form.post('/admin/categories/expense', opts);
}

// ---- Merge ----
const mergeSource = ref(null);
const mergeForm = useForm({ target_id: null });
const mergeOptions = computed(() => {
    const src = mergeSource.value;
    if (!src) return [];
    const all = page.props.lookups?.expenseCategories ?? [];
    const hasKids = (props.tree.find((p) => p.id === src.id)?.children.length ?? 0) > 0;
    return all.filter((o) => o.id !== src.id && o.parent_id !== src.id && (!hasKids || !o.parent_id));
});
function openMerge(c) {
    mergeSource.value = c;
    mergeForm.reset();
    mergeForm.clearErrors();
}
function submitMerge() {
    mergeForm.post(`/admin/categories/expense/${mergeSource.value.id}/merge`, {
        preserveScroll: true,
        onSuccess: () => (mergeSource.value = null),
    });
}

async function removeExpense(c) {
    if (c.all_count > 0 || (c.children && c.children.length)) {
        openMerge(c);
        return;
    }
    if (await confirm({ title: `Delete “${c.name}”?`, message: 'This category has no expenses. It will be removed permanently.' })) {
        router.delete(`/admin/categories/expense/${c.id}`, { preserveScroll: true });
    }
}

// ---- Income categories ----
const showIncomeForm = ref(false);
const editingIncome = ref(null);
const incomeForm = useForm({ name: '', icon: 'circle-dot', color: 'emerald' });
function openIncomeCreate() {
    editingIncome.value = null;
    incomeForm.defaults({ name: '', icon: 'circle-dot', color: 'emerald' });
    incomeForm.reset();
    incomeForm.clearErrors();
    showIncomeForm.value = true;
}
function openIncomeEdit(c) {
    editingIncome.value = c;
    incomeForm.defaults({ name: c.name, icon: c.icon, color: c.color });
    incomeForm.reset();
    incomeForm.clearErrors();
    showIncomeForm.value = true;
}
function submitIncome() {
    const opts = { preserveScroll: true, onSuccess: () => (showIncomeForm.value = false) };
    if (editingIncome.value) incomeForm.put(`/admin/categories/income/${editingIncome.value.id}`, opts);
    else incomeForm.post('/admin/categories/income', opts);
}

const deletingIncome = ref(null);
const reassignForm = useForm({ reassign_to: '' });
async function removeIncome(c) {
    if (c.all_count > 0) {
        deletingIncome.value = c;
        reassignForm.reset();
        reassignForm.clearErrors();
        return;
    }
    if (await confirm({ title: `Delete “${c.name}”?`, message: 'No income uses this category. It will be removed permanently.' })) {
        router.delete(`/admin/categories/income/${c.id}`, { preserveScroll: true });
    }
}
function submitReassign() {
    reassignForm.delete(`/admin/categories/income/${deletingIncome.value.id}`, {
        preserveScroll: true,
        onSuccess: () => (deletingIncome.value = null),
    });
}
const incomeTotal = computed(() => props.income.reduce((s, c) => s + c.year_total, 0));
</script>

<template>
    <Head title="Categories" />
    <PageHeader title="Categories" description="Organise how your spending and income are grouped.">
        <button v-if="tab === 'expense'" class="btn btn-primary" @click="openCreate()"><Plus class="h-4 w-4" /> New category</button>
        <button v-else class="btn btn-primary" @click="openIncomeCreate"><Plus class="h-4 w-4" /> New income category</button>
    </PageHeader>

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="seg">
            <button type="button" :class="{ on: tab === 'expense' }" @click="setTab('expense')">
                Expense <span class="num ml-1 text-xs text-zinc-400">{{ stats.expense_parents + stats.expense_children }}</span>
            </button>
            <button type="button" :class="{ on: tab === 'income' }" @click="setTab('income')">
                Income <span class="num ml-1 text-xs text-zinc-400">{{ stats.income }}</span>
            </button>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">Totals shown for <span class="num font-medium text-zinc-700 dark:text-zinc-200">{{ chartYear }}</span></p>
    </div>

    <!-- Expense categories -->
    <div v-if="tab === 'expense'" class="card">
        <div class="flex flex-col gap-3 border-b border-zinc-100 p-4 dark:border-zinc-800 sm:flex-row sm:items-center">
            <SearchInput v-model="q" placeholder="Search categories…" class="sm:w-64" />
            <button class="btn btn-ghost btn-sm" @click="toggleAll">
                <ChevronsDownUp v-if="allExpanded" class="h-4 w-4" /><ChevronsUpDown v-else class="h-4 w-4" />
                {{ allExpanded ? 'Collapse all' : 'Expand all' }}
            </button>
            <div class="text-right text-sm sm:ml-auto">
                <span class="text-zinc-500">{{ stats.expense_parents }} groups · {{ stats.expense_children }} subcategories ·</span>
                <span class="num font-semibold text-zinc-900 dark:text-white"> {{ money(yearTotal, { decimals: 0 }) }}</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Entries</th>
                        <th class="text-right">Spent in {{ chartYear }}</th>
                        <th class="text-center">Stats</th>
                        <th class="w-20"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="p in visibleTree" :key="p.id">
                        <tr>
                            <td>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="flex h-6 w-6 items-center justify-center rounded text-zinc-400 hover:bg-zinc-100 hover:text-zinc-700 dark:hover:bg-zinc-800 dark:hover:text-zinc-200"
                                        :class="{ invisible: !p.children.length }"
                                        :aria-label="p.open ? 'Collapse' : 'Expand'"
                                        @click="toggle(p.id)"
                                    >
                                        <ChevronRight class="h-4 w-4 transition-transform" :class="{ 'rotate-90': p.open }" />
                                    </button>
                                    <CategoryIcon :icon="p.icon" :color="p.color" />
                                    <div class="min-w-0 leading-tight">
                                        <p class="flex items-center gap-2 font-medium text-zinc-900 dark:text-zinc-100">
                                            <span class="truncate">{{ p.name }}</span>
                                            <span v-if="p.children.length" class="badge badge-gray num">{{ p.children.length }}</span>
                                            <span v-if="p.budget" class="badge badge-brand num" title="Monthly budget">{{ money(p.budget, { decimals: 0 }) }}/mo</span>
                                        </p>
                                        <p v-if="p.description" class="truncate text-xs text-zinc-500">{{ p.description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="num text-right text-zinc-500">{{ number(p.tree_count) }}</td>
                            <td class="num whitespace-nowrap text-right font-medium text-zinc-900 dark:text-white">{{ p.tree_total ? money(p.tree_total, { decimals: 0 }) : '—' }}</td>
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-ghost btn-icon btn-xs"
                                    :class="p.is_stats ? '!text-brand-600 dark:!text-brand-300' : ''"
                                    :title="p.is_stats ? 'Shown in expense stats — click to hide' : 'Show in expense stats'"
                                    @click="togglePin(p)"
                                >
                                    <Pin v-if="p.is_stats" class="h-3.5 w-3.5 fill-current" /><PinOff v-else class="h-3.5 w-3.5" />
                                </button>
                            </td>
                            <td>
                                <div class="flex justify-end">
                                    <Dropdown>
                                        <template #trigger="{ toggle: t }">
                                            <button class="btn btn-ghost btn-icon btn-xs" aria-label="Actions" @click="t"><MoreHorizontal class="h-4 w-4" /></button>
                                        </template>
                                        <button class="menu-item" @click="openEdit(p)"><Pencil class="h-4 w-4" /> Edit</button>
                                        <button class="menu-item" @click="openCreate(p)"><Plus class="h-4 w-4" /> Add subcategory</button>
                                        <button class="menu-item" @click="openMerge(p)"><GitMerge class="h-4 w-4" /> Merge into…</button>
                                        <button class="menu-item text-rose-600 dark:text-rose-400" @click="removeExpense(p)"><Trash2 class="h-4 w-4" /> Delete</button>
                                    </Dropdown>
                                </div>
                            </td>
                        </tr>
                        <template v-if="p.open">
                            <tr v-for="c in p.shownChildren" :key="c.id" class="bg-zinc-50/40 dark:bg-zinc-900/40">
                                <td>
                                    <div class="flex items-center gap-2.5 pl-8">
                                        <span class="h-px w-3 bg-zinc-300 dark:bg-zinc-700" />
                                        <CategoryIcon :icon="c.icon" :color="c.color" size="sm" />
                                        <div class="min-w-0 leading-tight">
                                            <p class="flex items-center gap-2 text-zinc-800 dark:text-zinc-200">
                                                <span class="truncate">{{ c.name }}</span>
                                                <span v-if="c.budget" class="badge badge-brand num">{{ money(c.budget, { decimals: 0 }) }}/mo</span>
                                            </p>
                                            <p v-if="c.description" class="truncate text-xs text-zinc-500">{{ c.description }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="num text-right text-zinc-500">{{ number(c.all_count) }}</td>
                                <td class="num whitespace-nowrap text-right text-zinc-700 dark:text-zinc-300">{{ c.year_total ? money(c.year_total, { decimals: 0 }) : '—' }}</td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-ghost btn-icon btn-xs"
                                        :class="c.is_stats ? '!text-brand-600 dark:!text-brand-300' : ''"
                                        :title="c.is_stats ? 'Shown in expense stats — click to hide' : 'Show in expense stats'"
                                        @click="togglePin(c)"
                                    >
                                        <Pin v-if="c.is_stats" class="h-3.5 w-3.5 fill-current" /><PinOff v-else class="h-3.5 w-3.5" />
                                    </button>
                                </td>
                                <td>
                                    <div class="flex justify-end">
                                        <Dropdown>
                                            <template #trigger="{ toggle: t }">
                                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Actions" @click="t"><MoreHorizontal class="h-4 w-4" /></button>
                                            </template>
                                            <button class="menu-item" @click="openEdit(c)"><Pencil class="h-4 w-4" /> Edit</button>
                                            <button class="menu-item" @click="openMerge(c)"><GitMerge class="h-4 w-4" /> Merge into…</button>
                                            <button class="menu-item text-rose-600 dark:text-rose-400" @click="removeExpense(c)"><Trash2 class="h-4 w-4" /> Delete</button>
                                        </Dropdown>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </template>
                </tbody>
            </table>
            <EmptyState v-if="!visibleTree.length" :title="q ? 'No categories match' : 'No categories yet'" :description="q ? `Nothing matches “${q}”.` : 'Create a category to start organising expenses.'" />
        </div>
    </div>

    <!-- Income categories -->
    <div v-else class="card">
        <div class="flex items-center justify-between border-b border-zinc-100 p-4 text-sm dark:border-zinc-800">
            <span class="text-zinc-500">Used as the “source” of each income record.</span>
            <span><span class="text-zinc-500">{{ chartYear }} total ·</span> <span class="num font-semibold text-emerald-600 dark:text-emerald-400">{{ money(incomeTotal, { decimals: 0 }) }}</span></span>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th class="text-right">Records</th>
                        <th class="text-right">Received in {{ chartYear }}</th>
                        <th class="w-24"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="c in income" :key="c.id">
                        <td>
                            <div class="flex items-center gap-2.5">
                                <CategoryIcon :icon="c.icon" :color="c.color" />
                                <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ c.name }}</span>
                            </div>
                        </td>
                        <td class="num text-right text-zinc-500">{{ number(c.all_count) }}</td>
                        <td class="num whitespace-nowrap text-right font-medium" :class="c.year_total ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400'">{{ c.year_total ? money(c.year_total, { decimals: 0 }) : '—' }}</td>
                        <td>
                            <div class="flex justify-end gap-0.5">
                                <button class="btn btn-ghost btn-icon btn-xs" aria-label="Edit" @click="openIncomeEdit(c)"><Pencil class="h-3.5 w-3.5" /></button>
                                <button class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" aria-label="Delete" @click="removeIncome(c)"><Trash2 class="h-3.5 w-3.5" /></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <EmptyState v-if="!income.length" title="No income categories" description="Add sources like Salary or Freelance." />
        </div>
    </div>

    <!-- Expense category form -->
    <Modal :show="showForm" :title="editing ? `Edit “${editing.name}”` : form.parent ? 'New subcategory' : 'New category'" size="lg" @close="showForm = false">
        <form id="cat-form" class="space-y-4" @submit.prevent="submit">
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Name" :error="form.errors.name" required for="cat-name">
                    <input id="cat-name" v-model="form.name" type="text" maxlength="255" class="input" :class="{ 'input-error': form.errors.name }" autofocus />
                </Field>
                <Field label="Parent" :error="form.errors.parent" :hint="editingHasChildren ? 'Has subcategories, so it stays top-level.' : 'Leave empty for a top-level group.'" for="cat-parent">
                    <select id="cat-parent" v-model.number="form.parent" class="input" :disabled="editingHasChildren">
                        <option :value="0">None (top level)</option>
                        <option v-for="p in parentOptions" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                </Field>
            </div>
            <Field label="Description" :error="form.errors.description" for="cat-desc">
                <input id="cat-desc" v-model="form.description" type="text" maxlength="255" class="input" placeholder="Optional" />
            </Field>
            <Toggle v-model="form.is_stats" label="Show in expense stats" description="Pinned categories get their own bar on the Expenses page." />
            <div>
                <IconColorPicker v-model:icon="form.icon" v-model:color="form.color" />
                <p v-if="form.parent && !form.icon" class="hint">No icon picked — the parent’s icon and colour are used.</p>
            </div>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="cat-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Create category' }}</button>
        </template>
    </Modal>

    <!-- Merge -->
    <Modal :show="!!mergeSource" :title="mergeSource ? `Merge “${mergeSource.name}”` : ''" description="Move everything into another category, then remove this one." @close="mergeSource = null">
        <form id="merge-form" class="space-y-4" @submit.prevent="submitMerge">
            <div v-if="mergeSource" class="rounded-lg border border-zinc-200 bg-zinc-50 p-3 text-sm text-zinc-600 dark:border-zinc-700 dark:bg-zinc-800/50 dark:text-zinc-300">
                <p><span class="num font-medium text-zinc-900 dark:text-white">{{ number(mergeSource.all_count) }}</span> expense(s) will be moved.</p>
                <p v-if="mergeSource.children?.length">Its {{ mergeSource.children.length }} subcategories will move under the target too.</p>
                <p class="mt-1 text-xs text-zinc-500">Budgets and recurring bills follow as well. No expense is deleted.</p>
            </div>
            <Field label="Merge into" :error="mergeForm.errors.target_id" required>
                <CategorySelect v-model="mergeForm.target_id" :options="mergeOptions" :error="!!mergeForm.errors.target_id" placeholder="Choose the category to keep" />
            </Field>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="mergeSource = null">Cancel</button>
            <button type="submit" form="merge-form" class="btn btn-primary" :disabled="mergeForm.processing || !mergeForm.target_id"><GitMerge class="h-4 w-4" /> Merge</button>
        </template>
    </Modal>

    <!-- Income category form -->
    <Modal :show="showIncomeForm" :title="editingIncome ? `Edit “${editingIncome.name}”` : 'New income category'" @close="showIncomeForm = false">
        <form id="inc-form" class="space-y-4" @submit.prevent="submitIncome">
            <Field
                label="Name"
                :error="incomeForm.errors.name"
                :hint="editingIncome && editingIncome.all_count ? `Renaming also updates its ${editingIncome.all_count} income record(s).` : ''"
                required
                for="inc-name"
            >
                <input id="inc-name" v-model="incomeForm.name" type="text" maxlength="255" class="input" :class="{ 'input-error': incomeForm.errors.name }" autofocus />
            </Field>
            <IconColorPicker v-model:icon="incomeForm.icon" v-model:color="incomeForm.color" />
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showIncomeForm = false">Cancel</button>
            <button type="submit" form="inc-form" class="btn btn-primary" :disabled="incomeForm.processing">{{ editingIncome ? 'Save changes' : 'Create' }}</button>
        </template>
    </Modal>

    <!-- Income category delete with reassign -->
    <Modal
        :show="!!deletingIncome"
        :title="deletingIncome ? `Delete “${deletingIncome.name}”` : ''"
        :description="deletingIncome ? `${deletingIncome.all_count} income record(s) use this category. Choose where to move them first.` : ''"
        size="sm"
        @close="deletingIncome = null"
    >
        <form id="reassign-form" @submit.prevent="submitReassign">
            <Field label="Move records to" :error="reassignForm.errors.reassign_to" required for="reassign">
                <select id="reassign" v-model="reassignForm.reassign_to" class="input" :class="{ 'input-error': reassignForm.errors.reassign_to }">
                    <option value="" disabled>Choose a category</option>
                    <option v-for="c in income.filter((x) => x.id !== deletingIncome?.id)" :key="c.id" :value="c.name">{{ c.name }}</option>
                </select>
            </Field>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="deletingIncome = null">Cancel</button>
            <button type="submit" form="reassign-form" class="btn btn-danger" :disabled="reassignForm.processing || !reassignForm.reassign_to">Move &amp; delete</button>
        </template>
    </Modal>
</template>

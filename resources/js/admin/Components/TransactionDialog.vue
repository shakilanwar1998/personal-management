<script setup>
import { computed, ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Modal from './Modal.vue';
import Field from './Field.vue';
import MoneyInput from './MoneyInput.vue';
import CategorySelect from './CategorySelect.vue';
import CategoryIcon from './CategoryIcon.vue';
import { quickAdd, closeQuickAdd } from '../composables/useQuickAdd';
import { today } from '../lib/format';

const page = usePage();
const incomeSources = computed(() => page.props.lookups?.incomeCategories ?? []);
const addAnother = ref(false);

const form = useForm({ date: today(), amount: '', category_id: null, income_source: 'Salary', remarks: '' });
const editing = computed(() => !!quickAdd.record);
const isExpense = computed(() => quickAdd.type === 'expense');

watch(
    () => quickAdd.open,
    (open) => {
        if (!open) return;
        form.clearErrors();
        const r = quickAdd.record;
        if (r) {
            form.defaults({
                date: r.date,
                amount: r.amount,
                category_id: r.category_id ?? null,
                income_source: r.income_source ?? incomeSources.value[0]?.name ?? 'Salary',
                remarks: r.remarks ?? '',
            });
        } else {
            // Keep the last used date/category when adding several in a row.
            form.defaults({
                date: form.date || today(),
                amount: '',
                category_id: addAnother.value ? form.category_id : null,
                income_source: form.income_source || incomeSources.value[0]?.name || 'Salary',
                remarks: '',
            });
        }
        form.reset();
    },
);

function submit() {
    const base = isExpense.value ? '/admin/expenses' : '/admin/incomes';
    const payload = (d) =>
        isExpense.value
            ? { date: d.date, amount: d.amount, category_id: d.category_id, remarks: d.remarks }
            : { date: d.date, amount: d.amount, income_source: d.income_source, remarks: d.remarks };
    const opts = {
        preserveScroll: true,
        onSuccess: () => {
            if (!editing.value && addAnother.value) {
                form.amount = '';
                form.remarks = '';
                form.clearErrors();
            } else {
                closeQuickAdd();
            }
        },
    };
    if (editing.value) form.transform(payload).put(`${base}/${quickAdd.record.id}`, opts);
    else form.transform(payload).post(base, opts);
}
</script>

<template>
    <Modal
        :show="quickAdd.open"
        :title="editing ? (isExpense ? 'Edit expense' : 'Edit income') : 'Add transaction'"
        @close="closeQuickAdd"
    >
        <form id="txn-form" class="space-y-4" @submit.prevent="submit">
            <div v-if="!editing" class="seg w-full">
                <button type="button" class="flex-1" :class="{ on: isExpense }" @click="quickAdd.type = 'expense'">Expense</button>
                <button type="button" class="flex-1" :class="{ on: !isExpense }" @click="quickAdd.type = 'income'">Income</button>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Amount" :error="form.errors.amount" required for="txn-amount">
                    <MoneyInput id="txn-amount" v-model="form.amount" :error="!!form.errors.amount" autofocus />
                </Field>
                <Field label="Date" :error="form.errors.date" required for="txn-date">
                    <input id="txn-date" v-model="form.date" type="date" class="input" :class="{ 'input-error': form.errors.date }" />
                </Field>
            </div>

            <Field v-if="isExpense" label="Category" :error="form.errors.category_id" required>
                <CategorySelect v-model="form.category_id" :error="!!form.errors.category_id" />
            </Field>
            <Field v-else label="Source" :error="form.errors.income_source" required>
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                    <button
                        v-for="s in incomeSources"
                        :key="s.id"
                        type="button"
                        class="flex items-center gap-2 rounded-lg border px-2.5 py-2 text-left text-[13px] font-medium transition"
                        :class="form.income_source === s.name ? 'border-brand-500 bg-brand-50 text-brand-700 ring-1 ring-brand-500 dark:bg-brand-500/10 dark:text-brand-200' : 'border-zinc-200 text-zinc-600 hover:border-zinc-300 dark:border-zinc-700 dark:text-zinc-300'"
                        @click="form.income_source = s.name"
                    >
                        <CategoryIcon :icon="s.icon" :color="s.color" size="sm" />
                        <span class="truncate">{{ s.name }}</span>
                    </button>
                </div>
            </Field>

            <Field label="Note" :error="form.errors.remarks" for="txn-remarks">
                <input id="txn-remarks" v-model="form.remarks" type="text" maxlength="255" class="input" placeholder="What was it for?" />
            </Field>
        </form>
        <template #footer>
            <label v-if="!editing" class="mr-auto flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                <input v-model="addAnother" type="checkbox" class="h-4 w-4 rounded" /> Add another after saving
            </label>
            <button type="button" class="btn btn-secondary" @click="closeQuickAdd">Cancel</button>
            <button type="submit" form="txn-form" class="btn btn-primary" :disabled="form.processing">
                {{ editing ? 'Save changes' : isExpense ? 'Add expense' : 'Add income' }}
            </button>
        </template>
    </Modal>
</template>

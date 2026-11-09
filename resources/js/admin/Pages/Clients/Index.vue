<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { FilePlus2, FileText, MoreHorizontal, Pencil, Plus, Trash2, Users } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import Modal from '../../Components/Modal.vue';
import Field from '../../Components/Field.vue';
import Dropdown from '../../Components/Dropdown.vue';
import EmptyState from '../../Components/EmptyState.vue';
import SearchInput from '../../Components/SearchInput.vue';
import { confirm } from '../../composables/useConfirm';
import { date, initials } from '../../lib/format';

const props = defineProps({
    clients: Array,
    countries: Object,
    currencies: Array,
});

const search = ref('');
const visible = computed(() => {
    const q = search.value.trim().toLowerCase();
    if (!q) return props.clients;
    return props.clients.filter((c) => [c.name, c.email, c.city].some((v) => v && v.toLowerCase().includes(q)));
});

const countryBadge = { US: 'badge-green', DE: 'badge-amber', GB: 'badge-blue' };

function fmt(amount, currency) {
    try {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: currency || 'USD' }).format(Number(amount) || 0);
    } catch {
        return `${currency} ${Number(amount || 0).toFixed(2)}`;
    }
}

const blank = { name: '', email: '', address: '', city: '', state: '', postal_code: '', country: 'US', tax_id: '', currency: 'USD', notes: '' };
const showForm = ref(false);
const editing = ref(null);
const form = useForm({ ...blank });

function openCreate() {
    editing.value = null;
    form.defaults({ ...blank });
    form.reset();
    form.clearErrors();
    showForm.value = true;
}
function openEdit(c) {
    editing.value = c;
    form.clearErrors();
    Object.keys(blank).forEach((k) => (form[k] = c[k] ?? blank[k]));
    showForm.value = true;
}
// Same defaults as the invoice form: the country decides the usual currency.
function onCountryChange() {
    form.currency = form.country === 'DE' ? 'EUR' : form.country === 'GB' ? 'GBP' : 'USD';
}
function submit() {
    const opts = { preserveScroll: true, onSuccess: () => (showForm.value = false) };
    if (editing.value) form.put(`/admin/clients/${editing.value.id}`, opts);
    else form.post('/admin/clients', opts);
}

async function remove(c) {
    const message = c.invoices_count
        ? `Their ${c.invoices_count} invoice(s) are kept with the client details they already have.`
        : 'This cannot be undone.';
    if (await confirm({ title: `Delete ${c.name}?`, message })) {
        router.delete(`/admin/clients/${c.id}`, { preserveScroll: true });
    }
}

const place = (c) => [c.city, props.countries[c.country] ?? c.country].filter(Boolean).join(', ');
</script>

<template>
    <Head title="Clients" />
    <PageHeader title="Clients" description="Saved client profiles. Pick one on a new invoice and the details fill in for you.">
        <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> Add client</button>
    </PageHeader>

    <div v-if="clients.length" class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title">All clients</h2>
                <p class="card-sub">{{ clients.length }} profile{{ clients.length === 1 ? '' : 's' }}</p>
            </div>
            <div class="w-full max-w-xs"><SearchInput v-model="search" placeholder="Search name, email, city…" /></div>
        </div>
        <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
            <li v-for="c in visible" :key="c.id" class="flex flex-col gap-3 px-5 py-3.5 sm:flex-row sm:items-center">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-semibold text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300">
                        {{ initials(c.name) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="truncate font-medium text-zinc-900 dark:text-zinc-100">{{ c.name }}</p>
                            <span class="badge" :class="countryBadge[c.country] || 'badge-gray'">{{ c.country }}</span>
                            <span class="badge badge-gray">{{ c.currency }}</span>
                        </div>
                        <p class="truncate text-xs text-zinc-500">
                            {{ [c.email, place(c)].filter(Boolean).join(' · ') || 'No contact details' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4 sm:justify-end">
                    <div class="text-left sm:text-right">
                        <p class="num text-sm font-semibold text-zinc-900 dark:text-white">
                            <template v-if="c.billed.length">{{ c.billed.map((b) => fmt(b.total, b.currency)).join(' + ') }}</template>
                            <span v-else class="font-normal text-zinc-400">Nothing billed</span>
                        </p>
                        <p class="text-xs text-zinc-500">
                            <Link v-if="c.invoices_count" :href="`/admin/invoices?client=${c.id}`" class="hover:underline">{{ c.invoices_count }} invoice{{ c.invoices_count === 1 ? '' : 's' }}</Link>
                            <span v-else>No invoices yet</span>
                            <template v-if="c.last_invoice_date"> · last {{ date(c.last_invoice_date) }}</template>
                        </p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 sm:ml-0">
                        <Link :href="`/admin/invoices/create?client=${c.id}`" class="btn btn-secondary btn-sm"><FilePlus2 class="h-3.5 w-3.5" /> New invoice</Link>
                        <Dropdown>
                            <template #trigger="{ toggle }">
                                <button class="btn btn-ghost btn-icon btn-sm" aria-label="More actions" @click="toggle"><MoreHorizontal class="h-4 w-4" /></button>
                            </template>
                            <button class="menu-item" @click="openEdit(c)"><Pencil class="h-4 w-4" /> Edit</button>
                            <Link v-if="c.invoices_count" :href="`/admin/invoices?client=${c.id}`" class="menu-item"><FileText class="h-4 w-4" /> View invoices</Link>
                            <button class="menu-item text-rose-600 dark:text-rose-400" @click="remove(c)"><Trash2 class="h-4 w-4" /> Delete</button>
                        </Dropdown>
                    </div>
                </div>
            </li>
            <li v-if="!visible.length" class="px-5 py-10 text-center text-sm text-zinc-500">No clients match “{{ search }}”.</li>
        </ul>
    </div>

    <div v-else class="card">
        <EmptyState :icon="Users" title="No clients yet" description="Save a client once and pick them from a dropdown every time you make an invoice.">
            <button class="btn btn-primary" @click="openCreate"><Plus class="h-4 w-4" /> Add your first client</button>
        </EmptyState>
    </div>

    <Modal :show="showForm" :title="editing ? `Edit ${editing.name}` : 'Add client'" @close="showForm = false">
        <form id="client-form" class="space-y-4" @submit.prevent="submit">
            <div class="grid gap-4 sm:grid-cols-2">
                <Field label="Client name" :error="form.errors.name" required for="cl-name" class="sm:col-span-2">
                    <input id="cl-name" v-model="form.name" type="text" maxlength="255" class="input" :class="{ 'input-error': form.errors.name }" placeholder="Person or company name" />
                </Field>
                <Field label="Email" :error="form.errors.email" for="cl-email">
                    <input id="cl-email" v-model="form.email" type="email" class="input" :class="{ 'input-error': form.errors.email }" />
                </Field>
                <Field label="Tax ID / VAT number" :error="form.errors.tax_id" for="cl-tax">
                    <input id="cl-tax" v-model="form.tax_id" type="text" class="input" />
                </Field>
                <Field label="Country" :error="form.errors.country" required for="cl-country">
                    <select id="cl-country" v-model="form.country" class="input" @change="onCountryChange">
                        <option v-for="(name, code) in countries" :key="code" :value="code">{{ name }}</option>
                    </select>
                </Field>
                <Field label="Invoice currency" :error="form.errors.currency" required for="cl-currency">
                    <select id="cl-currency" v-model="form.currency" class="input">
                        <option v-for="cur in currencies" :key="cur" :value="cur">{{ cur }}</option>
                    </select>
                </Field>
                <Field label="Address" :error="form.errors.address" class="sm:col-span-2" for="cl-address">
                    <textarea id="cl-address" v-model="form.address" rows="2" class="input" />
                </Field>
            </div>
            <div class="grid gap-4 sm:grid-cols-3">
                <Field label="City" :error="form.errors.city" for="cl-city">
                    <input id="cl-city" v-model="form.city" type="text" class="input" />
                </Field>
                <Field label="State / region" :error="form.errors.state" for="cl-state">
                    <input id="cl-state" v-model="form.state" type="text" class="input" />
                </Field>
                <Field label="Postal code" :error="form.errors.postal_code" for="cl-postal">
                    <input id="cl-postal" v-model="form.postal_code" type="text" class="input" />
                </Field>
            </div>
            <Field label="Notes" :error="form.errors.notes" hint="Only for you — not printed on invoices." for="cl-notes">
                <textarea id="cl-notes" v-model="form.notes" rows="2" class="input" />
            </Field>
        </form>
        <template #footer>
            <button type="button" class="btn btn-secondary" @click="showForm = false">Cancel</button>
            <button type="submit" form="client-form" class="btn btn-primary" :disabled="form.processing">{{ editing ? 'Save changes' : 'Add client' }}</button>
        </template>
    </Modal>
</template>

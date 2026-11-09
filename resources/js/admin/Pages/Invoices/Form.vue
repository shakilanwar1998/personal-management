<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ArrowDown, ArrowLeft, ArrowUp, ChevronDown, Download, Eye, Plus, Trash2 } from 'lucide-vue-next';
import Field from '../../Components/Field.vue';
import MoneyInput from '../../Components/MoneyInput.vue';
import InvoiceStatusBadge from '../../Components/InvoiceStatusBadge.vue';
import { confirm } from '../../composables/useConfirm';

const props = defineProps({
    invoice: Object,
    clients: { type: Array, default: () => [] },
    statuses: Array,
    currencies: Array,
    countries: Object,
});

const isEdit = computed(() => !!props.invoice.id);
let keySeq = 0;
const withKey = (item) => ({ _key: ++keySeq, description: '', quantity: 1, unit_price: '', tax_rate: 0, ...item });

const fields = [
    'invoice_number', 'invoice_date', 'due_date', 'status', 'currency',
    'client_name', 'client_email', 'client_address', 'client_city', 'client_state', 'client_postal_code', 'client_country', 'client_tax_id',
    'business_name', 'business_email', 'business_phone', 'business_website', 'business_address', 'business_city', 'business_state',
    'business_postal_code', 'business_country', 'business_tax_id',
    'tax_rate', 'notes', 'terms',
];
const initial = Object.fromEntries(fields.map((f) => [f, props.invoice[f] ?? '']));
initial.tax_rate = Number(props.invoice.tax_rate ?? 0);
initial.items = (props.invoice.items?.length ? props.invoice.items : [{}]).map(withKey);
initial.client_id = props.invoice.client_id ?? null;
initial.save_client = !isEdit.value;
initial.update_client = false;

const form = useForm(initial);

const showBusiness = ref(!props.invoice.business_name);
const showExtra = ref(!!(props.invoice.notes || props.invoice.terms));

const currencyFormatter = computed(() => {
    try {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: form.currency || 'USD' });
    } catch {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
});
const fmt = (n) => currencyFormatter.value.format(Number(n) || 0);

const lineTotal = (item) => (Number(item.quantity) || 0) * (Number(item.unit_price) || 0);
const subtotal = computed(() => form.items.reduce((s, i) => s + lineTotal(i), 0));
const taxAmount = computed(() => subtotal.value * ((Number(form.tax_rate) || 0) / 100));
const total = computed(() => subtotal.value + taxAmount.value);

// Client profiles: picking one fills the client fields.
const clientFields = { name: 'client_name', email: 'client_email', address: 'client_address', city: 'client_city', state: 'client_state', postal_code: 'client_postal_code', country: 'client_country', tax_id: 'client_tax_id' };
const selectedClient = computed(() => props.clients.find((c) => c.id === form.client_id) ?? null);

function onClientChange() {
    const c = selectedClient.value;
    form.update_client = false;
    Object.entries(clientFields).forEach(([field, column]) => {
        form[column] = c ? c[field] ?? '' : column === 'client_country' ? 'US' : '';
    });
    onCountryChange();
    if (c?.currency) form.currency = c.currency;
}

// True when the typed client details no longer match the picked profile.
const clientChanged = computed(() => {
    const c = selectedClient.value;
    if (!c) return false;
    return Object.entries(clientFields).some(([field, column]) => (c[field] ?? '') !== (form[column] ?? ''));
});

// Same behaviour as before: picking the client country sets tax rate and currency.
function onCountryChange() {
    form.tax_rate = form.client_country === 'DE' ? 19 : 0;
    form.currency = form.client_country === 'DE' ? 'EUR' : form.client_country === 'GB' ? 'GBP' : 'USD';
}

function addItem() {
    form.items.push(withKey({}));
}
function removeItem(index) {
    if (form.items.length > 1) form.items.splice(index, 1);
}
function moveItem(index, delta) {
    const to = index + delta;
    if (to < 0 || to >= form.items.length) return;
    const [item] = form.items.splice(index, 1);
    form.items.splice(to, 0, item);
}

const err = (key) => form.errors[key];
const itemErr = (i, f) => form.errors[`items.${i}.${f}`];
const hasErrors = computed(() => Object.keys(form.errors).length > 0);

function submit() {
    const strip = (data) => ({ ...data, items: data.items.map(({ _key, ...rest }) => rest) });
    if (isEdit.value) {
        form.transform(strip).put(`/admin/invoices/${props.invoice.id}`, { preserveScroll: true });
    } else {
        form.transform(strip).post('/admin/invoices');
    }
}

async function destroy() {
    if (await confirm({ title: `Delete invoice ${props.invoice.invoice_number}?`, message: 'The invoice and its items will be removed permanently.' })) {
        router.delete(`/admin/invoices/${props.invoice.id}`);
    }
}

const ucfirst = (s) => s.charAt(0).toUpperCase() + s.slice(1);
</script>

<template>
    <Head :title="isEdit ? `Invoice ${invoice.invoice_number}` : 'New invoice'" />

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <Link href="/admin/invoices" class="mb-2 inline-flex items-center gap-1 text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100">
                <ArrowLeft class="h-3.5 w-3.5" /> Invoices
            </Link>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-xl font-semibold tracking-tight text-zinc-900 dark:text-white sm:text-2xl">
                    {{ isEdit ? `Invoice ${invoice.invoice_number}` : 'New invoice' }}
                </h1>
                <InvoiceStatusBadge v-if="isEdit" :status="invoice.status" />
            </div>
        </div>
        <div v-if="isEdit" class="flex flex-wrap items-center gap-2">
            <a :href="`/invoices/${invoice.id}/view`" target="_blank" rel="noopener" class="btn btn-secondary"><Eye class="h-4 w-4" /> Preview</a>
            <a :href="`/invoices/${invoice.id}/download`" target="_blank" rel="noopener" class="btn btn-secondary"><Download class="h-4 w-4" /> Download PDF</a>
            <button type="button" class="btn btn-ghost btn-icon hover:!text-rose-600" aria-label="Delete invoice" title="Delete invoice" @click="destroy"><Trash2 class="h-4 w-4" /></button>
        </div>
    </div>

    <form class="space-y-6 pb-4" @submit.prevent="submit">
        <div v-if="hasErrors" class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300">
            Please fix the highlighted fields below.
        </div>

        <!-- Invoice info -->
        <section class="card">
            <div class="card-header"><h2 class="card-title">Invoice information</h2></div>
            <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-5">
                <Field label="Invoice number" :error="err('invoice_number')" :hint="isEdit ? 'Fixed once created' : ''" required for="inv-number">
                    <input id="inv-number" v-model="form.invoice_number" type="text" class="input num" :class="{ 'input-error': err('invoice_number') }" :disabled="isEdit" />
                </Field>
                <Field label="Invoice date" :error="err('invoice_date')" required for="inv-date">
                    <input id="inv-date" v-model="form.invoice_date" type="date" class="input" :class="{ 'input-error': err('invoice_date') }" />
                </Field>
                <Field label="Due date" :error="err('due_date')" for="inv-due">
                    <input id="inv-due" v-model="form.due_date" type="date" class="input" :class="{ 'input-error': err('due_date') }" />
                </Field>
                <Field label="Status" :error="err('status')" required for="inv-status">
                    <select id="inv-status" v-model="form.status" class="input">
                        <option v-for="s in statuses" :key="s" :value="s">{{ ucfirst(s) }}</option>
                    </select>
                </Field>
                <Field label="Currency" :error="err('currency')" required for="inv-currency">
                    <select id="inv-currency" v-model="form.currency" class="input">
                        <option value="USD">USD – US Dollar</option>
                        <option value="EUR">EUR – Euro</option>
                        <option value="GBP">GBP – British Pound</option>
                    </select>
                </Field>
            </div>
        </section>

        <!-- Client -->
        <section class="card">
            <div class="card-header">
                <h2 class="card-title">Client</h2>
                <Link href="/admin/clients" class="text-xs text-zinc-500 hover:text-zinc-900 hover:underline dark:hover:text-zinc-100">Manage clients</Link>
            </div>
            <div class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3">
                <Field label="Client profile" :error="err('client_id')" class="sm:col-span-2 lg:col-span-3" for="c-profile"
                    :hint="selectedClient ? 'Details below come from this profile. You can still change them for this invoice.' : 'Pick a saved client, or type a new one below.'">
                    <select id="c-profile" v-model="form.client_id" class="input" @change="onClientChange">
                        <option :value="null">+ New client</option>
                        <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}{{ c.email ? ` · ${c.email}` : '' }}</option>
                    </select>
                </Field>
                <Field label="Client name" :error="err('client_name')" required for="c-name">
                    <input id="c-name" v-model="form.client_name" type="text" class="input" :class="{ 'input-error': err('client_name') }" />
                </Field>
                <Field label="Email" :error="err('client_email')" for="c-email">
                    <input id="c-email" v-model="form.client_email" type="email" class="input" :class="{ 'input-error': err('client_email') }" />
                </Field>
                <Field label="Country" :error="err('client_country')" hint="Sets tax rate and currency (Germany: 19% VAT, EUR)." required for="c-country">
                    <select id="c-country" v-model="form.client_country" class="input" @change="onCountryChange">
                        <option v-for="(name, code) in countries" :key="code" :value="code">{{ name }}</option>
                    </select>
                </Field>
                <Field label="Address" :error="err('client_address')" class="sm:col-span-2 lg:col-span-3" for="c-address">
                    <textarea id="c-address" v-model="form.client_address" rows="2" class="input" />
                </Field>
                <Field label="City" :error="err('client_city')" for="c-city">
                    <input id="c-city" v-model="form.client_city" type="text" class="input" />
                </Field>
                <Field label="State / region" :error="err('client_state')" for="c-state">
                    <input id="c-state" v-model="form.client_state" type="text" class="input" />
                </Field>
                <Field label="Postal code" :error="err('client_postal_code')" for="c-postal">
                    <input id="c-postal" v-model="form.client_postal_code" type="text" class="input" />
                </Field>
                <Field label="Client tax ID / VAT number" :error="err('client_tax_id')" for="c-tax">
                    <input id="c-tax" v-model="form.client_tax_id" type="text" class="input" />
                </Field>
                <label v-if="!form.client_id" class="flex items-start gap-2.5 text-sm sm:col-span-2 lg:col-span-3">
                    <input v-model="form.save_client" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-brand-600" />
                    <span><span class="font-medium text-zinc-800 dark:text-zinc-200">Save as a client profile</span>
                        <span class="block text-xs text-zinc-500">Next time, pick this client from the list instead of typing.</span></span>
                </label>
                <label v-else-if="clientChanged" class="flex items-start gap-2.5 text-sm sm:col-span-2 lg:col-span-3">
                    <input v-model="form.update_client" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-brand-600" />
                    <span><span class="font-medium text-zinc-800 dark:text-zinc-200">Also update {{ selectedClient.name }}'s profile</span>
                        <span class="block text-xs text-zinc-500">Otherwise the changes apply to this invoice only.</span></span>
                </label>
            </div>
        </section>

        <!-- Business -->
        <section class="card">
            <button type="button" class="card-header w-full text-left" :class="{ 'border-b-0': !showBusiness }" :aria-expanded="showBusiness" @click="showBusiness = !showBusiness">
                <div>
                    <h2 class="card-title">Your business</h2>
                    <p v-if="!showBusiness" class="card-sub">{{ form.business_name || 'Not set' }}<span v-if="form.business_email"> · {{ form.business_email }}</span></p>
                </div>
                <ChevronDown class="h-4 w-4 text-zinc-400 transition-transform" :class="{ 'rotate-180': showBusiness }" />
            </button>
            <div v-show="showBusiness" class="grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-3">
                <Field label="Business name" :error="err('business_name')" for="b-name">
                    <input id="b-name" v-model="form.business_name" type="text" class="input" />
                </Field>
                <Field label="Email" :error="err('business_email')" for="b-email">
                    <input id="b-email" v-model="form.business_email" type="email" class="input" :class="{ 'input-error': err('business_email') }" />
                </Field>
                <Field label="Phone" :error="err('business_phone')" for="b-phone">
                    <input id="b-phone" v-model="form.business_phone" type="tel" class="input" />
                </Field>
                <Field label="Website" :error="err('business_website')" hint="Include https://" for="b-web">
                    <input id="b-web" v-model="form.business_website" type="url" class="input" :class="{ 'input-error': err('business_website') }" />
                </Field>
                <Field label="Tax ID / VAT number" :error="err('business_tax_id')" for="b-tax">
                    <input id="b-tax" v-model="form.business_tax_id" type="text" class="input" />
                </Field>
                <Field label="Country" :error="err('business_country')" for="b-country">
                    <input id="b-country" v-model="form.business_country" type="text" class="input" />
                </Field>
                <Field label="Address" :error="err('business_address')" class="sm:col-span-2 lg:col-span-3" for="b-address">
                    <textarea id="b-address" v-model="form.business_address" rows="2" class="input" />
                </Field>
                <Field label="City" :error="err('business_city')" for="b-city">
                    <input id="b-city" v-model="form.business_city" type="text" class="input" />
                </Field>
                <Field label="State / region" :error="err('business_state')" for="b-state">
                    <input id="b-state" v-model="form.business_state" type="text" class="input" />
                </Field>
                <Field label="Postal code" :error="err('business_postal_code')" for="b-postal">
                    <input id="b-postal" v-model="form.business_postal_code" type="text" class="input" />
                </Field>
            </div>
        </section>

        <!-- Items -->
        <section class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Items</h2>
                    <p class="card-sub">{{ form.items.length }} line{{ form.items.length === 1 ? '' : 's' }}</p>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" @click="addItem"><Plus class="h-4 w-4" /> Add item</button>
            </div>
            <p v-if="err('items')" class="error px-5 pt-3">{{ err('items') }}</p>

            <div class="hidden grid-cols-[minmax(0,1fr)_6rem_9rem_6rem_8rem_5.5rem] gap-3 border-b border-zinc-100 bg-zinc-50/80 px-5 py-2 text-xs font-medium text-zinc-500 dark:border-zinc-800 dark:bg-zinc-900/60 dark:text-zinc-400 lg:grid">
                <span>Description</span><span>Qty</span><span>Unit price</span><span>Tax %</span><span class="text-right">Line total</span><span />
            </div>
            <ul class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <li v-for="(item, i) in form.items" :key="item._key" class="grid gap-3 px-5 py-4 lg:grid-cols-[minmax(0,1fr)_6rem_9rem_6rem_8rem_5.5rem] lg:items-start">
                    <div>
                        <label class="label lg:sr-only" :for="`item-desc-${i}`">Description</label>
                        <textarea :id="`item-desc-${i}`" v-model="item.description" rows="2" class="input" :class="{ 'input-error': itemErr(i, 'description') }" placeholder="What did you deliver?" />
                        <p v-if="itemErr(i, 'description')" class="error">{{ itemErr(i, 'description') }}</p>
                    </div>
                    <div>
                        <label class="label lg:sr-only" :for="`item-qty-${i}`">Quantity</label>
                        <input :id="`item-qty-${i}`" v-model="item.quantity" type="number" step="0.01" min="0.01" class="input num" :class="{ 'input-error': itemErr(i, 'quantity') }" />
                        <p v-if="itemErr(i, 'quantity')" class="error">{{ itemErr(i, 'quantity') }}</p>
                    </div>
                    <div>
                        <label class="label lg:sr-only" :for="`item-price-${i}`">Unit price</label>
                        <MoneyInput :id="`item-price-${i}`" v-model="item.unit_price" :prefix="form.currency" :error="!!itemErr(i, 'unit_price')" />
                        <p v-if="itemErr(i, 'unit_price')" class="error">{{ itemErr(i, 'unit_price') }}</p>
                    </div>
                    <div>
                        <label class="label lg:sr-only" :for="`item-tax-${i}`">Tax %</label>
                        <input :id="`item-tax-${i}`" v-model="item.tax_rate" type="number" step="0.01" min="0" max="100" class="input num" :class="{ 'input-error': itemErr(i, 'tax_rate') }" />
                        <p v-if="itemErr(i, 'tax_rate')" class="error">{{ itemErr(i, 'tax_rate') }}</p>
                    </div>
                    <div class="flex items-center justify-between lg:block lg:pt-2 lg:text-right">
                        <span class="text-xs text-zinc-500 lg:hidden">Line total</span>
                        <span class="num text-sm font-medium text-zinc-900 dark:text-white">{{ fmt(lineTotal(item)) }}</span>
                    </div>
                    <div class="flex items-center justify-end gap-0.5 lg:pt-1">
                        <button type="button" class="btn btn-ghost btn-icon btn-xs" :disabled="i === 0" aria-label="Move up" @click="moveItem(i, -1)"><ArrowUp class="h-3.5 w-3.5" /></button>
                        <button type="button" class="btn btn-ghost btn-icon btn-xs" :disabled="i === form.items.length - 1" aria-label="Move down" @click="moveItem(i, 1)"><ArrowDown class="h-3.5 w-3.5" /></button>
                        <button type="button" class="btn btn-ghost btn-icon btn-xs hover:!text-rose-600" :disabled="form.items.length === 1" aria-label="Remove item" @click="removeItem(i)"><Trash2 class="h-3.5 w-3.5" /></button>
                    </div>
                </li>
            </ul>
            <div class="border-t border-zinc-100 px-5 py-3 dark:border-zinc-800">
                <button type="button" class="btn btn-ghost btn-sm" @click="addItem"><Plus class="h-4 w-4" /> Add another item</button>
            </div>
        </section>

        <!-- Totals + notes -->
        <div class="grid gap-6 lg:grid-cols-5">
            <section class="card lg:col-span-3">
                <button type="button" class="card-header w-full text-left" :class="{ 'border-b-0': !showExtra }" :aria-expanded="showExtra" @click="showExtra = !showExtra">
                    <div>
                        <h2 class="card-title">Notes & terms</h2>
                        <p v-if="!showExtra" class="card-sub">Optional message and payment terms shown on the invoice</p>
                    </div>
                    <ChevronDown class="h-4 w-4 text-zinc-400 transition-transform" :class="{ 'rotate-180': showExtra }" />
                </button>
                <div v-show="showExtra" class="space-y-4 p-5">
                    <Field label="Notes" :error="err('notes')" for="inv-notes">
                        <textarea id="inv-notes" v-model="form.notes" rows="3" class="input" />
                    </Field>
                    <Field label="Terms & conditions" :error="err('terms')" for="inv-terms">
                        <textarea id="inv-terms" v-model="form.terms" rows="3" class="input" />
                    </Field>
                </div>
            </section>

            <section class="card lg:col-span-2">
                <div class="card-header"><h2 class="card-title">Totals</h2></div>
                <div class="space-y-3 p-5 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500">Subtotal</span>
                        <span class="num font-medium">{{ fmt(subtotal) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <label for="inv-tax" class="text-zinc-500">Tax rate</label>
                        <div class="relative w-28">
                            <input id="inv-tax" v-model="form.tax_rate" type="number" step="0.01" min="0" max="100" class="input num pr-8 text-right" :class="{ 'input-error': err('tax_rate') }" />
                            <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-zinc-400">%</span>
                        </div>
                    </div>
                    <p v-if="err('tax_rate')" class="error">{{ err('tax_rate') }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-zinc-500">Tax</span>
                        <span class="num font-medium">{{ fmt(taxAmount) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-zinc-100 pt-3 dark:border-zinc-800">
                        <span class="font-semibold text-zinc-900 dark:text-white">Total</span>
                        <span class="num text-lg font-semibold text-zinc-900 dark:text-white">{{ fmt(total) }}</span>
                    </div>
                </div>
            </section>
        </div>

        <!-- Action bar -->
        <div class="sticky bottom-0 z-20 -mx-4 border-t border-zinc-200/80 bg-white/90 px-4 py-3 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/90 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="flex items-center justify-end gap-2">
                <span class="num mr-auto text-sm text-zinc-500">Total <span class="font-semibold text-zinc-900 dark:text-white">{{ fmt(total) }}</span></span>
                <Link href="/admin/invoices" class="btn btn-secondary">Cancel</Link>
                <button type="submit" class="btn btn-primary" :disabled="form.processing">{{ isEdit ? 'Save changes' : 'Create invoice' }}</button>
            </div>
        </div>
    </form>
</template>

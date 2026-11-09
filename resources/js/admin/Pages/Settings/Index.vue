<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { CalendarDays, Database, KeyRound, SlidersHorizontal, UserRound } from 'lucide-vue-next';
import PageHeader from '../../Components/PageHeader.vue';
import Field from '../../Components/Field.vue';
import { date, number } from '../../lib/format';

const props = defineProps({
    profile: Object,
    preferences: Object,
    data: Object,
});

const profileForm = useForm({ name: props.profile.name, email: props.profile.email });
const passwordForm = useForm({ current_password: '', password: '', password_confirmation: '' });
const prefForm = useForm({ currency_symbol: props.preferences.currency_symbol });

const saveProfile = () => profileForm.put('/admin/settings/profile', { preserveScroll: true });
const savePassword = () =>
    passwordForm.put('/admin/settings/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: () => passwordForm.reset('current_password'),
    });
const savePrefs = () => prefForm.put('/admin/settings/preferences', { preserveScroll: true });

const symbols = ['৳', 'Tk', '$', '€', '£', '₹'];
</script>

<template>
    <Head title="Settings" />
    <PageHeader title="Settings" description="Your account, security and display preferences." />

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <!-- Profile -->
            <form class="card" @submit.prevent="saveProfile">
                <div class="card-header">
                    <div class="flex items-center gap-2.5">
                        <UserRound class="h-4 w-4 text-zinc-400" />
                        <div>
                            <h2 class="card-title">Profile</h2>
                            <p class="card-sub">The name and email you sign in with</p>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 p-5 sm:grid-cols-2">
                    <Field label="Name" :error="profileForm.errors.name" required for="s-name">
                        <input id="s-name" v-model="profileForm.name" type="text" class="input" :class="{ 'input-error': profileForm.errors.name }" autocomplete="name" />
                    </Field>
                    <Field label="Email" :error="profileForm.errors.email" required for="s-email">
                        <input id="s-email" v-model="profileForm.email" type="email" class="input" :class="{ 'input-error': profileForm.errors.email }" autocomplete="email" />
                    </Field>
                </div>
                <div class="flex justify-end border-t border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="profileForm.processing || !profileForm.isDirty">Save profile</button>
                </div>
            </form>

            <!-- Password -->
            <form class="card" @submit.prevent="savePassword">
                <div class="card-header">
                    <div class="flex items-center gap-2.5">
                        <KeyRound class="h-4 w-4 text-zinc-400" />
                        <div>
                            <h2 class="card-title">Password</h2>
                            <p class="card-sub">Use at least 8 characters</p>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 p-5 sm:grid-cols-3">
                    <Field label="Current password" :error="passwordForm.errors.current_password" required for="s-cur">
                        <input id="s-cur" v-model="passwordForm.current_password" type="password" class="input" :class="{ 'input-error': passwordForm.errors.current_password }" autocomplete="current-password" />
                    </Field>
                    <Field label="New password" :error="passwordForm.errors.password" required for="s-new">
                        <input id="s-new" v-model="passwordForm.password" type="password" class="input" :class="{ 'input-error': passwordForm.errors.password }" autocomplete="new-password" />
                    </Field>
                    <Field label="Confirm new password" required for="s-conf">
                        <input id="s-conf" v-model="passwordForm.password_confirmation" type="password" class="input" autocomplete="new-password" />
                    </Field>
                </div>
                <div class="flex justify-end border-t border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="passwordForm.processing || !passwordForm.current_password || !passwordForm.password">Change password</button>
                </div>
            </form>

            <!-- Preferences -->
            <form class="card" @submit.prevent="savePrefs">
                <div class="card-header">
                    <div class="flex items-center gap-2.5">
                        <SlidersHorizontal class="h-4 w-4 text-zinc-400" />
                        <div>
                            <h2 class="card-title">Preferences</h2>
                            <p class="card-sub">How amounts are shown across the panel</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <Field label="Currency symbol" :error="prefForm.errors.currency_symbol" hint="Shown before every amount, e.g. ৳12,500." required for="s-cur-sym">
                        <div class="flex flex-wrap items-center gap-2">
                            <input id="s-cur-sym" v-model="prefForm.currency_symbol" type="text" maxlength="5" class="input w-24" :class="{ 'input-error': prefForm.errors.currency_symbol }" />
                            <button
                                v-for="sym in symbols"
                                :key="sym"
                                type="button"
                                class="btn btn-sm"
                                :class="prefForm.currency_symbol === sym ? 'btn-primary' : 'btn-secondary'"
                                @click="prefForm.currency_symbol = sym"
                            >{{ sym }}</button>
                        </div>
                    </Field>
                    <p class="num mt-4 text-sm text-zinc-500">Preview: <span class="font-semibold text-zinc-900 dark:text-white">{{ prefForm.currency_symbol }}12,500</span></p>
                </div>
                <div class="flex justify-end border-t border-zinc-100 px-5 py-3 dark:border-zinc-800">
                    <button type="submit" class="btn btn-primary btn-sm" :disabled="prefForm.processing || !prefForm.isDirty">Save preferences</button>
                </div>
            </form>
        </div>

        <!-- Data overview -->
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center gap-2.5">
                        <Database class="h-4 w-4 text-zinc-400" />
                        <h2 class="card-title">Your data</h2>
                    </div>
                </div>
                <ul class="divide-y divide-zinc-100 text-sm dark:divide-zinc-800">
                    <li v-for="c in data.counts" :key="c.label" class="flex items-center justify-between px-5 py-2.5">
                        <span class="text-zinc-600 dark:text-zinc-400">{{ c.label }}</span>
                        <span class="num font-medium text-zinc-900 dark:text-zinc-100">{{ number(c.count) }}</span>
                    </li>
                </ul>
                <div class="space-y-1 border-t border-zinc-100 px-5 py-3 text-xs text-zinc-500 dark:border-zinc-800">
                    <p>First transaction: <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ date(data.first_date) }}</span></p>
                    <p>Latest transaction: <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ date(data.last_date) }}</span></p>
                </div>
            </div>

            <div class="card p-5">
                <div class="flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/15 dark:text-brand-300">
                        <CalendarDays class="h-4 w-4" />
                    </span>
                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Calendar years</h3>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                            Every year runs from 1 January to 31 December. Pick the year to view from the selector in the top bar; lists, charts and reports follow it.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

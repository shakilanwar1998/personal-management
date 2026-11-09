<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Lock, Mail } from 'lucide-vue-next';

defineOptions({ layout: null });

const form = useForm({ email: '', password: '', remember: true });
const submit = () => form.post('/admin/login', { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Sign in" />
    <div class="flex min-h-screen">
        <div class="relative hidden w-[44%] overflow-hidden bg-zinc-950 lg:block">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(99,102,241,.35),transparent_55%),radial-gradient(ellipse_at_bottom_right,rgba(27,175,122,.18),transparent_50%)]" />
            <div class="relative flex h-full flex-col justify-between p-12 text-white">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-xl font-semibold ring-1 ring-white/20">৳</div>
                    <span class="text-lg font-semibold tracking-tight">Finance</span>
                </div>
                <div>
                    <h2 class="max-w-md text-3xl font-semibold leading-tight tracking-tight">Every taka, accounted for.</h2>
                    <p class="mt-4 max-w-md text-[15px] leading-relaxed text-zinc-400">
                        Income, expenses, budgets, bills, loans and investments — tracked by calendar year in one place.
                    </p>
                </div>
                <p class="text-xs text-zinc-500">Private workspace</p>
            </div>
        </div>

        <div class="flex flex-1 items-center justify-center px-6 py-12">
            <div class="w-full max-w-sm">
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-xl font-semibold text-white">৳</div>
                    <span class="text-lg font-semibold">Finance</span>
                </div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-white">Welcome back</h1>
                <p class="mt-1.5 text-sm text-zinc-500 dark:text-zinc-400">Sign in to your finance dashboard.</p>

                <form class="mt-8 space-y-4" @submit.prevent="submit">
                    <div>
                        <label for="email" class="label">Email</label>
                        <div class="relative">
                            <Mail class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                            <input id="email" v-model="form.email" type="email" autocomplete="username" autofocus required class="input h-10 pl-9" :class="{ 'input-error': form.errors.email }" />
                        </div>
                        <p v-if="form.errors.email" class="error">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label for="password" class="label">Password</label>
                        <div class="relative">
                            <Lock class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-400" />
                            <input id="password" v-model="form.password" type="password" autocomplete="current-password" required class="input h-10 pl-9" />
                        </div>
                        <p v-if="form.errors.password" class="error">{{ form.errors.password }}</p>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                        <input v-model="form.remember" type="checkbox" class="h-4 w-4 rounded" /> Keep me signed in
                    </label>
                    <button type="submit" class="btn btn-primary h-10 w-full" :disabled="form.processing">Sign in</button>
                </form>
            </div>
        </div>
    </div>
</template>

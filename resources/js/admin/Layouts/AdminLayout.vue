<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import {
    BarChart3, Banknote, CalendarClock, CalendarDays, Check, ChevronDown, FileText, Gauge, HandCoins, Landmark,
    LayoutDashboard, LogOut, Menu, Moon, PiggyBank, Plus, Receipt, Settings, Sun, Tags, TrendingUp, X,
} from 'lucide-vue-next';
import Dropdown from '../Components/Dropdown.vue';
import Toasts from '../Components/Toasts.vue';
import ConfirmDialog from '../Components/ConfirmDialog.vue';
import TransactionDialog from '../Components/TransactionDialog.vue';
import { toast } from '../composables/useToast';
import { useTheme } from '../composables/useTheme';
import { openQuickAdd, quickAdd } from '../composables/useQuickAdd';
import { initials } from '../lib/format';

const page = usePage();
const { isDark, toggle: toggleTheme } = useTheme();
const mobileOpen = ref(false);

const nav = computed(() => [
    { group: 'Overview', items: [
        { label: 'Dashboard', href: '/admin', icon: LayoutDashboard, exact: true },
        { label: 'Reports', href: '/admin/reports', icon: BarChart3 },
    ] },
    { group: 'Transactions', items: [
        { label: 'Expenses', href: '/admin/expenses', icon: Receipt },
        { label: 'Income', href: '/admin/incomes', icon: Banknote },
    ] },
    { group: 'Planning', items: [
        { label: 'Budgets', href: '/admin/budgets', icon: Gauge },
        { label: 'Bills & Subscriptions', href: '/admin/bills', icon: CalendarClock, badge: page.props.badges?.bills },
        { label: 'Savings Goals', href: '/admin/goals', icon: PiggyBank },
    ] },
    { group: 'Assets & Loans', items: [
        { label: 'Investments', href: '/admin/investments', icon: TrendingUp },
        { label: 'Money Lent', href: '/admin/lending', icon: HandCoins },
        { label: 'Money Borrowed', href: '/admin/borrowing', icon: Landmark },
    ] },
    { group: 'Business', items: [{ label: 'Invoices', href: '/admin/invoices', icon: FileText }] },
    { group: 'Setup', items: [
        { label: 'Categories', href: '/admin/categories', icon: Tags },
        { label: 'Settings', href: '/admin/settings', icon: Settings },
    ] },
]);

const path = computed(() => page.url.split('?')[0]);
const isActive = (item) => (item.exact ? path.value === item.href || path.value === item.href + '/' : path.value.startsWith(item.href));

const year = computed(() => page.props.calendar);
function selectYear(y) {
    router.post('/admin/year', { year: y }, { preserveScroll: true });
}

// Flash messages from the server → toasts.
watch(
    () => page.props.flash,
    (f) => {
        if (f?.success) toast(f.success, 'success');
        if (f?.error) toast(f.error, 'error', 6000);
    },
    { immediate: true, deep: true },
);

// Close the mobile menu after navigating.
const offNav = router.on('navigate', () => (mobileOpen.value = false));

// "N" opens the add-transaction dialog from anywhere (when not typing).
function onKey(e) {
    const tag = (e.target.tagName || '').toLowerCase();
    if (e.metaKey || e.ctrlKey || e.altKey || ['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable) return;
    if (e.key === 'n' && !quickAdd.open) {
        e.preventDefault();
        openQuickAdd('expense');
    }
}
onMounted(() => window.addEventListener('keydown', onKey));
onBeforeUnmount(() => {
    window.removeEventListener('keydown', onKey);
    offNav();
});
</script>

<template>
    <div class="min-h-screen">
        <!-- Mobile backdrop -->
        <div v-if="mobileOpen" class="fixed inset-0 z-40 bg-zinc-950/40 backdrop-blur-[2px] lg:hidden" @click="mobileOpen = false" />

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-zinc-200/80 bg-white transition-transform duration-200 dark:border-zinc-800 dark:bg-[#0c0c0e] lg:translate-x-0"
            :class="mobileOpen ? 'translate-x-0 shadow-pop' : '-translate-x-full'"
        >
            <div class="flex h-16 shrink-0 items-center gap-3 px-5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-lg font-semibold text-white shadow-sm">৳</div>
                <div class="leading-tight">
                    <p class="text-[15px] font-semibold tracking-tight text-zinc-900 dark:text-white">Finance</p>
                    <p class="text-xs text-zinc-500">Personal money manager</p>
                </div>
                <button class="btn btn-ghost btn-icon btn-sm ml-auto lg:hidden" aria-label="Close menu" @click="mobileOpen = false"><X class="h-4 w-4" /></button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 pb-6 pt-2">
                <div v-for="g in nav" :key="g.group" class="mb-5">
                    <p class="mb-1.5 px-3 text-[11px] font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">{{ g.group }}</p>
                    <Link
                        v-for="item in g.items"
                        :key="item.href"
                        :href="item.href"
                        class="group relative mb-0.5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors"
                        :class="isActive(item)
                            ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/10 dark:text-brand-200'
                            : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-400 dark:hover:bg-zinc-800/70 dark:hover:text-zinc-100'"
                    >
                        <component
                            :is="item.icon"
                            class="h-[18px] w-[18px] shrink-0"
                            :class="isActive(item) ? 'text-brand-600 dark:text-brand-300' : 'text-zinc-400 group-hover:text-zinc-600 dark:text-zinc-500 dark:group-hover:text-zinc-300'"
                        />
                        <span class="truncate">{{ item.label }}</span>
                        <span v-if="item.badge" class="num ml-auto rounded-full bg-amber-100 px-1.5 text-[11px] font-semibold text-amber-800 dark:bg-amber-500/20 dark:text-amber-300">{{ item.badge }}</span>
                    </Link>
                </div>
            </nav>

            <div class="border-t border-zinc-100 p-3 dark:border-zinc-800">
                <Dropdown align="left" width="w-56" up>
                    <template #trigger="{ toggle }">
                        <button class="flex w-56 items-center gap-3 rounded-lg p-2 text-left hover:bg-zinc-100 dark:hover:bg-zinc-800/70" @click="toggle">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-900 text-xs font-semibold text-white dark:bg-zinc-100 dark:text-zinc-900">{{ initials(page.props.auth.user?.name) }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ page.props.auth.user?.name }}</span>
                                <span class="block truncate text-xs text-zinc-500">{{ page.props.auth.user?.email }}</span>
                            </span>
                            <ChevronDown class="h-4 w-4 text-zinc-400" />
                        </button>
                    </template>
                    <div>
                        <Link href="/admin/settings" class="menu-item"><Settings class="h-4 w-4" /> Settings</Link>
                        <button class="menu-item" @click="toggleTheme">
                            <Sun v-if="isDark" class="h-4 w-4" /><Moon v-else class="h-4 w-4" /> {{ isDark ? 'Light mode' : 'Dark mode' }}
                        </button>
                        <Link href="/admin/logout" method="post" as="button" class="menu-item text-rose-600 dark:text-rose-400"><LogOut class="h-4 w-4" /> Sign out</Link>
                    </div>
                </Dropdown>
            </div>
        </aside>

        <!-- Main -->
        <div class="lg:pl-64">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-zinc-200/80 bg-white/80 px-4 backdrop-blur-md dark:border-zinc-800 dark:bg-zinc-950/80 sm:px-6 lg:px-8">
                <button class="btn btn-ghost btn-icon -ml-2 lg:hidden" aria-label="Open menu" @click="mobileOpen = true"><Menu class="h-5 w-5" /></button>

                <Dropdown v-if="year" align="left" width="w-44">
                    <template #trigger="{ toggle }">
                        <button class="btn btn-secondary btn-sm gap-2" @click="toggle" title="Year shown in reports and lists (January–December)">
                            <CalendarDays class="h-4 w-4 text-zinc-400" />
                            <span class="num">{{ year.selected }}</span>
                            <span class="hidden text-xs font-normal text-zinc-400 sm:inline">Jan – Dec</span>
                            <ChevronDown class="h-3.5 w-3.5 text-zinc-400" />
                        </button>
                    </template>
                    <p class="px-2.5 pb-1 pt-1.5 text-[11px] font-semibold uppercase tracking-wider text-zinc-400">Calendar year</p>
                    <div class="max-h-72 overflow-y-auto">
                        <button v-for="y in year.available" :key="y" class="menu-item num" @click="selectYear(y)">
                            <span class="flex-1">{{ y }}</span>
                            <span v-if="y === year.current" class="text-[11px] text-zinc-400">current</span>
                            <Check v-if="y === year.selected" class="h-4 w-4 text-brand-600" />
                        </button>
                    </div>
                </Dropdown>

                <div class="ml-auto flex items-center gap-1.5">
                    <button class="btn btn-ghost btn-icon" :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'" @click="toggleTheme">
                        <Sun v-if="isDark" class="h-[18px] w-[18px]" /><Moon v-else class="h-[18px] w-[18px]" />
                    </button>
                    <button class="btn btn-primary btn-sm sm:h-9 sm:px-3.5" title="Add transaction (N)" @click="openQuickAdd('expense')">
                        <Plus class="h-4 w-4" /> <span class="hidden sm:inline">Add transaction</span>
                        <kbd class="ml-1 hidden rounded border border-white/25 px-1 text-[10px] font-medium text-white/80 md:inline">N</kbd>
                    </button>
                </div>
            </header>

            <main class="mx-auto max-w-[1400px] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                <slot />
            </main>
        </div>

        <TransactionDialog />
        <ConfirmDialog />
        <Toasts />
    </div>
</template>

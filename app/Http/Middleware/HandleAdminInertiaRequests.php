<?php

namespace App\Http\Middleware;

use App\Models\ExpenseCategory;
use App\Models\IncomeCategory;
use App\Models\RecurringBill;
use App\Models\Settings;
use App\Services\YearService;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Inertia setup for the admin panel (separate root view and assets from the public site).
 */
class HandleAdminInertiaRequests extends Middleware
{
    protected $rootView = 'admin';

    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user ? $user->only('id', 'name', 'email') : null,
            ],
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'currency' => fn () => $user ? Settings::get('currency_symbol', '৳') : '৳',
            'calendar' => fn () => $user ? [
                'selected' => YearService::selected(),
                'available' => YearService::available(),
                'current' => now()->year,
            ] : null,
            'lookups' => fn () => $user ? [
                'expenseCategories' => ExpenseCategory::options(),
                'incomeCategories' => IncomeCategory::ordered()->map->only('id', 'name', 'icon', 'color')->values(),
            ] : null,
            'badges' => fn () => $user ? [
                'bills' => RecurringBill::where('is_active', true)
                    ->whereDate('next_due_date', '<=', now()->addDays(7)->toDateString())
                    ->count(),
            ] : null,
        ]);
    }
}

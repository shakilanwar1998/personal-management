<?php

namespace App\Http\Controllers\Admin;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SettingsController extends AdminController
{
    private array $tables = [
        'expenses' => 'Expenses',
        'incomes' => 'Incomes',
        'expense_categories' => 'Expense categories',
        'income_categories' => 'Income categories',
        'investments' => 'Investments',
        'lending_transactions' => 'Money lent',
        'borrowing_transactions' => 'Money borrowed',
        'invoices' => 'Invoices',
        'budgets' => 'Budgets',
        'recurring_bills' => 'Bills & subscriptions',
        'savings_goals' => 'Savings goals',
    ];

    public function index(Request $request)
    {
        $counts = [];
        foreach ($this->tables as $table => $label) {
            if (Schema::hasTable($table)) {
                $counts[] = ['label' => $label, 'count' => DB::table($table)->count()];
            }
        }

        $first = collect([DB::table('expenses')->min('date'), DB::table('incomes')->min('date')])->filter()->min();
        $last = collect([DB::table('expenses')->max('date'), DB::table('incomes')->max('date')])->filter()->max();

        return Inertia::render('Settings/Index', [
            'profile' => $request->user()->only('name', 'email'),
            'preferences' => [
                'currency_symbol' => Settings::get('currency_symbol', '৳'),
            ],
            'data' => [
                'counts' => $counts,
                'first_date' => $first,
                'last_date' => $last,
            ],
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        // The User model's "hashed" cast hashes the new password on save.
        $request->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password changed.');
    }

    public function updatePreferences(Request $request)
    {
        $data = $request->validate([
            'currency_symbol' => ['required', 'string', 'max:5'],
        ]);

        Settings::set('currency_symbol', trim($data['currency_symbol']));

        return back()->with('success', 'Preferences saved.');
    }
}

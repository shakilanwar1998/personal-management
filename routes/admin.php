<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
| Admin panel (Inertia + Vue). Loaded from routes/web.php under the /admin prefix.
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:10,1')->name('admin.login');
});

Route::middleware('auth')->name('admin.')->group(function () {
    Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');

    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::post('year', [Admin\YearController::class, 'update'])->name('year');

    // Transactions
    Route::get('expenses/export', [Admin\ExpenseController::class, 'export'])->name('expenses.export');
    Route::post('expenses/bulk-delete', [Admin\ExpenseController::class, 'bulkDestroy'])->name('expenses.bulk-delete');
    Route::resource('expenses', Admin\ExpenseController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('incomes/export', [Admin\IncomeController::class, 'export'])->name('incomes.export');
    Route::post('incomes/bulk-delete', [Admin\IncomeController::class, 'bulkDestroy'])->name('incomes.bulk-delete');
    Route::resource('incomes', Admin\IncomeController::class)->only(['index', 'store', 'update', 'destroy']);

    // Categories
    Route::get('categories', [Admin\CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories/expense', [Admin\CategoryController::class, 'storeExpense'])->name('categories.expense.store');
    Route::put('categories/expense/{category}', [Admin\CategoryController::class, 'updateExpense'])->name('categories.expense.update');
    Route::delete('categories/expense/{category}', [Admin\CategoryController::class, 'destroyExpense'])->name('categories.expense.destroy');
    Route::post('categories/expense/{category}/merge', [Admin\CategoryController::class, 'mergeExpense'])->name('categories.expense.merge');
    Route::post('categories/expense/{category}/toggle-stats', [Admin\CategoryController::class, 'toggleStats'])->name('categories.expense.toggle-stats');
    Route::post('categories/income', [Admin\CategoryController::class, 'storeIncome'])->name('categories.income.store');
    Route::put('categories/income/{incomeCategory}', [Admin\CategoryController::class, 'updateIncome'])->name('categories.income.update');
    Route::delete('categories/income/{incomeCategory}', [Admin\CategoryController::class, 'destroyIncome'])->name('categories.income.destroy');

    // Planning
    Route::resource('budgets', Admin\BudgetController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('bills/{bill}/pay', [Admin\RecurringBillController::class, 'pay'])->name('bills.pay');
    Route::post('bills/{bill}/skip', [Admin\RecurringBillController::class, 'skip'])->name('bills.skip');
    Route::resource('bills', Admin\RecurringBillController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('goals/{goal}/contributions', [Admin\SavingsGoalController::class, 'contribute'])->name('goals.contribute');
    Route::delete('goals/contributions/{contribution}', [Admin\SavingsGoalController::class, 'destroyContribution'])->name('goals.contributions.destroy');
    Route::resource('goals', Admin\SavingsGoalController::class)->only(['index', 'store', 'update', 'destroy']);

    // Assets & loans
    Route::post('investments/{investment}/return', [Admin\InvestmentController::class, 'recordReturn'])->name('investments.return');
    Route::post('investments/bulk-delete', [Admin\InvestmentController::class, 'bulkDestroy'])->name('investments.bulk-delete');
    Route::resource('investments', Admin\InvestmentController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('lending/{lending}/return', [Admin\LendingController::class, 'recordReturn'])->name('lending.return');
    Route::post('lending/bulk-delete', [Admin\LendingController::class, 'bulkDestroy'])->name('lending.bulk-delete');
    Route::resource('lending', Admin\LendingController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::post('borrowing/{borrowing}/repay', [Admin\BorrowingController::class, 'repay'])->name('borrowing.repay');
    Route::post('borrowing/bulk-delete', [Admin\BorrowingController::class, 'bulkDestroy'])->name('borrowing.bulk-delete');
    Route::resource('borrowing', Admin\BorrowingController::class)->only(['index', 'store', 'update', 'destroy']);

    // Business
    Route::resource('clients', Admin\ClientController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('invoices/{invoice}/payment', [Admin\InvoiceController::class, 'recordPayment'])->name('invoices.payment');
    Route::post('invoices/{invoice}/duplicate', [Admin\InvoiceController::class, 'duplicate'])->name('invoices.duplicate');
    Route::post('invoices/bulk-delete', [Admin\InvoiceController::class, 'bulkDestroy'])->name('invoices.bulk-delete');
    Route::resource('invoices', Admin\InvoiceController::class)->except(['show']);

    // Insights & settings
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [Admin\ReportController::class, 'export'])->name('reports.export');

    Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile', [Admin\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('settings/password', [Admin\SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::put('settings/preferences', [Admin\SettingsController::class, 'updatePreferences'])->name('settings.preferences');
});

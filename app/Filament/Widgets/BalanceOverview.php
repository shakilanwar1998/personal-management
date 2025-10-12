<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\LendingTransaction;
use App\Services\GlobalFinancialYearService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class BalanceOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $financialYear = GlobalFinancialYearService::getSelectedFinancialYear();
        $dates = GlobalFinancialYearService::getSelectedFinancialYearDates();
        
        $currentMonth = now()->format('M Y');
        $financialYearLabel = $financialYear . ' FY';

        $income = $this->currentMonthIncome();
        $expense = $this->currentMonthExpense();
        $fyIncome = $this->financialYearIncome($dates);
        $fyExpense = $this->financialYearExpense($dates);

        $currentBalance = $this->currentBalance();
        $fySavings = $this->financialYearSavings($dates);

        return [
            Stat::make('Earning ' . $currentMonth, number_format($income).' BDT'),
            Stat::make('Expense ' . $currentMonth, number_format($expense).' BDT'),
            Stat::make('Current Balance', number_format($currentBalance).' BDT'),
            Stat::make('FY Savings (' . $financialYearLabel . ')', number_format($fySavings).' BDT')
        ];
    }

    private function currentMonthIncome()
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $startDate = "{$currentYear}-{$currentMonth}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        $sum = Income::whereBetween('date', [$startDate, $endDate])->sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }

    private function currentMonthExpense()
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $startDate = "{$currentYear}-{$currentMonth}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        $sum = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }

    private function totalSavings()
    {
        $income = Income::sum('amount') ?? 0;
        $expense = Expense::sum('amount') ?? 0;
        $investments = Investment::where('is_lifetime', true)->sum('amount') ?? 0;

        return (float) $income - ((float) $expense + (float) $investments);
    }

    private function financialYearIncome($dates)
    {
        $sum = Income::whereBetween('date', [$dates['start'], $dates['end']])->sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }

    private function financialYearExpense($dates)
    {
        $sum = Expense::whereBetween('date', [$dates['start'], $dates['end']])->sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }

    private function financialYearSavings($dates)
    {
        $income = $this->financialYearIncome($dates);
        $expense = $this->financialYearExpense($dates);
        $investments = Investment::whereBetween('date', [$dates['start'], $dates['end']])
            ->where('is_lifetime', true)
            ->sum('amount') ?? 0;
        
        return (float) $income - ((float) $expense + (float) $investments);
    }

    private function currentBalance()
    {
        $income = Income::sum('amount') ?? 0;
        $expense = Expense::sum('amount') ?? 0;
        $lendingTransactions = LendingTransaction::where('is_returned', false)->sum('amount') ?? 0;
        $investments = Investment::where('is_returned', false)->sum('amount') ?? 0;

        return (float) $income - ((float) $expense + (float) $lendingTransactions + (float) $investments);
    }
}


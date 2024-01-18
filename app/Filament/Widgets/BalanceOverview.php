<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\LendingTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BalanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currentDate = new \DateTime();
        $lastMonth = $currentDate->format('M Y');

        $income = $this->currentMonthIncome();
        $expense = $this->currentMonthExpense();

        $currentBalance = $this->currentBalance();
        $savings = $this->totalSavings();

        return [
            Stat::make('Earning ' . $lastMonth, number_format($income).' BDT'),
            Stat::make('Expense ' . $lastMonth, number_format($expense).' BDT'),
            Stat::make('Remaining Balance ', number_format($currentBalance).' BDT'),
            Stat::make('Total Savings', number_format($savings).' BDT')
        ];
    }

    private function currentMonthIncome()
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $startDate = "{$currentYear}-{$currentMonth}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        return Income::whereBetween('date', [$startDate, $endDate])->pluck('amount')->sum();
    }

    private function currentMonthExpense()
    {
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        $startDate = "{$currentYear}-{$currentMonth}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        return Expense::whereBetween('date', [$startDate, $endDate])->pluck('amount')->sum();
    }

    private function totalSavings()
    {
        $income = Income::pluck('amount')->sum();
        $expense = Expense::pluck('amount')->sum();
        $investments = Investment::where('is_lifetime',true)->pluck('amount')->sum();

        return $income - ($expense + $investments);
    }

    private function currentBalance()
    {
        $income = Income::pluck('amount')->sum();
        $expense = Expense::pluck('amount')->sum();

        $lendingTransactions = LendingTransaction::where('is_returned',false)->pluck('amount')->sum();
        $investments = Investment::where('is_returned',false)->pluck('amount')->sum();

        return $income - ($expense + $lendingTransactions + $investments);
    }


}


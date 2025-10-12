<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\LendingTransaction;
use App\Models\BorrowingTransaction;
use App\Services\GlobalFinancialYearService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class FinancialHealthWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        // Current month data
        $currentIncome = $this->getMonthlyTotal(Income::class, $currentMonth);
        $currentExpenses = $this->getMonthlyTotal(Expense::class, $currentMonth);
        $currentSavings = $currentIncome - $currentExpenses;
        
        // Last month data
        $lastIncome = $this->getMonthlyTotal(Income::class, $lastMonth);
        $lastExpenses = $this->getMonthlyTotal(Expense::class, $lastMonth);
        $lastSavings = $lastIncome - $lastExpenses;
        
        // Calculate growth rates
        $incomeGrowth = $lastIncome > 0 ? (($currentIncome - $lastIncome) / $lastIncome) * 100 : 0;
        $expenseGrowth = $lastExpenses > 0 ? (($currentExpenses - $lastExpenses) / $lastExpenses) * 100 : 0;
        $savingsGrowth = $lastSavings > 0 ? (($currentSavings - $lastSavings) / $lastSavings) * 100 : 0;
        
        // Financial health metrics
        $totalAssets = $this->getTotalAssets();
        $totalLiabilities = $this->getTotalLiabilities();
        $netWorth = $totalAssets - $totalLiabilities;
        
        return [
            Stat::make('Monthly Savings', '৳' . number_format($currentSavings, 2))
                ->description($this->getGrowthDescription($savingsGrowth, 'savings'))
                ->descriptionIcon($savingsGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($savingsGrowth >= 0 ? 'success' : 'danger'),
                
            Stat::make('Income Growth', number_format($incomeGrowth, 1) . '%')
                ->description('vs last month')
                ->descriptionIcon($incomeGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($incomeGrowth >= 0 ? 'success' : 'danger'),
                
            Stat::make('Expense Growth', number_format($expenseGrowth, 1) . '%')
                ->description('vs last month')
                ->descriptionIcon($expenseGrowth <= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($expenseGrowth <= 0 ? 'success' : 'danger'),
                
            Stat::make('Net Worth', '৳' . number_format($netWorth, 2))
                ->description('Total Assets - Liabilities')
                ->descriptionIcon($netWorth >= 0 ? 'heroicon-m-banknotes' : 'heroicon-m-exclamation-triangle')
                ->color($netWorth >= 0 ? 'success' : 'warning'),
        ];
    }
    
    private function getMonthlyTotal($model, Carbon $month)
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();
        
        $sum = $model::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }
    
    private function getGrowthDescription($growth, $type)
    {
        if ($growth > 0) {
            return "↗ {$type} increased by " . number_format(abs($growth), 1) . "%";
        } elseif ($growth < 0) {
            return "↘ {$type} decreased by " . number_format(abs($growth), 1) . "%";
        } else {
            return "→ {$type} unchanged";
        }
    }
    
    private function getTotalAssets()
    {
        $totalIncome = Income::sum('amount') ?? 0;
        $totalExpenses = Expense::sum('amount') ?? 0;
        $activeInvestments = Investment::where('is_returned', false)->sum('amount') ?? 0;
        $lentMoney = LendingTransaction::where('is_returned', false)->sum('amount') ?? 0;
        
        return (float) $totalIncome - (float) $totalExpenses + (float) $activeInvestments + (float) $lentMoney;
    }
    
    private function getTotalLiabilities()
    {
        $sum = BorrowingTransaction::sum('amount');
        return is_numeric($sum) ? (float) $sum : 0.0;
    }
}

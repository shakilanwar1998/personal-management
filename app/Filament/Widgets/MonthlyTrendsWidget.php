<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Income;
use App\Services\GlobalFinancialYearService;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class MonthlyTrendsWidget extends ChartWidget
{
    
    protected static ?string $heading = 'Financial Year Monthly Trends';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $financialYear = GlobalFinancialYearService::getSelectedFinancialYear();
        $dates = GlobalFinancialYearService::getSelectedFinancialYearDates();
        
        $months = [];
        $incomeData = [];
        $expenseData = [];
        $savingsData = [];

        // Financial year months: Jul, Aug, Sep, Oct, Nov, Dec, Jan, Feb, Mar, Apr, May, Jun
        $monthNames = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $monthNumbers = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];

        for ($i = 0; $i < 12; $i++) {
            $month = $monthNumbers[$i];
            $year = ($month >= 7) ? $dates['start']->year : $dates['end']->year;
            
            $months[] = $monthNames[$i] . ' ' . $year;
            
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();
            
            $income = Income::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount') ?? 0;
            $expenses = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount') ?? 0;
            $savings = (float) $income - (float) $expenses;
            
            $incomeData[] = $income;
            $expenseData[] = $expenses;
            $savingsData[] = $savings;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Expenses',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Savings',
                    'data' => $savingsData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

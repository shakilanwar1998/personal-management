<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use App\Services\GlobalFinancialYearService;
use Filament\Widgets\ChartWidget;

class MonthlyIncomeOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $financialYear = GlobalFinancialYearService::getSelectedFinancialYear();
        $dates = GlobalFinancialYearService::getSelectedFinancialYearDates();
        self::$heading = $financialYear . ' Financial Year';
        
        // Financial year months: Jul, Aug, Sep, Oct, Nov, Dec, Jan, Feb, Mar, Apr, May, Jun
        $monthlyData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $monthNames = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $monthNumbers = [7, 8, 9, 10, 11, 12, 1, 2, 3, 4, 5, 6];

        for ($i = 0; $i < 12; $i++) {
            $month = $monthNumbers[$i];
            $year = ($month >= 7) ? $dates['start']->year : $dates['end']->year;

            $incomeSum = Income::whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            $monthlyData[$i] = (float) $incomeSum;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Income (FY)',
                    'data' => $monthlyData,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $monthNames,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

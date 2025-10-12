<?php

namespace App\Filament\Resources\IncomeResource\Widgets;

use App\Models\Income;
use App\Services\GlobalFinancialYearService;
use Filament\Widgets\ChartWidget;

class IncomeSourceOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $financialYear = GlobalFinancialYearService::getSelectedFinancialYear();
        $dates = GlobalFinancialYearService::getSelectedFinancialYearDates();
        self::$heading = $financialYear . ' Financial Year';

        $incomeSources = ['Salary', 'Remittance', 'Business', 'Gifts'];
        $expenses = array();
        
        foreach ($incomeSources as $source) {
            $sum = Income::whereBetween('date', [$dates['start'], $dates['end']])
                ->where('income_source', $source)
                ->sum('amount');
            
            $expenses[$source] = is_numeric($sum) ? (float) $sum : 0.0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Yearly Income',
                    'data' => array_values($expenses),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => array_keys($expenses),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
